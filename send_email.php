<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include Composer autoloader

function sendEmail($to, $subject, $body) {
  $mail = new PHPMailer(true);

  try {
    // SMTP Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kellyviny605@gmail.com';        // ✅ Your Gmail
    $mail->Password   = 'jcsehmkwpgriqjyi';            // ✅ Your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS
    $mail->Port       = 587;

    // Email headers
    $mail->setFrom('kellyviny605@gmail.com', 'Aptic HR System');
    $mail->addAddress($to);                              // Recipient from function call

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
    return true;
  } catch (Exception $e) {
    // Log the error to a file
    error_log("Mail Error: {$mail->ErrorInfo}\n", 3, __DIR__ . '/mail_error.log');
    return false;
  }
}

