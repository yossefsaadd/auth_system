<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


if (!isset($_POST['id'])) {
    echo "the product not have id❌.";
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$id = intval($_POST['id']);
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "❌ erorr in delete ";
}
