document.addEventListener('DOMContentLoaded', function() {
    const chatWidget = document.getElementById('chat-widget');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-message');
    const toggleChat = document.getElementById('toggle-chat');
    const chatLogo = document.getElementById('chat-logo');
    const chatWindow = document.getElementById('chat-window');
    const minimizeChat = document.getElementById('minimize-chat');

    function addThinkingIndicator() {
        const thinkingDiv = document.createElement('div');
        thinkingDiv.className = 'flex items-start thinking-indicator';
        thinkingDiv.innerHTML = `
            <div class="bg-gray-100 rounded-lg px-4 py-2">
                <div class="flex space-x-2">
                    <div class="typing-dot w-1 h-1"></div>
                    <div class="typing-dot w-1 h-1"></div>
                    <div class="typing-dot w-1 h-1"></div>
                </div>
            </div>
        `;
        chatMessages.appendChild(thinkingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return thinkingDiv;
    }

    let lastMessageWasBot = false;
    function addMessage(message, isUser = false) {
        // Detect car list (lines with '🚗')
        const isCarList = !isUser && /🚗/.test(message);
        // Detect if this is a new bot group
        const isNewBotGroup = !isUser && !lastMessageWasBot;
        lastMessageWasBot = !isUser;
        if (isUser) lastMessageWasBot = false;

        if (isUser) {
            // User message: always new group
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-row user fade-in';
            const avatar = document.createElement('img');
            avatar.className = 'chat-avatar';
            avatar.src = 'assets/images/profiles/profile_12_1747133788.jpg';
            avatar.alt = 'You';
            const bubble = document.createElement('div');
            bubble.className = 'user-bubble';
            bubble.innerHTML = `<p class="text-sm">${message}</p>`;
            messageDiv.appendChild(bubble);
            messageDiv.appendChild(avatar);
            chatMessages.appendChild(messageDiv);
        } else {
            // Bot message
            let groupDiv = chatMessages.lastElementChild;
            if (!groupDiv || !groupDiv.classList.contains('bot-group')) {
                // Start new bot group
                groupDiv = document.createElement('div');
                groupDiv.className = 'bot-group fade-in';
                // Bot avatar
                const avatar = document.createElement('img');
                avatar.className = 'chat-avatar';
                avatar.src = 'assets/images/bot-avatar.png';
                avatar.alt = 'Bot';
                groupDiv.appendChild(avatar);
                // Bot label
                const label = document.createElement('div');
                label.className = 'bot-label';
                label.textContent = 'Bot';
                groupDiv.appendChild(label);
                chatMessages.appendChild(groupDiv);
            }
            // Add message bubble or car list
            if (isCarList) {
                const listDiv = document.createElement('div');
                listDiv.className = 'bot-list';
                // Split by line, add car emoji and bold car name
                listDiv.innerHTML = message.split('\n').map(line => {
                    if (/🚗/.test(line)) {
                        return `<div>${line.replace(/(🚗\s*)([\w\s\-]+)( - [^$]+\$[\d,.]+\/day)?/, '<span style=\'font-weight:bold\'>$1$2</span>$3')}</div>`;
                    }
                    return `<div>${line}</div>`;
                }).join('');
                groupDiv.appendChild(listDiv);
            } else {
                const bubble = document.createElement('div');
                bubble.className = 'bot-bubble';
                // Replace \n with <br> for FAQ and multi-line answers
                bubble.innerHTML = `<p class="text-sm">${message.replace(/\n/g, '<br>')}</p>`;
                groupDiv.appendChild(bubble);
            }
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // FAQ Integration
    function showFAQs() {
        fetch('api/faqs.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.faqs.length > 0) {
                    // Insert FAQ section after the introduction
                    const chatMessages = document.getElementById('chat-messages');
                    let faqSection = document.getElementById('faq-section');
                    if (faqSection) faqSection.remove();
                    faqSection = document.createElement('div');
                    faqSection.id = 'faq-section';
                    faqSection.className = 'mb-4';
                    faqSection.innerHTML = `
                        <div class="bot-label mb-2">Frequently Asked Questions</div>
                        <div class="flex flex-col gap-2" id="faq-list">
                            ${data.faqs.map(faq => `
                                <div class='faq-item' data-id='${faq.id}'>
                                    <button class='faq-btn w-full text-left' data-id='${faq.id}'>${faq.question}</button>
                                    <div class='faq-answer hidden mt-2 p-3 bg-blue-50 rounded text-blue-900 text-sm'></div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    // Insert after the static intro (first child)
                    chatMessages.insertBefore(faqSection, chatMessages.children[1] || null);

                    // Add click listeners
                    faqSection.querySelectorAll('.faq-btn').forEach(btn => {
                        btn.onclick = function() {
                            const id = this.getAttribute('data-id');
                            // Hide all other answers
                            faqSection.querySelectorAll('.faq-answer').forEach(ans => ans.classList.add('hidden'));
                            fetch('api/faqs.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id })
                            })
                            .then(res => res.json())
                            .then(ans => {
                                const answerDiv = this.parentElement.querySelector('.faq-answer');
                                if (ans.status === 'success') {
                                    answerDiv.innerHTML = ans.answer.replace(/\n/g, '<br>');
                                    answerDiv.classList.remove('hidden');
                                    answerDiv.classList.add('faq-answer-bubble');
                                } else {
                                    answerDiv.innerHTML = 'Sorry, I could not find the answer to that question.';
                                    answerDiv.classList.remove('hidden');
                                    answerDiv.classList.add('faq-answer-bubble');
                                }
                            });
                        };
                    });
                }
            });
    }

    // Show chat window when logo clicked
    chatLogo.addEventListener('click', () => {
        chatLogo.classList.add('hidden');
        chatWindow.classList.remove('hidden');
        showFAQs();
    });

    // Minimize chat window
    minimizeChat.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
        chatLogo.classList.remove('hidden');
    });

    function handleUserMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        addMessage(message, true);
        chatInput.value = '';
        
        const thinkingIndicator = addThinkingIndicator();

        fetch('api/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ query: message })
        })
        .then(response => response.json())
        .then(data => {
           // Take some time to show the thinking indicator
           setTimeout(() => {
            thinkingIndicator.remove();
            addMessage(data.response);
        }, 1500); // 2 second delay
            
        })
        .catch(error => {
              // Take some time to show the thinking indicator
           setTimeout(() => {
            thinkingIndicator.remove();
            addMessage("Let me connect you with our latest information! Please try your question again.");
        }, 1500); // 2 second delay
        });
    }

    sendButton.addEventListener('click', handleUserMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') handleUserMessage();
    });

    toggleChat.addEventListener('click', () => {
        chatMessages.parentElement.classList.toggle('hidden');
    });
});
