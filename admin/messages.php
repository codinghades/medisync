<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="../assets/css/adminMessages.css">
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/adminSidebar.php'; ?>
        </div>

        <div class="mainContent">
            <div class="chatListSection">
                <h2>Chats</h2>
                <div id="chatSessionListContainer">
                    <div class="status-none">Loading chats...</div>
                </div>
            </div>
            <div class="chatConversationSection">
                <div class="chat-box">
                    <div class="chat-header">
                        <span>Chat with <span id="currentChatUserName"></span></span>
                    </div>
                    <div class="message-history" id="messageHistory">
                        <div class="status-none">Select a chat to view messages.</div>
                    </div>
                    <form id="chatForm" onsubmit="sendMessage(event)">
                        <textarea id="chatInput" placeholder="Type your message..." rows="3"></textarea>
                        <button type="submit" id="sendButton">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/adminMessages.js"></script>
</body>
</html>