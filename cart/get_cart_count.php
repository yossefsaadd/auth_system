<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => true, 'count' => 0]);
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row['total'] ?? 0;

echo json_encode(['success' => true, 'count' => $count]);
