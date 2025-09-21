/**
 * Handles all front-end interactivity for the UltraPress Chatbot.
 *
 * This script manages the chat window's state (opening/closing), message submission via AJAX,
 * rendering new messages, session storage for conversation history, and the auto-resizing input area.
 *
 * @package UltraPress
 * @version 5.0.0
 */

'use strict';

jQuery(function ($) {

    // --- 1. Cache DOM Elements ---
    // Caching elements that are frequently accessed improves performance.
    const chatbotContainer = $('.ultrapress-container');
    const chatbotToggle = $('.ultrapress-toggle');
    const chatbotMessages = $('.ultrapress-messages');
    const chatbotForm = $('.ultrapress-input-form');
    const chatbotInput = $('.ultrapress-input');
    const chatbotMinimizeBtn = $('.ultrapress-minimize');

    // --- 2. State Management ---
    let conversationHistory = [];

    // --- 3. Core Functions ---

    /**
     * Opens the chat window, focuses the input, and hides the toggle button.
     */
    function openChatWindow() {
        chatbotContainer.removeClass('ultrapress-hidden');
        chatbotInput.focus();
        chatbotToggle.fadeOut(200);
    }

    /**
     * Closes the chat window and shows the toggle button.
     */
    function closeChatWindow() {
        chatbotContainer.addClass('ultrapress-hidden');
        chatbotToggle.fadeIn(200);
    }

    /**
     * Loads conversation from sessionStorage to maintain state across page reloads.
     * If no history exists, it initializes the chat with the welcome message.
     */
    function loadConversation() {
        try {
            const savedHistory = sessionStorage.getItem('ultrapressChatHistory');
            if (savedHistory) {
                // If history exists, clear the default message and rebuild the chat.
                conversationHistory = JSON.parse(savedHistory);
                chatbotMessages.empty();
                conversationHistory.forEach(msg => appendMessage(msg.content, msg.role === 'user' ? 'user' : 'bot'));
            } else {
                // On the very first load, the welcome message is already in the HTML.
                // We only add it to our JavaScript memory array.
                conversationHistory.push({ role: 'assistant', content: ultrapressData.welcomeMessage });
            }
        } catch (e) {
            console.error("UltraPress Error: Could not load conversation history.", e);
            // Fallback in case of a corrupted sessionStorage
            chatbotMessages.html(`<div class="ultrapress-message ultrapress-bot-message">${ultrapressData.welcomeMessage}</div>`);
            conversationHistory = [{ role: 'assistant', content: ultrapressData.welcomeMessage }];
        }
    }

    /**
     * Saves the current conversation to sessionStorage.
     */
    function saveConversation() {
        sessionStorage.setItem('ultrapressChatHistory', JSON.stringify(conversationHistory));
    }

    /**
     * Appends a new message to the chat window.
     * It parses Markdown for bot messages and sanitizes user messages.
     * @param {string} message - The message content.
     * @param {string} type - 'user', 'bot', or 'bot-error'.
     */
    function appendMessage(message, type) {
        let processedMessage;
        if (type.startsWith('bot')) {
            // For bot messages, use the marked.js library to convert Markdown to safe HTML.
            processedMessage = marked.parse(message, { sanitize: true });
        } else {
            // For user messages, escape the text to prevent HTML injection.
            const escaper = document.createElement('div');
            escaper.innerText = message;
            processedMessage = escaper.innerHTML;
        }
        const messageDiv = $(`<div class="ultrapress-message ultrapress-${type}-message">${processedMessage}</div>`);
        chatbotMessages.append(messageDiv);
        scrollToBottom();
    }

    /**
     * Shows the 'typing...' animation.
     */
    function showTypingIndicator() {
        const typingDiv = `<div class="ultrapress-message ultrapress-bot-message ultrapress-typing"><div class="ultrapress-dot"></div><div class="ultrapress-dot"></div><div class="ultrapress-dot"></div></div>`;
        chatbotMessages.append(typingDiv);
        scrollToBottom();
    }

    /**
     * Removes the 'typing...' animation.
     */
    function hideTypingIndicator() {
        $('.ultrapress-typing').remove();
    }

    /**
     * Smoothly scrolls the message window to the latest message.
     */
    function scrollToBottom() {
        chatbotMessages.animate({ scrollTop: chatbotMessages[0].scrollHeight }, 500);
    }

    // --- 4. Event Handlers ---

    // Open chat when the toggle button is clicked.
    chatbotToggle.on('click', function (e) {
        e.stopPropagation();
        openChatWindow();
    });

    // Minimize chat when the minimize button in the header is clicked.
    chatbotMinimizeBtn.on('click', function () {
        closeChatWindow();
    });

    // Minimize chat when the user clicks outside the chat window.
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.ultrapress-container, .ultrapress-toggle').length && !chatbotContainer.hasClass('ultrapress-hidden')) {
            closeChatWindow();
        }
    });

    // Handle the auto-resizing textarea as the user types.
    chatbotInput.on('input', function () {
        this.style.height = 'auto'; // Reset height to recalculate on each input
        this.style.height = (this.scrollHeight) + 'px'; // Set height to match content
    });

    // Handle form submission via AJAX.
    chatbotForm.on('submit', function (e) {
        // Prevent the default form submission which reloads the page.
        e.preventDefault();

        const message = chatbotInput.val().trim();
        if (!message) return;

        appendMessage(message, 'user');
        chatbotInput.val('');
        
        conversationHistory.push({ role: 'user', content: message });
        saveConversation();
        showTypingIndicator();

        // After submitting, reset the textarea height.
        setTimeout(() => { chatbotInput.css('height', 'auto'); }, 0);
        
        $.ajax({
            url: ultrapressData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ultrapress_send_message',
                nonce: ultrapressData.nonce,
                history: JSON.stringify(conversationHistory),
            },
            success: function (response) {
                hideTypingIndicator();
                if (response.success) {
                    appendMessage(response.data, 'bot');
                    conversationHistory.push({ role: 'assistant', content: response.data });
                    saveConversation();
                } else {
                    const errorMessage = response.data.message || ultrapressData.errorMessages.general;
                    appendMessage(errorMessage, 'bot-error');
                }
            },
            error: function () {
                hideTypingIndicator();
                appendMessage(ultrapressData.errorMessages.connection, 'bot-error');
            }
        });
    });

    // --- 5. Initialization ---
    // Load the conversation history when the script is first executed.
    loadConversation();
});