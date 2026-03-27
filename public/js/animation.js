// Motion.dev Scroll Animation Script
// --- NEW: Page Load Entrance Animation ---
const { scroll, animate, inView, stagger } = Motion;

// --- FIX: Zero-Flicker Entrance ---
function playEntrance() {
    if (typeof Motion === "undefined") {
        // If Motion isn't ready yet, try again in a heartbeat
        requestAnimationFrame(playEntrance);
        return;
    }

    const { animate, stagger } = Motion;
    const elements = document.querySelectorAll(".loading-shield");

    // 1. Remove the static "shield" and add the transition class
    elements.forEach((el) => {
        el.classList.remove("loading-shield");
        el.classList.add("hero-transition");
    });

    // 2. Trigger the Motion.dev sequence
    animate(
        ".hero-transition",
        { opacity: [0, 1], y: [20, 0] },
        {
            delay: stagger(0.15, { startDelay: 0.2 }),
            duration: 0.8,
            easing: [0.22, 1, 0.36, 1], // Quintic ease-out for a premium feel
        },
    );
}

// Trigger on DOM Ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", playEntrance);
} else {
    playEntrance();
}

// Critical for Redirects: Handle the Back-Forward Cache
window.addEventListener("pageshow", (event) => {
    // If the user is coming from the login redirect or back button
    if (
        event.persisted ||
        performance.getEntriesByType("navigation")[0].type === "back_forward"
    ) {
        playEntrance();
    }
});

// Hero Image Scroll Animation with Motion.dev
const heroImage = document.getElementById("heroImage");
const heroWrapper = document.querySelector(".benefits-hero-custom");

if (heroImage && heroWrapper) {
    scroll(
        animate(heroImage, {
            scale: [1, 1.3],
            opacity: [1, 0.4],
        }),
        {
            target: heroWrapper,
            offset: ["start start", "end start"],
        },
    );
}

// Smooth fade-in animations for content sections
const fadeElements = document.querySelectorAll("[data-fade]");

fadeElements.forEach((el, index) => {
    inView(
        el,
        () => {
            animate(
                el,
                {
                    opacity: [0, 1],
                    y: [30, 0],
                },
                {
                    duration: 0.8,
                    delay: index * 0.1,
                    easing: [0.4, 0, 0.2, 1],
                },
            );
        },
        {
            amount: 0.3,
        },
    );
});

// Add magnetic effect to CTA buttons
const magneticBtns = document.querySelectorAll(".magnetic-btn");
if (magneticBtns.length > 0) {
    magneticBtns.forEach((btn) => {
        btn.addEventListener("mouseenter", (e) => {
            animate(
                btn,
                {
                    scale: 1.05,
                },
                {
                    duration: 0.3,
                    easing: [0.4, 0, 0.2, 1],
                },
            );
        });

        btn.addEventListener("mouseleave", (e) => {
            animate(
                btn,
                {
                    scale: 1,
                },
                {
                    duration: 0.3,
                    easing: [0.4, 0, 0.2, 1],
                },
            );
        });
    });
}

// Scroll to Top Button
const scrollToTopBtn = document.getElementById("scrollToTop");

window.addEventListener("scroll", () => {
    if (window.scrollY > 500) {
        scrollToTopBtn.classList.add("visible");
    } else {
        scrollToTopBtn.classList.remove("visible");
    }
});

scrollToTopBtn.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
});

// --- Consolidated Chatbot Logic ---

// --- Unified Chatbot Logic ---
const chatbotButton = document.getElementById("chatbotButton");
const chatbotWindow = document.getElementById("chatbotWindow");
const chatbotMessages = document.getElementById("chatbotMessages");
const chatbotInput = document.getElementById("chatbotInput");
const chatbotSend = document.getElementById("chatbotSend");

// Get data from Blade metadata
const sessionId = document.body.dataset.sessionId;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// 1. Toggle Window (Restores the button functionality)
if (chatbotButton && chatbotWindow) {
    chatbotButton.addEventListener("click", () => {
        chatbotWindow.classList.toggle("active");
        if (chatbotWindow.classList.contains("active")) {
            chatbotInput.focus();
        }
    });
}

// 2. Function to load history from Database on page refresh
async function loadChatHistory() {
    if (!sessionId) return;
    try {
        const res = await fetch(`/chat/history/${sessionId}`);
        const history = await res.json();

        if (history.length > 0) {
            chatbotMessages.innerHTML = ""; // Clear default welcome if history exists
            history.forEach((msg) => appendMessageToUI(msg.role, msg.content));
        }
    } catch (e) {
        console.error("History load failed", e);
    }
}

// 3. Send Message logic
async function sendMessage() {
    const message = chatbotInput.value.trim();
    const sessionId = document.body.getAttribute("data-session-id");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    if (!message || !sessionId) return;

    // UI: Add user message immediately
    appendMessageToUI("user", message);
    chatbotInput.value = "";

    try {
        const response = await fetch("/chat/send", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                message: message,
                session_id: sessionId,
            }),
        });

        // Grab the JSON payload from Laravel regardless of status code
        const data = await response.json();

        // If the server crashed (500 error), throw the exact Laravel error
        if (!response.ok) {
            throw new Error(
                data.reply || data.message || "Unknown server error",
            );
        }

        appendMessageToUI("assistant", data.reply);
    } catch (error) {
        console.error("Chat Error:", error);
        // This will now print the exact backend error in your chat window
        appendMessageToUI("assistant", "Error: " + error.message);
    }
}

// Helper to add messages to the window
function appendMessageToUI(role, text) {
    const msgDiv = document.createElement("div");
    msgDiv.className = `chatbot-message ${role === "user" ? "user" : "bot"}`;
    msgDiv.textContent = text;
    chatbotMessages.appendChild(msgDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}

// 1. Listen for the Send Button Click
if (chatbotSend) {
    chatbotSend.addEventListener("click", sendMessage);
}

// 2. Listen for the "Enter" key inside the input box
if (chatbotInput) {
    chatbotInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
}

// 3. Initialize history on page load
loadChatHistory();
