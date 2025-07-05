<?php
session_start();
include 'db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM staff WHERE id = $id");

header("Location: staff.php");
exit;
?>
