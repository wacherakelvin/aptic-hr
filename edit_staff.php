<?php
session_start();
include 'db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM staff WHERE id = $id");
$staff = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $department = $_POST['department'];
  $phone = $_POST['phone'];
  $start_date = $_POST['start_date'];
  $status = $_POST['status'];

  $stmt = $conn->prepare("UPDATE staff SET name=?, email=?, role=?, department=?, phone=?, start_date=?, status=? WHERE id=?");
  $stmt->bind_param("sssssssi", $name, $email, $role, $department, $phone, $start_date, $status, $id);
  $stmt->execute();

  // Handle multiple file uploads
  if (!empty($_FILES['documents']['name'][0])) {
    $uploadDir = "uploads/";

    foreach ($_FILES['documents']['tmp_name'] as $index => $tmpName) {
      $filename = basename($_FILES['documents']['name'][$index]);
      $targetPath = $uploadDir . time() . "_" . $filename;

      if (move_uploaded_file($tmpName, $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO staff_documents (staff_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $filename, $targetPath);
        $stmt->execute();
      }
    }
  }

  header("Location: staff.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Staff</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Edit Staff Member</h2>
  <form method="POST" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($staff['name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required><br><br>

    <label>Role:</label><br>
    <input type="text" name="role" value="<?= htmlspecialchars($staff['role']) ?>" required><br><br>

    <label>Department:</label><br>
    <select name="department" required>
    <option value="">-- Select Department --</option>
    <option value="operations" <?= $staff['department'] === 'operations' ? 'selected' : '' ?>>Operations</option>
    <option value="tradefinance" <?= $staff['department'] === 'tradefinance' ? 'selected' : '' ?>>Trade Finance</option>
    <option value="credit" <?= $staff['department'] === 'credit' ? 'selected' : '' ?>>Credit</option>
    <option value="insurance" <?= $staff['department'] === 'insurance' ? 'selected' : '' ?>>Insurance</option>
    <option value="finance" <?= $staff['department'] === 'finance' ? 'selected' : '' ?>>Finance</option>
    <option value="administration" <?= $staff['department'] === 'administration' ? 'selected' : '' ?>>Administration</option>
    <option value="it" <?= $staff['department'] === 'it' ? 'selected' : '' ?>>IT</option>
    <option value="management" <?= $staff['department'] === 'management' ? 'selected' : '' ?>>Management</option>
    </select><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($staff['phone']) ?>" required><br><br>

    <label>Start Date:</label><br>
    <input type="date" name="start_date" value="<?= $staff['start_date'] ?>" required><br><br>
 
    <label>Status:</label><br>
    <select name="status" required>
    <option value="">-- Select Status --</option>
    <option value="Active" <?= $staff['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
    <option value="Inactive" <?= $staff['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    <option value="On Leave" <?= $staff['status'] === 'On Leave' ? 'selected' : '' ?>>On Leave</option>
    </select><br><br>

    <label>Upload New Documents (you can select multiple):</label><br>
    <input type="file" name="documents[]" multiple><br><br>

    <button type="submit">Save Changes</button>
  </form>

  <hr>
  <h3>Uploaded Documents</h3>
  <ul>
    <?php
      $docs = $conn->query("SELECT * FROM staff_documents WHERE staff_id = $id ORDER BY uploaded_at DESC");
      while ($doc = $docs->fetch_assoc()):
    ?>
      <li>
        <?= htmlspecialchars($doc['file_name']) ?> - 
        <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank">View</a> | 
        <a href="<?= htmlspecialchars($doc['file_path']) ?>" download>Download</a>
      </li>
    <?php endwhile; ?>
  </ul>
</body>
</html>

