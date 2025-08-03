<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'count' => 0]);
  exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT SUM(quantity) AS total FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$count = $row['total'] ?? 0;
echo json_encode(['success' => true, 'count' => $count]);
