<!-- Chatbot Widget -->
<div class="chatbot-widget">
    <div id="chatbotWindow" class="chatbot-window">
        <div class="chatbot-header">
            <div>
                <h3 class="font-bold text-white">GymWithin Assistant</h3>
                <p class="text-xs text-white/80">Always here to help</p>
            </div>
            <button id="closeChatbot" class="text-white hover:text-white/80 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="chatbotMessages" class="chatbot-messages">
            <div class="chatbot-message bot">
                👋 Hello! I'm your GymWithin assistant. How can I help you today?
            </div>
            <div class="chatbot-message bot">
                I can help you with:
                <br />• Product recommendations
                <br />• Pricing and financing
                <br />• Shipping information
                <br />• Technical support
            </div>
        </div>

        <div class="chatbot-input-area">
            <input type="text" id="chatbotInput" class="chatbot-input" placeholder="Type your message..."
                autocomplete="off">
            <button id="chatbotSend" class="chatbot-send">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
    </div>

    <button id="chatbotButton" class="chatbot-button">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
</div>