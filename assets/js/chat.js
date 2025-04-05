// Sample chat history (You can fetch this from your server)
const chatHistory = [
    { sender: 'admin', message: 'Hello! How can I assist you today?' },
    { sender: 'user', message: 'I need help with my appointment.' },
    { sender: 'admin', message: 'Sure, let me check that for you.' }
];

// Function to toggle the chat box visibility
function toggleChatBox() {
    const chatBox = document.querySelector('.chat-box');
    chatBox.style.display = chatBox.style.display === 'flex' ? 'none' : 'flex';
    displayChatHistory(); // Display the chat history when the box opens
}

// Function to display the chat history
function displayChatHistory() {
    const messageHistory = document.getElementById('messageHistory');
    messageHistory.innerHTML = ''; // Clear previous messages

    // Loop through the chat history and display each message
    chatHistory.forEach(chat => {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', chat.sender); // Add sender class (user/admin)
        messageDiv.textContent = chat.message;
        messageHistory.appendChild(messageDiv);
    });

    // Scroll to the bottom of the chat
    messageHistory.scrollTop = messageHistory.scrollHeight;
}

// Function to send a new message
function sendMessage(event) {
    event.preventDefault();  // Prevent the form from reloading the page

    const messageInput = document.getElementById('chatInput');
    const messageHistory = document.getElementById('messageHistory');
    
    const messageText = messageInput.value.trim();  // Get the trimmed message text

    if (messageText === '') return;  // Don't send empty messages

    // Create a new message element
    const messageElement = document.createElement('div');
    messageElement.classList.add('message', 'user');  // Add 'user' class for styling
    messageElement.textContent = messageText;

    // Append the new message to the chat history
    messageHistory.appendChild(messageElement);

    // Scroll to the bottom of the chat history
    messageHistory.scrollTop = messageHistory.scrollHeight;

    // Clear the input field
    messageInput.value = '';
}

// This will trigger the form submission when Enter is pressed
document.getElementById('chatInput').addEventListener('keydown', function (event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();  // Prevent new line on Enter key
        sendMessage(event);      // Call send message function
    }
});

