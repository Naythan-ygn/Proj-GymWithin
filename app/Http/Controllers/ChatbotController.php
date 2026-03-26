<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'required|string'
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->input('session_id');

        // 1. Log User Message
        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'content' => $userMessage
        ]);

        // 2. Fetch Context (Available Products)
        $availableProducts = Product::where('stock', '>', 0)
            ->select('name', 'price', 'description')
            ->get()
            ->toJson();

        // 3. Construct System Prompt
        $systemPrompt = "You are the GymWithin Assistant, a helpful sales and support bot for a premium fitness equipment store.
        Be concise, friendly, and professional.
        Here is the current real-time inventory of available products: {$availableProducts}.
        Only recommend products that are in this list. If asked about a product not on the list, apologize and say it's currently out of stock.";

        // 4. Fetch Chat History
        $history = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $msg) {
            $messages[] = ['role' => $msg->role, 'content' => $msg->content];
        }

        // 5. Call OpenRouter API
        /** @var \Illuminate\Http\Client\Response $response */

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'HTTP-Referer' => config('services.openrouter.url'),
            'X-Title' => config('services.openrouter.name'),
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemini-2.5-flash-lite',
                    'messages' => [
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                ]);

        // Handle potential API errors gracefully
        if ($response->failed()) {
            Log::error('OpenRouter API Error: ' . $response->body());
            return response()->json(['reply' => 'I am currently experiencing technical difficulties. Please try again later.']);
        }

        $botReply = $response->json('choices.0.message.content') ?? 'Sorry, I could not process that request.';

        // 6. Log Bot Response
        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'content' => $botReply
        ]);

        return response()->json(['reply' => $botReply]);
    }
}
