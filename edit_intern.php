<?php
include 'db.php';
$id = $_GET['id'];
$intern = $conn->query("SELECT * FROM interns WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $school = $_POST['school'];
  $department = $_POST['department'];
  $phone = $_POST['phone'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $status = $_POST['status'];

  $conn->query("UPDATE interns SET 
    name='$name', 
    school='$school', 
    department='$department', 
    phone='$phone', 
    start_date='$start_date', 
    end_date='$end_date', 
    status='$status' 
    WHERE id=$id");

  header("Location: interns.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Intern</title>
  <link rel="stylesheet" href="style.css"> <!-- ✅ Link to your CSS -->
</head>
<body>
<h3>Edit Intern</h3>
<form method="POST">
  <label>Name:</label><br>
  <input type="text" name="name" value="<?= $intern['name'] ?>" required><br>

  <label>School:</label><br>
  <input type="text" name="school" value="<?= $intern['school'] ?>" required><br>

  <label>Department:</label><br>
  <input type="text" name="department" value="<?= $intern['department'] ?>" required><br>

  <label>Phone:</label><br>
  <input type="text" name="phone" value="<?= $intern['phone'] ?>" required><br>

  <label>Start Date:</label><br>
  <input type="date" name="start_date" value="<?= $intern['start_date'] ?>" required><br>

  <label>End Date:</label><br>
  <input type="date" name="end_date" value="<?= $intern['end_date'] ?>" required><br>

  <label>Status:</label><br>
  <select name="status">
    <option value="Active" <?= $intern['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
    <option value="Inactive" <?= $intern['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
  </select><br><br>

  <button type="submit">Update Intern</button>
</form>
</body>
</html>
