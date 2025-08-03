<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image, p.description 
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
    WHERE w.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="../css/main-main.css">
</head>
<body>
    <h2>My Wishlist </h2>
    <div class="product-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <img src="../admin/<?= htmlspecialchars($row['image']) ?>" width="100" />
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p>Price: $<?= $row['price'] ?></p>
                    <form action="delete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" onclick="return confirm('Remove from wishlist?')">🗑️ Remove</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No items in your wishlist.</p>
        <?php endif; ?>
    </div>
</body>
</html>
