<?php

namespace App\Http\Controllers;

use App\Models\CustomerComplaint;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $complaint = CustomerComplaint::create($validated);
        } catch (\Exception $e) {
            Log::error('Failed to save customer complaint: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unable to send your message right now. Please try again later.'], 500);
            }

            return back()->with('error', 'Unable to send your message right now. Please try again later.');
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Thank you! Your message has been received. Our team will follow up soon.']);
        }

        return back()->with('success', 'Thank you! Your message has been received. Our team will follow up soon.');
    }
}
