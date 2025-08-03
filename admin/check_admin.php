<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  die("Please log in.");
}

$stmt = $conn->prepare("SELECT is_admin FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['is_admin'] != 1) {
  die("Access Denied. Admins only.");
}
?>
