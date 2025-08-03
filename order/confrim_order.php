<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized ❌");
}

$user_id = $_SESSION['user_id'];



$stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();


$stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];


    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert_item->bind_param("iii", $order_id, $product_id, $quantity);
    $insert_item->execute();
    $insert_item->close();
}
$stmt->close();


$delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();
$delete->close();

$conn->close();


header("Location: ../main-main.php");
exit;
?>
