// ../assets/js/adminMessages.js

document.addEventListener('DOMContentLoaded', function() {
    const chatSessionListContainer = document.getElementById('chatSessionListContainer');
    const chatConversationSection = document.querySelector('.chatConversationSection');
    const chatBox = document.querySelector('.chat-box');
    const currentChatUserName = document.getElementById('currentChatUserName');
    const messageHistory = document.getElementById('messageHistory');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const sendButton = document.getElementById('sendButton');
    let currentSessionId = null;

    // Function to load the list of latest messages from all sessions
    function loadLatestMessages() {
        fetch('../process/getLatestMessage.php')
            .then(response => response.json())
            .then(data => {
                chatSessionListContainer.innerHTML = '';
                if (data.sessions && Array.isArray(data.sessions)) {
                    data.sessions.forEach(session => {
                        const dlElement = document.createElement('dl');
                        dlElement.classList.add('chatItem');
                        dlElement.setAttribute('data-session-id', session.chat_session_id);
                        dlElement.addEventListener('click', () => loadConversation(session.chat_session_id, session.other_user_name)); // Use other_user_name
    
                        const dtElement = document.createElement('dt');
                        const nameSpan = document.createElement('span');
                        nameSpan.classList.add('name');
                        nameSpan.textContent = session.other_user_name || 'Unknown User'; // Use other_user_name
    
                        const dateSpan = document.createElement('span');
                        dateSpan.classList.add('date');
                        const latestTimeParts = session.latest_timestamp ? session.latest_timestamp.split(' at ') : [];
                        dateSpan.textContent = latestTimeParts[0] || 'No messages yet';
    
                        dtElement.appendChild(nameSpan);
                        dtElement.appendChild(dateSpan);
    
                        const ddElement = document.createElement('dd');
                        const infoSpan = document.createElement('span');
                        infoSpan.classList.add('info');
                        infoSpan.textContent = session.latest_message ? session.latest_message.substring(0, 50) + '...' : 'No messages yet';
                        ddElement.appendChild(infoSpan);
    
                        dlElement.appendChild(dtElement);
                        dlElement.appendChild(ddElement);
    
                        chatSessionListContainer.appendChild(dlElement);
                    });
    
                    if (data.sessions.length > 0) {
                        const firstSession = data.sessions[0];
                        loadConversation(firstSession.chat_session_id, firstSession.other_user_name); // Use other_user_name
                        const firstChatItem = chatSessionListContainer.querySelector(`dl[data-session-id="${firstSession.chat_session_id}"]`);
                        if (firstChatItem) {
                            firstChatItem.classList.add('selected');
                        }
                        currentSessionId = firstSession.chat_session_id;
                        chatBox.style.display = 'flex';
                    } else {
                        chatBox.style.display = 'none';
                        chatSessionListContainer.innerHTML = '<div class="status-none">No chats found.</div>';
                    }
                } else {
                    chatSessionListContainer.innerHTML = '<div class="status-none">Error loading chats.</div>';
                    chatBox.style.display = 'none';
                    console.error('Invalid data format:', data);
                }
            })
            .catch(error => {
                chatSessionListContainer.innerHTML = '<div class="status-none">Error loading chats.</div>';
                chatBox.style.display = 'none';
                console.error('Error fetching latest sessions:', error);
            });
    }

    // Function to load all messages for a specific session
    function loadConversation(sessionId, userName) {
        currentSessionId = sessionId;
        currentChatUserName.textContent = userName || 'User';
        messageHistory.innerHTML = '<div class="status-none">Loading messages...</div>';
        chatBox.style.display = 'flex';

        document.querySelectorAll('.chatItem.selected').forEach(item => item.classList.remove('selected'));
        const currentChatItem = document.querySelector(`dl[data-session-id="${sessionId}"]`);
        if (currentChatItem) {
            currentChatItem.classList.add('selected');
        }

        fetch('../process/getMessage.php?session_id=' + sessionId)
            .then(response => response.json())
            .then(data => {
                messageHistory.innerHTML = '';
                if (data.messages && Array.isArray(data.messages)) {
                    data.messages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

                    if (data.messages.length > 0) {
                        data.messages.forEach(message => {
                            if (message.chat_session_id == sessionId) {
                                const messageElement = document.createElement('div');
                                messageElement.classList.add('message');

                                if (message.sender && message.sender.startsWith('A-')) {
                                    messageElement.classList.add('admin');
                                } else if (message.sender && message.sender.startsWith('P-')) {
                                    messageElement.classList.add('user');
                                }

                                messageElement.innerHTML = `
                                    ${message.message}
                                    <div class="messageDate">${message.timestamp}</div>
                                `;
                                messageHistory.appendChild(messageElement);
                            }
                        });
                        messageHistory.scrollTop = messageHistory.scrollHeight;
                    } else {
                        messageHistory.innerHTML = '<div class="status-none">No messages in this chat.</div>';
                    }
                } else {
                    messageHistory.innerHTML = '<div class="status-none">Error loading messages.</div>';
                    console.error('Error fetching messages:', data);
                }
            })
            .catch(error => {
                messageHistory.innerHTML = '<div class="status-none">Error loading conversation.</div>';
                console.error('Error fetching messages:', error);
            });
    }

    // Send message on Enter (Shift+Enter allows newline)
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message || !currentSessionId) {
            alert('Please select a chat and enter a message.');
            return;
        }

        fetch('../process/adminSendMessage.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ session_id: currentSessionId, message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatInput.value = '';
                loadConversation(currentSessionId, currentChatUserName.textContent);
                loadLatestMessages();
            } else {
                console.error('Message failed:', data.error);
                alert('Failed to send message: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Error sending message.');
        });
    }

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        sendMessage();
    });

    loadLatestMessages();
});