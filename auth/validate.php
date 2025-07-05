<?php 
session_start();
include '../db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Log what we received from form
file_put_contents('../debug.log', "EMAIL: $email | PASS: $password\n", FILE_APPEND);

// Find user by email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Log fetched user
file_put_contents('../debug.log', "USER FOUND: " . json_encode($user) . "\n", FILE_APPEND);

// Check password
if ($user && password_verify($password, $user['password'])) {
  $_SESSION['loggedin'] = true;
  $_SESSION['user_email'] = $user['email'];
  header("Location: ../dashboard.php");
} else {
  file_put_contents('../debug.log', "LOGIN FAILED\n", FILE_APPEND);
  header("Location: ../index.php?error=1");
}
?>

