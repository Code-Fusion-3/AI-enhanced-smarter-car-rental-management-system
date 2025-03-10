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




    // Show chat window when logo clicked
    chatLogo.addEventListener('click', () => {
        chatLogo.classList.add('hidden');
        chatWindow.classList.remove('hidden');
    });

    // Minimize chat window
    minimizeChat.addEventListener('click', () => {
        chatWindow.classList.add('hidden');
        chatLogo.classList.remove('hidden');
    });
    function addMessage(message, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start ${isUser ? 'justify-end' : ''}`;
        
        const bubble = document.createElement('div');
        bubble.className = `rounded-lg px-4 py-2 max-w-[80%] ${isUser ? 'bg-blue-600 text-white' : 'bg-gray-100'}`;
        bubble.innerHTML = `<p class="text-sm">${message}</p>`;
        
        messageDiv.appendChild(bubble);
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

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
