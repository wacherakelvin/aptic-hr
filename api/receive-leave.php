<?php
// receive-leave.php

// Connect to DB
require_once('../db.php');

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
  exit;
}

// Get staff ID from name
$name = $input['name'];
$stmt = $conn->prepare("SELECT id FROM staff WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
  http_response_code(404);
  echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
  exit;
}

$staff_id = $staff['id'];
$leave_type = $input['leave_type'];
$start_date = date('Y-m-d', strtotime($input['start_date']));
$end_date = date('Y-m-d', strtotime($input['end_date']));
$status = 'Pending';

$stmt = $conn->prepare("INSERT INTO leaves (staff_id, leave_type, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $staff_id, $leave_type, $start_date, $end_date, $status);

if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'Leave added']);
} else {
  http_response_code(500);
  echo json_encode(['status' => 'error', 'message' => 'DB insert failed']);
}
?>
