<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'session_id' => 'required|string'
            ]);

            $sessionId = $request->input('session_id');
            $userMessage = $request->input('message');

            // Validate session ID
            if (empty($sessionId) || $sessionId === 'undefined') {
                return response()->json(['reply' => 'Session ID is missing or invalid. Please refresh the page.'], 400);
            }

            // Create or update chat session
            \App\Models\ChatSession::updateOrCreate(
                ['session_id' => $sessionId],
                ['updated_at' => now()]
            );

            // Save user message
            try {
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'role' => 'user',
                    'content' => $userMessage
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to save user message: ' . $e->getMessage());
                // Continue even if save fails - don't block the chat
            }

            // Build message history
            $messages = $this->buildMessageHistory($sessionId);

            // Prepare API messages
            $apiMessages = [];
            foreach ($messages as $msg) {
                if (!empty($msg['content'])) {
                    $apiMessages[] = [
                        'role' => $msg['role'],
                        'content' => (string) $msg['content']
                    ];
                }
            }

            // Check if API key is configured
            $apiKey = config('services.openrouter.key');
            if (empty($apiKey)) {
                Log::error('OpenRouter API key is not configured');
                return response()->json(['reply' => 'Chat service is temporarily unavailable. Please try again later.'], 500);
            }

            // Call OpenRouter API
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'GymWithin_Bot_v1',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                        'model' => 'openai/gpt-4o-mini',
                        'messages' => array_values($apiMessages),
                    ]);

            if ($response->failed()) {
                $errorMessage = $response->json('error.message') ?? $response->status() . ' - ' . $response->reason();
                Log::error('OpenRouter API Error: ' . $errorMessage);
                return response()->json(['reply' => 'I\'m having trouble connecting right now. Please try again in a moment.'], 500);
            }

            $responseData = $response->json();

            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('OpenRouter API Response Missing Content: ' . json_encode($responseData));
                return response()->json(['reply' => 'I received an unexpected response. Please try again.'], 500);
            }

            $botReply = $responseData['choices'][0]['message']['content'];

            // Save bot response
            try {
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'role' => 'assistant',
                    'content' => $botReply
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to save bot message: ' . $e->getMessage());
                // Continue - the user still gets their response
            }

            return response()->json(['reply' => $botReply]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['reply' => 'Invalid request. Please try again.'], 422);
        } catch (\Exception $e) {
            Log::error('Chatbot Controller Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['reply' => 'An internal error occurred. Our team has been notified.'], 500);
        }
    }

    /**
     * Helper method to generate the system prompt and pull chat history.
     */
    private function buildMessageHistory($sessionId)
    {
        try {
            $availableProducts = Product::where('stock', '>', 0)
                ->select('name', 'price', 'description')
                ->get()
                ->toJson();
        } catch (\Exception $e) {
            Log::warning('Chatbot Product Fetch Error: ' . $e->getMessage());
            $availableProducts = "[]";
        }

        $systemPrompt = "You are the GymWithin Assistant. Inventory: {$availableProducts}.";

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        // Grab the last 10 messages for context
        $history = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        foreach ($history as $msg) {
            $messages[] = ['role' => $msg->role, 'content' => $msg->content];
        }

        return $messages;
    }

    public function getHistory($sessionId)
    {
        if (empty($sessionId) || $sessionId === 'undefined') {
            return response()->json(['error' => 'Missing session id'], 400);
        }

        try {
            $history = ChatMessage::where('session_id', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($history);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Chat history unavailable'], 503);
        }
    }

    /**
     * Handle structured actions requested by the chatbot.
     * Actions must be explicitly allowed and validated here.
     */
    public function handleAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'params' => 'sometimes|array'
        ]);

        $action = $request->input('action');
        $params = $request->input('params', []);

        try {
            switch ($action) {
                case 'list_products':
                    return $this->actionListProducts($params);
                case 'get_order':
                    return $this->actionGetOrder($params, $request->user());
                case 'create_support_ticket':
                    return $this->actionCreateSupportTicket($params, $request->user());
                case 'create_order':
                    return $this->actionCreateOrder($params, $request->user());
                case 'order_analysis':
                    return $this->actionOrderAnalysis($params, $request->user());
                default:
                    return response()->json(['error' => 'Unknown action'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Chat action failed: ' . $e->getMessage());
            return response()->json(['error' => 'Action failed: ' . $e->getMessage()], 500);
        }
    }

    private function actionListProducts(array $params)
    {
        $limit = isset($params['limit']) ? intval($params['limit']) : 25;
        $products = Product::select('id', 'name', 'sku', 'price', 'stock', 'category_id')
            ->where('stock', '>', 0)
            ->take(min($limit, 100))
            ->get();

        return response()->json(['products' => $products]);
    }

    private function actionGetOrder(array $params, $user)
    {
        if (empty($params['order_number']) && empty($params['id'])) {
            return response()->json(['error' => 'order_number or id is required'], 422);
        }

        $orderQuery = \App\Models\Order::with(['items.product', 'transaction']);

        if (!empty($params['id'])) {
            $orderQuery->where('id', $params['id']);
        } else {
            $orderQuery->where('order_number', $params['order_number']);
        }

        $order = $orderQuery->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Authorization: only owners or admins can view full order details
        if (!$user || ($user->id !== $order->user_id && $user->role !== 'admin')) {
            return response()->json(['error' => 'Unauthorized to view this order'], 403);
        }

        return response()->json(['order' => $order]);
    }

    private function actionCreateSupportTicket(array $params, $user)
    {
        if (!$user) {
            return response()->json(['error' => 'Authentication required to create support tickets'], 401);
        }

        $subject = $params['subject'] ?? 'Support Request from Chatbot';
        $message = $params['message'] ?? '';

        if (empty(trim($message))) {
            return response()->json(['error' => 'Message is required'], 422);
        }

        // Store a simple support entry using ChatMessage for now (no new migration required)
        $sessionId = 'support-' . uniqid();
        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'support_ticket',
            'content' => json_encode(['user_id' => $user->id, 'subject' => $subject, 'message' => $message])
        ]);

        // You may want to notify staff here (email, webhook, etc.)

        return response()->json(['ticket' => ['id' => $sessionId, 'subject' => $subject, 'status' => 'open']]);
    }

    private function actionCreateOrder(array $params, $user)
    {
        if (!$user) {
            return response()->json(['error' => 'Authentication required to place orders'], 401);
        }

        // Very small, safe skeleton for creating a pending order.
        // Expect $params['items'] = array of { product_id or sku, quantity }
        $items = $params['items'] ?? [];
        if (empty($items) || !is_array($items)) {
            return response()->json(['error' => 'Order items required'], 422);
        }

        // Build order lines and calculate total
        $total = 0;
        $orderItems = [];
        foreach ($items as $line) {
            $product = null;
            if (!empty($line['product_id'])) {
                $product = Product::find($line['product_id']);
            } elseif (!empty($line['sku'])) {
                $product = Product::where('sku', $line['sku'])->first();
            }
            if (!$product)
                continue;
            $qty = max(1, intval($line['quantity'] ?? 1));
            $subtotal = $product->price * $qty;
            $total += $subtotal;
            $orderItems[] = ['product_id' => $product->id, 'quantity' => $qty, 'price' => $product->price];
        }

        if (empty($orderItems)) {
            return response()->json(['error' => 'No valid order items found'], 422);
        }

        // Create order record (payment will be processed separately by Checkout)
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_price' => $total,
            'payment_status' => 'pending',
        ]);

        foreach ($orderItems as $li) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $li['product_id'],
                'quantity' => $li['quantity'],
                'price' => $li['price'],
            ]);
        }

        return response()->json(['order' => $order->load('items.product')]);
    }

    private function actionOrderAnalysis(array $params, $user)
    {
        // If admin, allow site-wide analysis
        if ($user && $user->role === 'admin') {
            $totalOrders = \App\Models\Order::count();
            $totalRevenue = \App\Models\Order::sum('total_price');
            $topProducts = Product::select('name')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->selectRaw('products.name, SUM(order_items.quantity) as sold')
                ->groupBy('products.name')
                ->orderByDesc('sold')
                ->take(5)
                ->get();

            return response()->json(['total_orders' => $totalOrders, 'total_revenue' => $totalRevenue, 'top_products' => $topProducts]);
        }

        // Otherwise return user-scoped metrics
        if (!$user) {
            return response()->json(['error' => 'Authentication required for analysis'], 401);
        }

        $userOrders = \App\Models\Order::where('user_id', $user->id)->count();
        $userSpend = \App\Models\Order::where('user_id', $user->id)->sum('total_price');

        return response()->json(['orders' => $userOrders, 'total_spent' => $userSpend]);
    }
}
