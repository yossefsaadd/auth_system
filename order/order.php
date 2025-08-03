<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Unauthorized");
}

$user_id = $_SESSION['user_id'];


$orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$orders->bind_param("i", $user_id);
$orders->execute();
$orders_result = $orders->get_result();
?>

<h2>📦 Your Orders</h2>

<?php while ($order = $orders_result->fetch_assoc()): ?>
    <div class="order-item<?= $order['status'] === 'cancelled' ? ' cancelled-order' : '' ?>" id="order-<?= $order['id'] ?>" style="border: 1px solid #ccc; margin-bottom: 15px; padding: 10px;">
        <strong>Order ID:</strong> <?= $order['id'] ?> |
        <strong>Date:</strong> <?= $order['order_date'] ?><br>
        <strong>Status:</strong> <?= htmlspecialchars($order['status']) ?><br><br>

        <?php

        $items = $conn->prepare("
            SELECT p.name, p.image, p.price, oi.quantity
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $items->bind_param("i", $order['id']);
        $items->execute();
        $items_result = $items->get_result();
        ?>

        <?php while ($item = $items_result->fetch_assoc()): ?>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <img src="../admin/<?= htmlspecialchars($item['image']) ?>" width="80">
                <div>
                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                    Quantity: <?= $item['quantity'] ?><br>
                    Price: $<?= $item['price'] ?>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if ($order['status'] === 'pending'): ?>
            <a class="cancel-btn" href="./cancel_order.php?id=<?= $order['id'] ?>" onclick="return confirm('Are you sure you want to cancel this order?');">❌ Cancel Order</a>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

<script>
window.addEventListener('DOMContentLoaded', function() {

    setTimeout(function() {
        document.querySelectorAll('.cancelled-order').forEach(function(el) {
            el.style.display = 'none';
        });
    }, 3000);
});
</script>

<a href="../main-main.php">🔙 Back to menu</a>
