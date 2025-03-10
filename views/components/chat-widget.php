<div id="chat-widget" class="fixed bottom-4 right-4 w-80" style="z-index: 9999;">
    <div class="bg-white rounded-lg shadow-xl">
        <!-- Chat Header -->
        <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
            <h3 class="font-semibold">AI Assistant</h3>
            <button id="toggle-chat" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Chat Messages -->
        <div id="chat-messages" class="h-80 overflow-y-auto p-4 space-y-4">
            <div class="flex items-start">
                <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-[80%]">
                    <p class="text-sm">Hello! How can I help you today?</p>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="border-t p-4">
            <div class="flex space-x-2">
                <input type="text" id="chat-input" 
                       class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-blue-500" 
                       placeholder="Type your message...">
                <button id="send-message" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
