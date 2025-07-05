<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $conn->query("DELETE FROM leaves WHERE id = $id");
}

header("Location: leaves.php");
exit;
