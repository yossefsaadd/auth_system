<?php
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<form action="update_product.php" method="POST">
  <input type="hidden" name="id" value="<?= $product['id'] ?>">
  <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
  <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br>
  <input type="text" name="image" value="<?= $product['image'] ?>" required><br>
  <button type="submit">Update</button>
</form>
