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

            // Verify session is valid
            if (empty($sessionId) || $sessionId === 'undefined') {
                return response()->json(['reply' => 'Error: Session ID is missing.'], 400);
            }

            // 1. Save User Message
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'user',
                'content' => $userMessage
            ]);

            // 2. Build the message array with history and system prompt
            $messages = $this->buildMessageHistory($sessionId);

            // 3. Call OpenRouter API
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemini-2.0-flash-lite:free',
                    'messages' => $messages,
                ]);

            if ($response->failed()) {
                Log::error('OpenRouter API Error: ' . $response->body());
                return response()->json(['reply' => 'AI Error: ' . $response->status() . ' - ' . $response->reason()], 500);
            }

            $botReply = $response->json('choices.0.message.content') ?? 'I could not generate a reply.';

            // 4. Save Bot Response
            ChatMessage::create([
                'session_id' => $sessionId,
                'role' => 'assistant',
                'content' => $botReply
            ]);

            return response()->json(['reply' => $botReply]);

        } catch (\Exception $e) {
            // Log the exact PHP error so you can read it in storage/logs/laravel.log
            Log::error('Chatbot Controller Exception: ' . $e->getMessage());
            return response()->json(['reply' => 'Internal Server Error: ' . $e->getMessage()], 500);
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
