<?php
// Bot token
$BOT_TOKEN = "Your_Bot_Token_Here";

// Mapping of chat types and effect IDs
$types = [
    1 => ['name' => 'User', 'effect_id' => '5107584321108051014'], // 👍 Thumbs Up
    2 => ['name' => 'Private Channel', 'effect_id' => '5046589136895476101'], // 💩 Poop
    3 => ['name' => 'Private Group', 'effect_id' => '5104858069142078462'], // 👎 Thumbs Down
    4 => ['name' => 'Public Channel', 'effect_id' => '5104841245755180586'], // 🔥 Fire
    5 => ['name' => 'Public Group', 'effect_id' => '5046509860389126442'], // 🎉 Confetti
    6 => ['name' => 'Bot', 'effect_id' => '5046509860389126442'], // 🎉 Confetti (changed from ❤️ Heart)
    7 => ['name' => 'Premium User', 'effect_id' => '5046509860389126442'] // 🎉 Confetti
];

// Message effect ID for the /start command
$START_EFFECT_ID = "5104841245755180586"; // 🔥 Fire

// Log file path
$LOG_FILE = __DIR__ . '/error.log';

// Function to log errors to error.log
function logError($message) {
    global $LOG_FILE;
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents($LOG_FILE, $log_message, FILE_APPEND | LOCK_EX);
}

// Always Get All Updates From Telegram API
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Exit If No Update Received From Telegram Bots API
if (!$update || !isset($update['message'])) {
    logError("No valid update or message received: " . json_encode($update));
    exit;
}

$message = $update['message'];
$chat_id = $message['chat']['id'];
$text = $message['text'] ?? '';

// Start Message Handling
if ($text === '/start') {
    $reply_text = "👋 <b>Welcome to Chat ID Finder Bot!</b> 🆔\n\n" .
                  "✅ <b>Fetch Any Chat ID Instantly!</b>\n\n" .
                  "🔧 <b>How to Use?</b>\n" .
                  "1️⃣ Click the buttons below to share a chat or user.\n" .
                  "2️⃣ Receive the unique ID instantly.\n\n" .
                  "💎 <b>Features:</b>\n" .
                  "✅ Supports users, bots, private/public groups & channels\n" .
                  "⚡ Fast and reliable\n\n" .
                  "<blockquote>🛠 Made with ❤️ By @TheSmartDev</blockquote>";

    // Define All The Keyboard Buttons With Custom Formation
    $keyboard = [
        'keyboard' => [
            // Row 1: User button
            [
                ['text' => '👤 User', 'request_user' => ['request_id' => 1, 'user_is_bot' => false]]
            ],
            // Row 2: Public Group and Private Group
            [
                ['text' => '🌐 Public Group', 'request_chat' => [
                    'request_id' => 5,
                    'chat_is_channel' => false,
                    'chat_has_username' => true
                ]],
                ['text' => '🔒 Private Group', 'request_chat' => [
                    'request_id' => 3,
                    'chat_is_channel' => false,
                    'chat_has_username' => false
                ]]
            ],
            // Row 3: Public Channel and Private Channel
            [
                ['text' => '🌐 Public Channel', 'request_chat' => [
                    'request_id' => 4,
                    'chat_is_channel' => true,
                    'chat_has_username' => true
                ]],
                ['text' => '🔒 Private Channel', 'request_chat' => [
                    'request_id' => 2,
                    'chat_is_channel' => true,
                    'chat_has_username' => false
                ]]
            ],
            // Row 4: Bots and Premium User
            [
                ['text' => '🤖 Bots', 'request_user' => ['request_id' => 6, 'user_is_bot' => true]],
                ['text' => 'Premium 🌟', 'request_user' => ['request_id' => 7, 'user_is_premium' => true]]
            ]
        ],
        'resize_keyboard' => true,  // Adjusts keyboard size for better fit
        'one_time_keyboard' => false  // Keyboard persists after use
    ];

    // Send the welcome message with the keyboard and fire effect
    sendHTMLMessage($BOT_TOKEN, $chat_id, $reply_text, $keyboard, true, $START_EFFECT_ID);
}

// Handle shared user (User, Bots, or Premium User)
if (isset($message['user_shared'])) {
    $request_id = $message['user_shared']['request_id'] ?? null;
    if (!$request_id || !isset($types[$request_id])) {
        logError("Invalid or missing request_id for user_shared: " . json_encode($message['user_shared']));
        $response = "⚠️ <b>Error:</b> Invalid user type shared.";
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response);
        exit;
    }
    $type = $types[$request_id]['name'];
    $effect_id = $types[$request_id]['effect_id'];
    $user_id = $message['user_shared']['user_id'] ?? 'Unknown';
    if ($user_id === 'Unknown') {
        logError("Missing user_id in user_shared for request_id $request_id: " . json_encode($message['user_shared']));
        $response = "⚠️ <b>Error:</b> Unable to retrieve $type ID.";
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, $effect_id);
        exit;
    }
    $response = "👤 <b>Shared $type Info</b>\n🆔 ID: <code>$user_id</code>";
    // Try sending with effect, fallback without effect if it fails
    if (!sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, $effect_id)) {
        logError("Retrying without message_effect_id for $type (chat_id: $chat_id, user_id: $user_id)");
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, null);
    }
}

// Handle shared chat (Private/Public Channel/Group)
if (isset($message['chat_shared'])) {
    $request_id = $message['chat_shared']['request_id'] ?? null;
    if (!$request_id || !isset($types[$request_id])) {
        logError("Invalid or missing request_id for chat_shared: " . json_encode($message['chat_shared']));
        $response = "⚠️ <b>Error:</b> Invalid chat type shared.";
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response);
        exit;
    }
    $type = $types[$request_id]['name'];
    $effect_id = $types[$request_id]['effect_id'];
    $shared_id = $message['chat_shared']['chat_id'] ?? 'Unknown';
    if ($shared_id === 'Unknown') {
        logError("Missing chat_id in chat_shared for request_id $request_id: " . json_encode($message['chat_shared']));
        $response = "⚠️ <b>Error:</b> Unable to retrieve $type ID.";
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, $effect_id);
        exit;
    }
    $response = "💬 <b>Shared $type Info</b>\n🆔 ID: <code>$shared_id</code>";
    // Try sending with effect, fallback without effect if it fails
    if (!sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, $effect_id)) {
        logError("Retrying without message_effect_id for $type (chat_id: $chat_id, shared_id: $shared_id)");
        sendHTMLMessage($BOT_TOKEN, $chat_id, $response, null, false, null);
    }
}

// Function to send messages with HTML formatting and optional message effect
function sendHTMLMessage($token, $chat_id, $text, $keyboard = null, $disable_link_preview = false, $message_effect_id = null) {
    global $LOG_FILE;
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $payload = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    if ($keyboard) {
        $payload['reply_markup'] = json_encode($keyboard);
    }
    if ($disable_link_preview) {
        $payload['disable_web_page_preview'] = true;
    }
    if ($message_effect_id) {
        $payload['message_effect_id'] = $message_effect_id;
    }
    $response = file_get_contents($url . '?' . http_build_query($payload));
    if ($response === false) {
        logError("Failed to send message to chat_id $chat_id: " . json_encode($payload));
        return false;
    }
    $result = json_decode($response, true);
    if (!$result['ok']) {
        logError("Telegram API error for chat_id $chat_id: " . json_encode($result));
        return false;
    }
    return true;
}
