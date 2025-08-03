<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  echo "Please log in to view your cart.";
  exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

  <h2>Your Cart</h2>

  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
      ?>
      <div class="cart-item">
        <img src="../<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <div class="cart-details">
          <p><strong><?= htmlspecialchars($row['name']) ?></strong></p>
          <p>Quantity: <?= $row['quantity'] ?></p>
          <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>

          <form action="remove-item.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
            <label for="remove_quantity">Remove quantity:</label>
            <select name="remove_quantity" required>
              <?php for ($i = 1; $i <= $row['quantity']; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
            <button type="submit" class="remove-btn">❌ Remove</button>
          </form>
        </div>
        <form action="../order/confrim_order.php" method="POST">
    <button type="submit" onclick="return confirm('Are you sure you want to place the order?')">
        ✅ Confirm Purchase
    </button>
</form>

      </div>
    <?php endwhile; ?>

    <h3>Total: $<?= number_format($total, 2) ?></h3>

  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>

</body>
</html>
