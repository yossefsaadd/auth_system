<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Unauthorized");
}

$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$order_id) {
    die("❌ No order ID");
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ Order not found");
}

$order = $result->fetch_assoc();

if ($order['status'] !== 'pending') {
    die("❌ Order cannot be cancelled");
}

$delete_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
$delete_items->bind_param("i", $order_id);
$delete_items->execute();
$delete_items->close();


$delete = $conn->prepare("DELETE FROM orders WHERE id = ?");
$delete->bind_param("i", $order_id);
if ($delete->execute()) {
    header("Location: ../order/order.php?msg=Order Deleted");
    exit;
} else {
    echo "❌ Failed to delete order.";
}
