<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
  $name = $_POST['name'];
  $school = $_POST['school'];
  $department = $_POST['department'];
  $supervisor = $_POST['supervisor'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $status = $_POST['status'];
  $notes = $_POST['notes'];

  $log = "Intern Form Received:\nName: $name\nSchool: $school\nDept: $department\n\n";
  file_put_contents("debug.log", $log, FILE_APPEND);

  $stmt = $conn->prepare("INSERT INTO interns (name, school, department, supervisor, start_date, end_date, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  
  if (!$stmt) {
    file_put_contents("debug.log", "Prepare failed: " . $conn->error . "\n", FILE_APPEND);
  }

  $stmt->bind_param("ssssssss", $name, $school, $department, $supervisor, $start_date, $end_date, $status, $notes);
  
  if ($stmt->execute()) {
    file_put_contents("debug.log", "Insert Successful!\n", FILE_APPEND);
    header("Location: interns.php");
    exit;
  } else {
    file_put_contents("debug.log", "Insert Failed: " . $stmt->error . "\n", FILE_APPEND);
  }
}
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: index.php");
  exit;
}
include 'db.php';

// Handle Add Intern
if (isset($_POST['add'])) {
  $name = $_POST['name'];
  $school = $_POST['school'];
  $department = $_POST['department'];
  $supervisor = $_POST['supervisor'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $status = $_POST['status'];
  $notes = $_POST['notes'];

  $stmt = $conn->prepare("INSERT INTO interns (name, school, department, supervisor, start_date, end_date, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssss", $name, $school, $department, $supervisor, $start_date, $end_date, $status, $notes);

  if ($stmt->execute()) {
    header("Location: interns.php");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Interns</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Add Intern</h2>
  <form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" required><br>

    <label>School:</label><br>
    <input type="text" name="school" required><br>

    <label>Department:</label><br>
    <input type="text" name="department" required><br>

    <label>Supervisor:</label><br>
    <input type="text" name="supervisor"><br>

    <label>Start Date:</label><br>
    <input type="date" name="start_date"><br>

    <label>End Date:</label><br>
    <input type="date" name="end_date"><br>

    <label>Status:</label><br>
    <select name="status">
      <option value="Active">Active</option>
      <option value="Completed">Completed</option>
      <option value="Pending">Pending</option>
    </select><br>

    <label>Notes:</label><br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit" name="add">Add Intern</button>
  </form>

  <hr>

  <h2>Intern List</h2>
  <table border="1" cellpadding="5">
    <tr>
      <th>Name</th>
      <th>School</th>
      <th>Department</th>
      <th>Supervisor</th>
      <th>Start</th>
      <th>End</th>
      <th>Status</th>
      <th>Notes</th>
      <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM interns ORDER BY id DESC");
    while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['school']) ?></td>
        <td><?= htmlspecialchars($row['department']) ?></td>
        <td><?= htmlspecialchars($row['supervisor']) ?></td>
        <td><?= htmlspecialchars($row['start_date']) ?></td>
        <td><?= htmlspecialchars($row['end_date']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= htmlspecialchars($row['notes']) ?></td>
        <td>
          <a href="edit_intern.php?id=<?= $row['id'] ?>">Edit</a> |
          <a href="delete_intern.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this intern?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>

