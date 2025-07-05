<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header("Location: index.php");
  exit;
}
include 'db.php';

// Handle insert
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $department = $_POST['department'];
  $phone = $_POST['phone'];
  $start_date = $_POST['start_date'];
  $status = $_POST['status'];

  // Handle file upload
  $doc_file = '';
  if (!empty($_FILES['doc_file']['name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0755, true);
    }
    $doc_file = $target_dir . basename($_FILES['doc_file']['name']);
    move_uploaded_file($_FILES['doc_file']['tmp_name'], $doc_file);
  }

  // Insert into database
  $stmt = $conn->prepare("INSERT INTO staff (name, role, department, phone, start_date, status, doc_file)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $role, $department, $phone, $start_date, $status, $doc_file);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Staff Management - Aptic HR</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Add Staff Member</h2>
  <form method="POST" action="" enctype="multipart/form-data">
    <label>Full Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Role:</label><br>
    <input type="text" name="role" required><br><br>
    
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
    <input type="text" name="phone" required><br><br>

    <label>Start Date:</label><br>
    <input type="date" name="start_date" required><br><br>
   
    <label>Status:</label><br> 
    <select name="status" required>
    <option value="">-- Select Status --</option>
    <option value="Active" <?= $staff['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
    <option value="Inactive" <?= $staff['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    <option value="On Leave" <?= $staff['status'] === 'On Leave' ? 'selected' : '' ?>>On Leave</option>
    </select><br><br>
   
     <label>Upload Document:</label><br>
    <input type="file" name="doc_file" accept=".pdf,.doc,.docx"><br><br>

    <button type="submit">Add Staff</button>
  </form>

  <hr>

  <h2>Staff List</h2>
  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Department</th>
      <th>Phone</th>
      <th>Start Date</th>
      <th>Status</th>
      <th>Document</th>
     <th>Actions</th>
    </tr>
<?php
  $result = $conn->query("SELECT * FROM staff ORDER BY id DESC");
  while ($row = $result->fetch_assoc()):
?>
<tr>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= htmlspecialchars($row['role']) ?></td>
  <td><?= htmlspecialchars($row['department']) ?></td>
  <td><?= htmlspecialchars($row['phone']) ?></td>
  <td><?= htmlspecialchars($row['start_date']) ?></td>
  <td><?= htmlspecialchars($row['status']) ?></td>
  <td>
    <?php if (!empty($row['doc_file'])): ?>
      <a href="uploads/<?= urlencode($row['doc_file']) ?>" target="_blank">View</a>
    <?php else: ?>
      N/A
    <?php endif; ?>
  </td>
  <td>
    <a href="edit_staff.php?id=<?= $row['id'] ?>">Edit</a> |
    <a href="delete_staff.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
  </td>
</tr>
<?php endwhile; ?>
  </table>
</body>
</html>

