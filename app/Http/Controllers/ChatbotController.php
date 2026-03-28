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
                        'content' => (string)$msg['content']
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
}
