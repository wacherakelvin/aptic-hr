<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Aptic HR Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard-wrapper">
    <!-- ✅ Logo Here -->
    <img src="images/aptic-logo.png" alt="Aptic Logo" style="max-width: 180px; display: block; margin: 0 auto 20px;">

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?></h2>
    <p>This is the HR Dashboard.</p>

    <ul class="dashboard-menu">
      <li><a href="staff.php">👥 Manage Staff</a></li>
      <li><a href="interns.php">🎓 Manage Interns</a></li>
      <li><a href="leaves.php">🗓️ Manage Leave</a></li>
      <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
  </div>
</body>
</html>

