<?php
session_start();
require_once __DIR__ . '/../includes/db.php';


if (!isset($_SESSION['user_id'])) {
  header('Location: /../login.html');
  exit;
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<head>
    link rel="stylesheet" href="../css/main-main.css">
    <title>Products</title>
</head>
<h2>Products</h2>
<div class="products-list">
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="product-card">
      <h3><?= htmlspecialchars($row['name']) ?></h3>
      <img src="<?= htmlspecialchars($row['image']) ?>" width="100">
      <p><?= htmlspecialchars($row['description']) ?></p>
      <p>Price: $<?= number_format($row['price'], 2) ?></p>
      <input type="number" id="quantity-<?= $row['id'] ?>" value="1" min="1">
      <button class="add-to-cart" data-product-id="<?= $row['id'] ?>">Add to Cart</button>
      <span id="msg-<?= $row['id'] ?>" class="message"></span>
    </div>
  <?php endwhile; ?>
</div>

<script src="../js/cart.js"></script>
