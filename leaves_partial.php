<h3>Leave Management</h3>

<form method="POST">
  <label>Staff:</label><br>
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
    <option value="">-- Select Type --</option>
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
  <select name="status" required>
    <option value="Pending">Pending</option>
    <option value="Approved">Approved</option>
    <option value="Rejected">Rejected</option>
  </select><br><br>

  <button type="submit">Add Leave</button>
</form>

<hr>

<h4>Leave Records</h4>
<table border="1" cellpadding="5">
  <tr>
    <th>Staff</th>
    <th>Type</th>
    <th>Start</th>
    <th>End</th>
    <th>Status</th>
  </tr>
  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $staff_id = $_POST['staff_id'];
      $leave_type = $_POST['leave_type'];
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];
      $status = $_POST['status'];

      $stmt = $conn->prepare("INSERT INTO leaves (staff_id, leave_type, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("issss", $staff_id, $leave_type, $start_date, $end_date, $status);
      $stmt->execute();
    }

    $q = $conn->query("SELECT l.*, s.name AS staff_name FROM leaves l JOIN staff s ON l.staff_id = s.id ORDER BY l.id DESC");
    while ($row = $q->fetch_assoc()):
  ?>
    <tr>
      <td><?= htmlspecialchars($row['staff_name']) ?></td>
      <td><?= htmlspecialchars($row['leave_type']) ?></td>
      <td><?= htmlspecialchars($row['start_date']) ?></td>
      <td><?= htmlspecialchars($row['end_date']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
