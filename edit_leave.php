<?php
session_start();
include 'db.php';
require 'send_email.php'; // Include PHPMailer function

if (!isset($_GET['id'])) {
  header("Location: leaves.php");
  exit;
}

$id = intval($_GET['id']);
$leave = $conn->query("SELECT * FROM leaves WHERE id = $id")->fetch_assoc();
$current_staff_id = $leave['staff_id'];
$prev_status = $leave['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $staff_id = $_POST['staff_id'];
  $leave_type = $_POST['leave_type'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $status = $_POST['status'];

  $stmt = $conn->prepare("UPDATE leaves SET staff_id=?, leave_type=?, start_date=?, end_date=?, status=? WHERE id=?");
  $stmt->bind_param("issssi", $staff_id, $leave_type, $start_date, $end_date, $status, $id);

  if ($stmt->execute()) {
    // Send email only if status changed to Approved
    if ($status === 'Approved' && $prev_status !== 'Approved') {
      $get_staff = $conn->prepare("SELECT name, email FROM staff WHERE id = ?");
      $get_staff->bind_param("i", $staff_id);
      $get_staff->execute();
      $result = $get_staff->get_result();

      if ($row = $result->fetch_assoc()) {
        $staff_email = $row['email'];
        $staff_name = $row['name'];

        $subject = "Leave Request Approved";
        $body = "Hello $staff_name,<br><br>Your leave request from <strong>$start_date</strong> to <strong>$end_date</strong> has been <strong>Approved</strong>.<br><br>Regards,<br>Aptic HR";

        sendEmail($staff_email, $subject, $body);
      }
    }

    header("Location: leaves.php");
    exit;
  } else {
    echo "Update failed: " . $stmt->error;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Leave</title>
  <link rel="stylesheet" href="style.css"> <!-- ✅ Link to your CSS -->
</head>
<body>
<h2>Edit Leave</h2>
<form method="post">
  <label>Staff Member:</label><br>
  <select name="staff_id" required>
    <option value="">-- Select Staff --</option>
    <?php
    $staff_list = $conn->query("SELECT id, name FROM staff");
    while ($s = $staff_list->fetch_assoc()):
    ?>
      <option value="<?= $s['id'] ?>" <?= ($s['id'] == $current_staff_id) ? 'selected' : '' ?>>
        <?= htmlspecialchars($s['name']) ?>
      </option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Type of Leave:</label><br>
  <select name="leave_type" required>
    <option value="Annual" <?= $leave['leave_type'] == 'Annual' ? 'selected' : '' ?>>Annual</option>
    <option value="Sick" <?= $leave['leave_type'] == 'Sick' ? 'selected' : '' ?>>Sick</option>
    <option value="Maternity" <?= $leave['leave_type'] == 'Maternity' ? 'selected' : '' ?>>Maternity</option>
    <option value="Paternity" <?= $leave['leave_type'] == 'Paternity' ? 'selected' : '' ?>>Paternity</option>
    <option value="Unpaid" <?= $leave['leave_type'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
  </select><br><br>

  <label>Start Date:</label><br>
  <input type="date" name="start_date" value="<?= $leave['start_date'] ?>" required><br><br>

  <label>End Date:</label><br>
  <input type="date" name="end_date" value="<?= $leave['end_date'] ?>" required><br><br>

  <label>Status:</label><br>
  <select name="status" required>
    <option value="Pending" <?= $leave['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Approved" <?= $leave['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
    <option value="Rejected" <?= $leave['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
  </select><br><br>

  <button type="submit">Update Leave</button>
</form>
</body>
</html>
