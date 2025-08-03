<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if ($product_id) {
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } else {

    }

    $stmt->close();
}

header("Location: ../main-main.php");
exit;
?>

<?php

$user_id = $_SESSION['user_id'];
$wishlist = [];
$stmt = $conn->prepare("SELECT product_id FROM wishlist WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $wishlist[] = $row['product_id'];
}
$stmt->close();
?>

<?php foreach ($products as $product): ?>
    <div class="product-card">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <img src="../admin/<?= htmlspecialchars($product['image']) ?>" width="100" />
        <p><?= htmlspecialchars($product['description']) ?></p>
        <p>Price: $<?= $product['price'] ?></p>
        <form action="wishlist/<?= in_array($product['id'], $wishlist) ? 'delete.php' : 'add_to_wishlist.php' ?>" method="POST" style="display:inline;">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" style="background:none;border:none;font-size:1.5em;cursor:pointer;">
                <?= in_array($product['id'], $wishlist) ? '❤️' : '🤍' ?>
            </button>
        </form>
    </div>
<?php endforeach; ?>

<style>
button[type="submit"] {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    transition: transform 0.1s;
}
button[type="submit"]:active {
    transform: scale(0.9);
}
</style>
