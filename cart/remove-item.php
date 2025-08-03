<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$remove_quantity = $_POST['remove_quantity'];

if (!is_numeric($product_id) || !is_numeric($remove_quantity)) {
  die("Invalid input.");
}


$sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  $current_quantity = $row['quantity'];
  $new_quantity = $current_quantity - $remove_quantity;

  if ($new_quantity > 0) {

    $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
    $update_stmt->execute();
  } else {

    $delete_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $user_id, $product_id);
    $delete_stmt->execute();
  }
}

header("Location: cart.php");
exit;
