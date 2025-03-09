document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');
    
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const query = this.value;
            addMessage('user', query);
            
            fetch('api/chat.php', {
                method: 'POST',
                body: JSON.stringify({ query: query }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                addMessage('bot', data.response);
            });
            
            this.value = '';
        }
    });
    
    function addMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
