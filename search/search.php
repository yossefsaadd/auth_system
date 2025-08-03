<?php
require_once __DIR__ . '/../includes/db.php';

$keyword = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '';

$stmt = $conn->prepare("SELECT id, name, image, price FROM products WHERE name LIKE ?");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
