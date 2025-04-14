# Chat ID Echo Bot (PHP)

![Chat ID Echo Bot](https://img.shields.io/badge/Telegram-Bot-blue?logo=telegram)  
A lightweight Telegram bot written in PHP to fetch chat IDs for users, groups, channels, and bots with ease.

## 📖 Overview

The **Chat ID Echo Bot (PHP)** is a Telegram bot designed to retrieve the unique IDs of Telegram entities (users, groups, channels, and bots) using a simple keyboard interface. Built with PHP and leveraging the Telegram Bot API, this bot is perfect for developers needing chat IDs for API interactions or users managing Telegram groups and channels.

This project is maintained by [abirxdhack](https://github.com/abirxdhack) and hosted at [Chat-ID-Echo-Bot](https://github.com/abirxdhack/Chat-ID-Echo-Bot).

## ✨ Features

- **Fetch Chat IDs Instantly**: Get IDs for users, bots, private/public groups, and private/public channels.
- **Interactive Keyboard**: User-friendly buttons to share Telegram entities.
- **Lightweight and Fast**: Minimal PHP script with no external dependencies.
- **Customizable**: Easily modify the keyboard layout and messages to suit your needs.
- **Open Source**: Contribute or adapt the bot for your own projects!

## 📋 Prerequisites

Before setting up the bot, ensure you have the following:

- **PHP 7.0+**: The bot is written in PHP.
- **Web Server**: A server with PHP support (e.g., Apache, Nginx) to host the bot script.
- **HTTPS URL**: Telegram webhooks require a secure HTTPS URL.
- **Bot Token**: Create a bot via [BotFather](https://t.me/BotFather) on Telegram to get a `BOT_TOKEN`.

## 🛠 Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/abirxdhack/Chat-ID-Echo-Bot.git
   cd Chat-ID-Echo-Bot
   ```

2. **Set Up Your Bot Token**:
   Open `index.php` (or rename the script as needed) and replace the placeholder token with your bot token:
   ```php
   $BOT_TOKEN = "YOUR_BOT_TOKEN_HERE";  // Replace with your Bot Token
   ```

3. **Host the Script**:
   - Upload the PHP script (e.g., `index.php`) to your web server.
   - Ensure the script is accessible via an HTTPS URL (e.g., `https://yourdomain.com/index.php`).

4. **Set Up the Webhook**:
   Use the Telegram Bot API to set the webhook for your bot:
   - Open your browser or use a tool like `curl` to make the following request:
     ```
     https://api.telegram.org/botYOUR_BOT_TOKEN_HERE/setWebhook?url=https://yourdomain.com/index.php
     ```
   - Replace `YOUR_BOT_TOKEN_HERE` with your bot token and `https://yourdomain.com/index.php` with the URL of your script.
   - If successful, you’ll get a response like: `{"ok":true,"result":true,"description":"Webhook was set"}`.

## 🚀 Usage

1. **Start the Bot**:
   - Open Telegram and start a chat with your bot (find it using the username you set via BotFather).
   - Send the `/start` command to see the welcome message and keyboard.

2. **Fetch Chat IDs**:
   - Click a button (e.g., "👤 User", "🔒 Private Group", or "🌐 Public Channel") and share the requested entity.
   - The bot will reply with the chat ID, e.g.:
     ```
     👤 Shared User Info
     🆔 ID: 5857628904
     ```

## 📜 Code Structure

- **`index.php`**: The main PHP script that handles Telegram updates, processes user input, and sends responses with chat IDs.
- **Keyboard Layout**: Customizable keyboard with buttons for sharing users, groups, channels, and bots.

## 🤝 Contributing

Contributions are welcome! If you have ideas for new features or improvements, feel free to:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Make your changes and commit them (`git commit -m 'Add your feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 📧 Contact

For questions, suggestions, or support, reach out to [abirxdhack](https://github.com/abirxdhack) via GitHub Issues or Telegram (@TheSmartDev).
