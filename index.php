<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Aptic HR Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-box">
    <h2>HR Login</h2>
    <form action="auth/validate.php" method="post">
      <label>Email:</label>
      <input type="email" name="email" required><br><br>
      
      <label>Password:</label>
      <input type="password" name="password" required><br><br>
      
      <input type="submit" value="Login">
    </form>
    <?php if (isset($_GET['error'])): ?>
      <p style="color:red;">Invalid login. Try again.</p>
    <?php endif; ?>
  </div>
</body>
</html>
