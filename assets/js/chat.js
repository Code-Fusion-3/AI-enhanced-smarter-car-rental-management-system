document.addEventListener('DOMContentLoaded', function() {
    const chatWidget = document.getElementById('chat-widget');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-message');
    const toggleChat = document.getElementById('toggle-chat');

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

        fetch('api/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ query: message })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            addMessage(data.response);
        })
        .catch(error => {
            addMessage("Let me connect you with our latest information! Please try your question again.");
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
