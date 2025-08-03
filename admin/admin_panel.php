<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT is_admin FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row || $row['is_admin'] != 1) {
    die("You are not authorized to view this page.");
}


$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
</head>
<body>
  <h2>Welcome Admin</h2>
  <h3>All Products</h3>
  <table>
    <tr><th>Name</th><th>Price</th><th>Image</th><th>Action</th></tr>
    <?php while($row = $products->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>$<?= number_format($row['price'], 2) ?></td>
        <td><img src="<?= $row['image'] ?>" width="60"></td>
        <td>
          <a href="edit_product.php?id=<?= $row['id'] ?>">✏️ Edit</a> | 
          <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">❌ Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h3><a href="add_product.php">➕ Add New Product</a></h3>
</body>
</html>
