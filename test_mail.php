<?php
require 'send_email.php';

$to = 'kellyviny605@gmail.com';
$subject = 'Test Email from Aptic HR';
$body = '<p>This is a test email sent from the server using PHPMailer & Gmail SMTP.</p>';

if (sendEmail($to, $subject, $body)) {
  echo "Email sent successfully!";
} else {
  echo "Failed to send email.";
}
