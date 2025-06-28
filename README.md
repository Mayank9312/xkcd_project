XKCD Comic Email Subscription Service

This project allows users to subscribe to receive daily XKCD comics in their inbox after email verification. It includes a full email verification system, PHPMailer integration, unsubscribe feature, and a CRON job for automated email delivery.
🌐 Live Demo (Hosted on InfinityFree)

🔗 https://mayal.kesug.com

💡 Features

📧 Email verification on sign-up

✅ Secure email validation with 6-digit code

🤖 Daily random XKCD comic via email (using CRON)

❌ Unsubscribe functionality via link

💌 PHPMailer-based SMTP email sending (Gmail)

📁 Project Structure

xkcd_project/

├── src/

│   ├── PHPMailer/                # PHPMailer Library

│   │   ├── PHPMailer.php

│   │   ├── SMTP.php

│   │   └── Exception.php

│   ├── functions.php             # Core logic (mail, register, unsubscribe)

│   ├── index.php                 # Email registration and verification UI

│   ├── unsubscribe.php           # Handle unsubscription

│   ├── cron.php                  # Send comic to all registered users

│   ├── setup_cron.sh            # CRON setup script

│   └── registered_emails.txt    # Stores subscriber emails

⚙️ Setup Instructions

1. ✅ Prerequisites

PHP 7.x or 8.x

Hosting (e.g., InfinityFree)

Gmail SMTP & App Password (enable 2FA)

PHPMailer Library (included)

2. 🛠 Configuration

Open functions.php and set:

$senderEmail = 'your_gmail@gmail.com';

$senderPassword = 'your_app_password';

3. ☁️ Upload to InfinityFree

Zip all files inside /src folder

Upload to htdocs/ via File Manager

Make sure PHPMailer folder is preserved

4. ⏰ Setup CRON Job

Run this URL once daily:

https://yourdomain/cron.php

📬 Email Flow

User enters email → gets 6-digit code

On verification → email is saved

Daily comic is sent to all registered users

User can click unsubscribe in mail to stop emails

🧪 Test Emails

✅ Gmail: Works perfectly

⚠️ College or institutional emails (e.g., @cuchd.in) may block mails

📷 Screenshot



👨‍💻 Author

Mayank BhardwajGitHub: Mayank9312

📜 License

This project is open-source and free to use.

🙏 Credits

XKCD Comics API

PHPMailer

InfinityFree Hosting

