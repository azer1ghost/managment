{{-- AI Assistant Chat Widget - Floating Button and Chat Window --}}
{{-- Permission check: Only visible for users with ID 1, 2, 15, 78, 123 --}}
@php
    $currentUserId = auth()->check() ? auth()->id() : null;
    $allowedUserIds = [1, 2, 15, 78, 123];
    $hasAccess = auth()->check() && in_array($currentUserId, $allowedUserIds);
@endphp

@if($hasAccess)
    {{-- Floating Chat Button (bottom-right) --}}
    <button 
        id="aiAssistantBtn" 
        class="ai-chat-btn"
        title="AI Assistant"
        aria-label="Open AI Assistant Chat"
    >
        <i class="fal fa-comments-alt"></i>
        <span class="ai-chat-badge" id="aiChatBadge" style="display: none;">1</span>
    </button>

    {{-- Chat Window (sağ aşağıda chat kimi) --}}
    <div id="aiChatWindow" class="ai-chat-window" style="display: none;">
        {{-- Chat Header --}}
        <div class="ai-chat-header">
            <div class="ai-chat-header-left">
                <div class="ai-chat-avatar">
                    <i class="fal fa-robot"></i>
                </div>
                <div class="ai-chat-header-info">
                    <div class="ai-chat-title">AI Assistant</div>
                    <div class="ai-chat-status" id="aiChatStatus">Online</div>
                </div>
            </div>
            <button id="aiChatMinimize" class="ai-chat-minimize" title="Kiçilt">
                <i class="fal fa-minus"></i>
            </button>
            <button id="aiChatClose" class="ai-chat-close" title="Bağla">
                <i class="fal fa-times"></i>
            </button>
        </div>

        {{-- Chat Messages Area (Scrollable) --}}
        <div class="ai-chat-messages" id="aiChatMessages">
            <div class="ai-chat-welcome">
                <div class="ai-chat-welcome-icon">
                    <i class="fal fa-robot"></i>
                </div>
                <div class="ai-chat-welcome-text">
                    <strong>Salam! Men AI Assistant-am.</strong>
                    <p>Mene sual vere bilersiniz. Meselen:</p>
                    <ul>
                        <li>Bu ay umumi is sayi necedir?</li>
                        <li>Son 30 gunde qazanc ne qederdir?</li>
                        <li>Imzalanmis qaimelerin sayi necedir?</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Chat Input Area --}}
        <div class="ai-chat-input-area">
            {{-- Loading Indicator --}}
            <div id="aiChatLoading" class="ai-chat-loading" style="display: none;">
                <div class="ai-chat-typing">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="ai-chat-typing-text">AI cavab verir...</span>
            </div>

            {{-- Error Message --}}
            <div id="aiChatError" class="ai-chat-error" style="display: none;"></div>

            {{-- Input Form --}}
            <div class="ai-chat-input-wrapper">
                <textarea 
                    id="aiChatInput" 
                    class="ai-chat-input" 
                    rows="1"
                    placeholder="Sualınızı yazın..."
                    maxlength="1000"
                ></textarea>
                <button 
                    id="aiChatSend" 
                    class="ai-chat-send"
                    type="button"
                    title="Göndər"
                >
                    <i class="fal fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        /* Floating Button */
        .ai-chat-btn {
            position: fixed !important;
            bottom: 30px !important;
            right: 30px !important;
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            cursor: pointer !important;
            z-index: 9999 !important;
            transition: all 0.3s ease;
            display: flex !important;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            padding: 0;
        }

        .ai-chat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .ai-chat-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Chat Window */
        .ai-chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 380px;
            height: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            z-index: 9998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Chat Header */
        .ai-chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }

        .ai-chat-header-left {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .ai-chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 20px;
        }

        .ai-chat-header-info {
            flex: 1;
        }

        .ai-chat-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .ai-chat-status {
            font-size: 12px;
            opacity: 0.9;
        }

        .ai-chat-minimize,
        .ai-chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px 10px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .ai-chat-minimize:hover,
        .ai-chat-close:hover {
            opacity: 1;
        }

        /* Chat Messages Area */
        .ai-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }

        .ai-chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .ai-chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .ai-chat-messages::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        /* Welcome Message */
        .ai-chat-welcome {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .ai-chat-welcome-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .ai-chat-welcome-text {
            font-size: 14px;
            line-height: 1.6;
        }

        .ai-chat-welcome-text ul {
            text-align: left;
            margin-top: 10px;
            padding-left: 20px;
        }

        .ai-chat-welcome-text li {
            margin: 5px 0;
            color: #888;
        }

        /* Message Bubbles */
        .ai-message {
            margin-bottom: 15px;
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ai-message.user {
            justify-content: flex-end;
        }

        .ai-message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .ai-message.user .ai-message-bubble {
            background: #667eea;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .ai-message.assistant .ai-message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 4px;
        }

        .ai-message-time {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }

        .ai-message.assistant .ai-message-time {
            text-align: left;
        }

        /* Chat Input Area */
        .ai-chat-input-area {
            border-top: 1px solid #e0e0e0;
            background: white;
            padding: 15px;
        }

        .ai-chat-loading {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ai-chat-typing {
            display: flex;
            gap: 4px;
        }

        .ai-chat-typing span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            animation: typing 1.4s infinite;
        }

        .ai-chat-typing span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .ai-chat-typing span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.7;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .ai-chat-typing-text {
            font-size: 12px;
            color: #666;
        }

        .ai-chat-error {
            padding: 10px;
            background: #fee;
            color: #c33;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .ai-chat-input-wrapper {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .ai-chat-input {
            flex: 1;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 10px 15px;
            resize: none;
            font-size: 14px;
            max-height: 100px;
            font-family: inherit;
        }

        .ai-chat-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .ai-chat-send {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .ai-chat-send:hover {
            background: #5568d3;
            transform: scale(1.05);
        }

        .ai-chat-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Minimized State */
        .ai-chat-window.minimized {
            height: 60px;
            overflow: hidden;
        }

        .ai-chat-window.minimized .ai-chat-messages,
        .ai-chat-window.minimized .ai-chat-input-area {
            display: none;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        (function() {
            // Wait for DOM and jQuery
            if (typeof jQuery === 'undefined') {
                console.error('jQuery not loaded');
                return;
            }

            jQuery(document).ready(function($) {
                var currentUserId = {!! json_encode($currentUserId ?? null) !!};
                console.log('AI Chat Widget Loaded. User ID:', currentUserId);

                const $btn = $('#aiAssistantBtn');
                const $window = $('#aiChatWindow');
                const $messages = $('#aiChatMessages');
                const $input = $('#aiChatInput');
                const $sendBtn = $('#aiChatSend');
                const $loading = $('#aiChatLoading');
                const $error = $('#aiChatError');
                const $closeBtn = $('#aiChatClose');
                const $minimizeBtn = $('#aiChatMinimize');
                const $badge = $('#aiChatBadge');

                let isOpen = false;
                let isMinimized = false;

                // CSRF Token Setup
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Open/Close Chat Window
                $btn.on('click', function() {
                    if (!isOpen) {
                        openChat();
                    } else if (!isMinimized) {
                        minimizeChat();
                    } else {
                        openChat();
                    }
                });

                $closeBtn.on('click', function() {
                    closeChat();
                });

                $minimizeBtn.on('click', function() {
                    minimizeChat();
                });

                function openChat() {
                    $window.show();
                    isOpen = true;
                    isMinimized = false;
                    $window.removeClass('minimized');
                    $badge.hide();
                    setTimeout(function() {
                        $input.focus();
                        scrollToBottom();
                    }, 100);
                }

                function minimizeChat() {
                    isMinimized = true;
                    $window.addClass('minimized');
                }

                function closeChat() {
                    $window.hide();
                    isOpen = false;
                    isMinimized = false;
                    $window.removeClass('minimized');
                }

                // Auto-resize textarea
                $input.on('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
                });

                // Send Message
                function sendMessage() {
                    const question = $input.val().trim();
                    
                    if (!question) {
                        return;
                    }

                    // Disable input
                    $input.prop('disabled', true);
                    $sendBtn.prop('disabled', true);
                    $error.hide();
                    $loading.show();

                    // Add user message to chat (prevent duplicates)
                    if ($messages.find('.ai-message.user:last').length === 0 || 
                        $messages.find('.ai-message.user:last .ai-message-bubble').text().trim() !== question) {
                        addMessage(question, 'user');
                    }

                    // Clear input
                    $input.val('');
                    $input.css('height', 'auto');

                    // Scroll to bottom
                    scrollToBottom();

                    console.log('Sending question:', question);

                    // Send AJAX request
                    var aiAskUrl = {!! json_encode(route('ai.ask')) !!};
                    $.ajax({
                        url: aiAskUrl,
                        type: 'POST',
                        data: {
                            question: question,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        timeout: 60000,
                        success: function(response) {
                            console.log('AI Response:', response);
                            
                            $loading.hide();
                            $input.prop('disabled', false);
                            $sendBtn.prop('disabled', false);

                            if (response && (response.success !== false || response.answer)) {
                                const answer = response.answer || response.message || 'Cavab alınmadı.';
                                addMessage(answer, 'assistant', response);
                            } else {
                                let errorMsg = 'Xəta baş verdi.';
                                if (response && response.message) {
                                    errorMsg = String(response.message);
                                } else if (response && response.error) {
                                    errorMsg = String(response.error);
                                }
                                showError(errorMsg);
                            }

                            scrollToBottom();
                            $input.focus();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', {
                                status: xhr.status,
                                error: error,
                                response: xhr.responseJSON
                            });

                            $loading.hide();
                            $input.prop('disabled', false);
                            $sendBtn.prop('disabled', false);

                            let errorMsg = 'Xəta baş verdi: ' + String(error);
                            
                            if (xhr.status === 403) {
                                errorMsg = 'Bu funksiyaya giriş imkanınız yoxdur.';
                            } else if (xhr.status === 422) {
                                const errors = xhr.responseJSON?.errors;
                                if (errors && errors.question && errors.question[0]) {
                                    errorMsg = String(errors.question[0]);
                                } else {
                                    errorMsg = 'Yanlış məlumat.';
                                }
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = String(xhr.responseJSON.message);
                            } else if (xhr.status === 0) {
                                errorMsg = 'Server ilə əlaqə qurula bilmədi.';
                            } else if (status === 'timeout') {
                                errorMsg = 'Sorğu vaxtı bitdi.';
                            }
                            
                            // Clean error message from any problematic characters
                            errorMsg = errorMsg.replace(/["'`]/g, '');

                            showError(errorMsg);
                            scrollToBottom();
                            $input.focus();
                        }
                    });
                }

                // Send button click
                $sendBtn.on('click', sendMessage);

                // Enter to send (Shift+Enter for new line)
                $input.on('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Add message to chat
                function addMessage(text, type, metadata) {
                    // Remove welcome message if exists
                    $messages.find('.ai-chat-welcome').remove();

                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const time = hours + ':' + minutes;

                    var messageBubble = $('<div>').addClass('ai-message ' + type);
                    var bubbleContent = $('<div>').addClass('ai-message-bubble');
                    bubbleContent.html(formatMessage(text));
                    var timeDiv = $('<div>').addClass('ai-message-time').text(time);
                    bubbleContent.append(timeDiv);
                    messageBubble.append(bubbleContent);

                    $messages.append(messageBubble);
                    scrollToBottom();
                }

                // Format message text
                function formatMessage(text) {
                    if (!text) return '';
                    
                    // Escape HTML
                    let formatted = $('<div>').text(text).html();
                    
                    // Convert line breaks
                    formatted = formatted.replace(/\n/g, '<br>');
                    
                    // Convert bullet points
                    formatted = formatted.replace(/^[\s]*[-•]\s*(.+)$/gm, '<li>$1</li>');
                    formatted = formatted.replace(/(<li>.*<\/li>)/s, '<ul style="margin: 5px 0; padding-left: 20px;">$1</ul>');
                    
                    // Convert **bold**
                    formatted = formatted.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
                    
                    return formatted;
                }

                // Show error
                function showError(message) {
                    $error.text(message).show();
                    setTimeout(function() {
                        $error.fadeOut();
                    }, 5000);
                }

                // Scroll to bottom
                function scrollToBottom() {
                    $messages.scrollTop($messages[0].scrollHeight);
                }

                // Close on escape key
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape' && isOpen && !isMinimized) {
                        minimizeChat();
                    }
                });
            });
        })();
    </script>
@endif
