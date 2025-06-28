<?php
include 'functions.php';

$message = '';
$disableEmail = false;
$showVerifiedMessage = false;
$enteredEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Send verification code
    if (isset($_POST['email']) && !isset($_POST['verification_code'])) {
        $enteredEmail = $_POST['email'];
        $code = generateVerificationCode();
        $_SESSION['codes'][$enteredEmail] = $code;
        sendVerificationEmail($enteredEmail, $code);
        $message = "Verification code sent to $enteredEmail!";
        $disableEmail = true;
    }

    // Step 2: Verify code
    if (isset($_POST['verification_code']) && isset($_POST['email'])) {
        $enteredEmail = $_POST['email'];
        $code = $_POST['verification_code'];
        $disableEmail = true;

        if (verifyCode($enteredEmail, $code)) {
            registerEmail($enteredEmail);
            $showVerifiedMessage = true;
            $message = "🎉 You are successfully verified for XKCD comics subscription!";
        } else {
            $message = "❌ Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Comic Subscription</title>
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
            background-color: #007BFF;
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

<h2>📩 Subscribe to XKCD Comics</h2>

<?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

<!-- 📧 Email Form -->
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required 
        value="<?php echo htmlspecialchars($enteredEmail); ?>"
        <?php echo $disableEmail ? 'readonly' : ''; ?>>
    <button id="submit-email" <?php echo $disableEmail ? 'disabled' : ''; ?>>Submit</button>
</form>

<!-- 🔢 Code Verification Form -->
<?php if ($disableEmail && !$showVerifiedMessage): ?>
    <form method="POST">
        <label>Verification Code:</label>
        <input type="text" name="verification_code" maxlength="6" required>
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($enteredEmail); ?>">
        <button id="submit-verification">Verify</button>
    </form>
<?php endif; ?>

</body>
</html>
