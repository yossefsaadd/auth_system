<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
  exit;
}

$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$user_id = $_SESSION['user_id'];

$product_check = $conn->prepare("SELECT id FROM products WHERE id = ?");
$product_check->bind_param("i", $product_id);
$product_check->execute();
$result = $product_check->get_result();
if ($result->num_rows === 0) {
  echo json_encode(['success' => false, 'message' => 'Product not found']);
  exit;
}

$stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {

  $existing = $res->fetch_assoc()['quantity'];
  $new_quantity = $existing + $quantity;
  $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
  $update->bind_param("iii", $new_quantity, $user_id, $product_id);
  $update->execute();
} else {

  $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
  $insert->bind_param("iii", $user_id, $product_id, $quantity);
  $insert->execute();
}

echo json_encode(['success' => true, 'message' => 'Added to cart']);
