<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

// Gmail App Email
$senderEmail = "datahandling45@gmail.com";
$senderPassword = "bdrdeeejvwhznwnq";  // ✅ App Password without spaces

// ✅ Generate 6-digit verification code
function generateVerificationCode() {
    return rand(100000, 999999);
}

// ✅ Register email to file
function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

// ✅ Remove email from file
function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
}

// ✅ Send email using PHPMailer
function sendMail($to, $subject, $body) {
    global $senderEmail, $senderPassword;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $senderEmail;
        $mail->Password   = $senderPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom($senderEmail, 'XKCD Comics');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Email to $to failed. Error: {$mail->ErrorInfo}");
    }
}

// ✅ Send verification email
function sendVerificationEmail($email, $code) {
    $body = "<p>Your verification code is: <strong>$code</strong></p>";
    sendMail($email, "Your Verification Code", $body);
}

// ✅ Send unsubscribe email
function sendUnsubscribeVerification($email, $code) {
    $body = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
    sendMail($email, "Confirm Un-subscription", $body);
}

// ✅ Check if code matches
function verifyCode($email, $code) {
    return isset($_SESSION['codes'][$email]) && $_SESSION['codes'][$email] == $code;
}

// ✅ Fetch XKCD comic using cURL
function fetchAndFormatXKCDData() {
    $randomId = rand(1, 3000);
    $url = "https://xkcd.com/$randomId/info.0.json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return "<h2>XKCD Comic</h2><p>Could not fetch comic. Please try again later.</p>";
    }

    $data = json_decode($response, true);

    return "<h2>XKCD Comic</h2>
            <img src=\"{$data['img']}\" alt=\"XKCD Comic\">
            <p><a href='https://mayal.kesug.com/unsubscribe.php' id='unsubscribe-button'>Unsubscribe</a></p>";
}

// ✅ Send comic to all registered users
function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $comicHtml = fetchAndFormatXKCDData();
    $subject = "Your XKCD Comic";

    foreach ($emails as $email) {
        sendMail($email, $subject, $comicHtml);
    }
}
