<?php
// Replace With Your Own BOTOTKEN By Which You Will Set Webhook
$BOT_TOKEN = "YOUR_BOT_TOKEN_HERE";

// Mapping Which Info Bot Will Fetch And Create Keyboard Buttons With Request With core.telegram.org API
$types = [
    1 => 'User',
    2 => 'Private Channel',
    3 => 'Private Group',
    4 => 'Public Channel',
    5 => 'Public Group',
    6 => 'Bots',
    7 => 'Premium User' // Added for Premium button
];

// Always Get All Updates From Telegram API
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Exit If No Update Rechieved From Telegram Bots API
if (!$update || !isset($update['message'])) {
    exit;
}

$message = $update['message'];
$chat_id = $message['chat']['id'];
$text = $message['text'] ?? '';

// Start Message String Edit As You Need
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

    // Send the welcome message with the keyboard
    sendHTMLMessage($BOT_TOKEN, $chat_id, $reply_text, $keyboard, true);
}

// Handle shared user (User, Bots, or Premium User)
if (isset($message['user_shared'])) {
    $request_id = $message['user_shared']['request_id'];
    $type = $types[$request_id];
    $user_id = $message['user_shared']['user_id'];
    $response = "👤 <b>Shared $type Info</b>\n🆔 ID: <code>$user_id</code>";
    sendHTMLMessage($BOT_TOKEN, $chat_id, $response);
}

// Handle shared chat (Private/Public Channel/Group)
if (isset($message['chat_shared'])) {
    $request_id = $message['chat_shared']['request_id'];
    $type = $types[$request_id];
    $shared_id = $message['chat_shared']['chat_id'];
    $response = "💬 <b>Shared $type Info</b>\n🆔 ID: <code>$shared_id</code>";
    sendHTMLMessage($BOT_TOKEN, $chat_id, $response);
}

// Function to send messages with HTML formatting
function sendHTMLMessage($token, $chat_id, $text, $keyboard = null, $disable_link_preview = false) {
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
    file_get_contents($url . '?' . http_build_query($payload));
}
