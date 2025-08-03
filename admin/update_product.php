<?php
require_once __DIR__ . '/../includes/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$image = $_POST['image'];

$stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
$stmt->bind_param("sdsi", $name, $price, $image, $id);
$stmt->execute();

header("Location: admin_dashboard.php");
