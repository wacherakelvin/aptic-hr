<?php
$host = 'localhost';
$db = 'aptic_hr';
$user = 'aptic_hr_user';
$pass = 'apticpass123';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

