<div id="chat-widget" class="fixed bottom-4 right-4" style="z-index: 9999;">
    <!-- Minimized Chat Logo -->
    <div id="chat-logo" class="bg-blue-600 rounded-full p-2 cursor-pointer shadow-lg hover:bg-blue-700 transition-all duration-300 ml-auto w-12 h-12" >
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
    </div>

    <!-- Chat Window -->
    <div id="chat-window" class="bg-white rounded-lg shadow-xl hidden w-80">
        <!-- Rest of the chat window code -->
        <!-- Chat Header -->
        <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <h3 class="font-semibold">Rental Assistant</h3>
            </div>
            <button id="minimize-chat" class="text-white hover:text-gray-200 transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Chat Messages -->
        <div id="chat-messages" class="h-80 overflow-y-auto p-4 space-y-4">
            <div class="flex items-start">
                <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-[80%]">
                    <p class="text-sm">Hello! How can I help you with car rentals today?</p>
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
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
