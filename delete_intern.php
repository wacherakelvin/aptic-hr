<?php
include 'db.php';

$id = $_GET['id'];
$conn->query("DELETE FROM interns WHERE id = $id");

header("Location: interns.php");
exit;
