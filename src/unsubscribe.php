<?php
include 'functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Send code to email
    if (isset($_POST['unsubscribe_email']) && !isset($_POST['verification_code'])) {
        $email = $_POST['unsubscribe_email'];
        $code = generateVerificationCode();
        $_SESSION['codes'][$email] = $code;

        sendUnsubscribeVerification($email, $code);
        $message = "Unsubscribe verification code sent to $email!";
    }

    // Step 2: Verify code to unsubscribe
    if (isset($_POST['verification_code']) && isset($_POST['unsubscribe_email'])) {
        $email = $_POST['unsubscribe_email'];
        $code = $_POST['verification_code'];

        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            $message = "You have been unsubscribed!";
        } else {
            $message = "Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe from XKCD</title>
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: auto;
            padding: 40px;
        }
        input, button {
            margin: 10px 0;
            padding: 8px;
            width: 100%;
        }
        button {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .message {
            margin: 15px 0;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<h2>❌ Unsubscribe from XKCD Comics</h2>

<?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

<!-- 📧 Email Input Form -->
<form method="POST">
    <label>Email to Unsubscribe:</label>
    <input type="email" name="unsubscribe_email" required
        value="<?php echo isset($_POST['unsubscribe_email']) ? htmlspecialchars($_POST['unsubscribe_email']) : ''; ?>">
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<!-- 🔢 Verification Code Form -->
<form method="POST">
    <label>Verification Code:</label>
    <input type="text" name="verification_code" maxlength="6" required>
    <input type="hidden" name="unsubscribe_email"
        value="<?php echo isset($_POST['unsubscribe_email']) ? htmlspecialchars($_POST['unsubscribe_email']) : ''; ?>">
    <button id="submit-verification">Verify</button>
</form>

</body>
</html>
