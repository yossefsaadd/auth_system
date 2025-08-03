<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("❌ Unauthorized.");
}

$mysqli = new mysqli("localhost", "root", "", "auth_system");

if ($mysqli->connect_error) {
    die("❌ Database connection failed: " . $mysqli->connect_error);
}

$name        = $_POST['name'] ?? '';
$price       = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';

if (!$name || !$price || !$description || !isset($_FILES['image'])) {
    die("❌ All fields are required.");
}

$imageName = $_FILES['image']['name'];
$tmpName   = $_FILES['image']['tmp_name'];
$uploadDir = './uploads/';
$imagePath = $uploadDir . basename($imageName);


if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!move_uploaded_file($tmpName, $imagePath)) {
    die("❌ Failed to upload image.");
}

$stmt = $mysqli->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sdss", $name, $price, $description, $imagePath);

if ($stmt->execute()) {
    echo "✅ Product added successfully! <a href='add_product.php'>Add another</a> | <a href='admin_dashboard.php'>Go to Dashboard</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
