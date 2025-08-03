<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("❌ Access denied. Admins only.");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
</head>
<body>
  <h2>Add New Product</h2>
  <form action="process_add_product.php" method="POST" enctype="multipart/form-data">
    <label>Product Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" required></textarea><br><br>

    <label>Image:</label><br>
    <input type="file" name="image" accept="image/*" required><br><br>

    <button type="submit">Save Product</button>
  </form>
</body>
</html>
