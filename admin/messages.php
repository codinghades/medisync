<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="../assets/css/adminMessages.css">
    <script src="../assets/js/adminMessages.js"></script>
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/adminSidebar.php'; ?>
        </div>

        <div class="mainContent">
            <div class="titleContainer">
                <div class="title">Patient Messages</div>
            </div>

            <div class="upcomingAppointments" id="messageList">
                <div class="header"><p>Chats</p></div>
                <div class="list" id="chatSessionList">
                    </div>
                <div class="spacer"></div>
            </div>

            <div class="upcomingAppointments" id="chatBoxPanel">
                <div class="header"><p>Conversation</p></div>
                <div class="list" id="chatConversation">
                    <div class="status-none" style="text-align: center; padding: 20px;">No message selected</div>
                </div>
                <div class="spacer"></div>
            </div>
        </div>
    </div>
</body>
</html>