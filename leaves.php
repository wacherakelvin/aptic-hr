<?php
session_start();
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $staff_id = $_POST['staff_id'];
  $leave_type = $_POST['leave_type'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $status = $_POST['status'];

  $stmt = $conn->prepare("INSERT INTO leaves (staff_id, leave_type, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("issss", $staff_id, $leave_type, $start_date, $end_date, $status);
    $stmt->execute();
    $stmt->close();
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Leave Requests</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Leave Requests</h2>

<form method="post">
  <label>Staff Member:</label><br>
  <select name="staff_id" required>
    <option value="">-- Select Staff --</option>
    <?php
      $staff = $conn->query("SELECT id, name FROM staff");
      while ($s = $staff->fetch_assoc()):
    ?>
      <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label>Type of Leave:</label><br>
  <select name="leave_type" required>
    <option value="Annual">Annual</option>
    <option value="Sick">Sick</option>
    <option value="Maternity">Maternity</option>
    <option value="Paternity">Paternity</option>
    <option value="Unpaid">Unpaid</option>
  </select><br><br>

  <label>Start Date:</label><br>
  <input type="date" name="start_date" required><br><br>

  <label>End Date:</label><br>
  <input type="date" name="end_date" required><br><br>

  <label>Status:</label><br>
  <select name="status">
    <option value="Pending">Pending</option>
    <option value="Approved">Approved</option>
    <option value="Rejected">Rejected</option>
  </select><br><br>

  <button type="submit">Add Leave</button>
</form>

<hr>

<!-- Display Leave Records -->
<h3>Leave Applications</h3>
<table border="1" cellpadding="8">
  <tr>
    <th>Staff Name</th>
    <th>Type</th>
    <th>Start</th>
    <th>End</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  <?php
    $result = $conn->query("
      SELECT l.id, s.name AS staff_name, l.leave_type, l.start_date, l.end_date, l.status
      FROM leaves l
      JOIN staff s ON l.staff_id = s.id
      ORDER BY l.id DESC
    ");
    while ($row = $result->fetch_assoc()):
  ?>
    <tr>
      <td><?= htmlspecialchars($row['staff_name']) ?></td>
      <td><?= htmlspecialchars($row['leave_type']) ?></td>
      <td><?= htmlspecialchars($row['start_date']) ?></td>
      <td><?= htmlspecialchars($row['end_date']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td>
        <a href="edit_leave.php?id=<?= $row['id'] ?>">Edit</a> |
        <a href="delete_leave.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this leave?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>

