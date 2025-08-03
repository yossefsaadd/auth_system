<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Unauthorized");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    die("❌ No product selected");
}

$stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {

    header("Location: ../main-main.php?message=Product removed from wishlist");
} else {

    header("Location: view.php?error=Failed to remove product from wishlist");
}
?>



<script>
document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = this.dataset.productId;
        const action = this.dataset.action;
        const url = action === 'delete' ? 'wishlist/delete.php' : 'wishlist/add_to_wishlist.php';

        fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'product_id=' + encodeURIComponent(productId)
        })
        .then(response => response.text())
        .then(data => {
            alert(action === 'delete' ? 'Deleted from wishlist done' : 'Added to wishlist');

            location.reload(); 
        });
    });
});
</script>
