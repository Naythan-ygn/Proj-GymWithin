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
const heroWrapper = document.querySelector(".hero-wrapper");

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

// Chatbot Functionality
const chatbotButton = document.getElementById("chatbotButton");
const chatbotWindow = document.getElementById("chatbotWindow");
const closeChatbot = document.getElementById("closeChatbot");
const chatbotInput = document.getElementById("chatbotInput");
const chatbotSend = document.getElementById("chatbotSend");
const chatbotMessages = document.getElementById("chatbotMessages");

// Toggle chatbot window
chatbotButton.addEventListener("click", () => {
    chatbotWindow.classList.toggle("active");
    if (chatbotWindow.classList.contains("active")) {
        chatbotInput.focus();
    }
});

closeChatbot.addEventListener("click", () => {
    chatbotWindow.classList.remove("active");
});

// Send message function
function sendMessage() {
    const message = chatbotInput.value.trim();
    if (!message) return;

    // Add user message
    const userMsg = document.createElement("div");
    userMsg.className = "chatbot-message user";
    userMsg.textContent = message;
    chatbotMessages.appendChild(userMsg);

    // Clear input
    chatbotInput.value = "";

    // Scroll to bottom
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

    // Simulate bot response
    setTimeout(() => {
        const botMsg = document.createElement("div");
        botMsg.className = "chatbot-message bot";

        // Simple response logic
        const lowerMessage = message.toLowerCase();
        if (lowerMessage.includes("price") || lowerMessage.includes("cost")) {
            botMsg.textContent =
                "Our equipment ranges from $1,299 to $2,499. We also offer flexible financing options starting at $54/month. Would you like to know more about a specific product?";
        } else if (
            lowerMessage.includes("shipping") ||
            lowerMessage.includes("delivery")
        ) {
            botMsg.textContent =
                "We offer free white-glove delivery and installation on all equipment. Delivery typically takes 5-7 business days. Where are you located?";
        } else if (lowerMessage.includes("warranty")) {
            botMsg.textContent =
                "All GymWithin equipment comes with a lifetime warranty on frames and a 5-year warranty on parts. We stand behind our quality!";
        } else if (lowerMessage.includes("treadmill")) {
            botMsg.textContent =
                "Our Pro Treadmill X1 features advanced cushioning, smart tracking, and a powerful motor. It's perfect for serious runners. Would you like to schedule a demo?";
        } else {
            botMsg.textContent =
                "Thanks for reaching out! Our team can help you with that. You can also call us at 1-800-GYM-WITHIN or email support@gymwithin.com. Is there anything specific I can help with?";
        }

        chatbotMessages.appendChild(botMsg);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }, 800);
}

// Send on button click
chatbotSend.addEventListener("click", sendMessage);

// Send on Enter key
chatbotInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
        sendMessage();
    }
});
