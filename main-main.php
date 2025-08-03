<?php
session_start();
require_once __DIR__ . '/./includes/db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$user_id = $_SESSION['user_id'] ?? null;
$mysqli = new mysqli("localhost", "root", "", "auth_system");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Main Page - Products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/v4-shims.min.css" />
  <link rel="stylesheet" href="./css/main-main.css" />
</head>
<body>
  <nav class="navbar">
    <div class="nav-left">
      <a href="main-main.php">Zamzor <i class="fas fa-globe"></i></a>
    </div>

    <div class="nav-language">
      <select id="lang-switch">
        <option value="en">EN</option>
        <option value="ar">AR</option>
      </select>
    </div>

    <div class="nav-search">
      <input type="text" id="search-input" placeholder="Search products..." />
      <button><i class="fas fa-search"></i></button>
    </div>

    <div class="nav-account">
      <div class="account-menu">
        <span id="account-text">Hello, Sign in</span>
        <p>Account & Lists <i class="fas fa-user"></i></p>
        <div class="dropdown">
          <a href="./login.html"><i class="fas fa-sign-in-alt"></i> switch acount</a>
          <a href="./auth/logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
          <a href="./order/order.php"><i class="fas fa-box"></i> Orders</a>
          <a href="./wishlist/view.php"><i class="fas fa-heart"></i> Wishlist</a>
        </div>
      </div>
    </div>

    <div class="nav-cart" onclick="goToCart()">
      <i class="fas fa-shopping-cart"></i>
      <a href="./cart/cart.php" class="cart-text">Cart</a>
      <span id="cart-count">0</span>
    </div>
  </nav>

  <section class="products">
    <h2>Products</h2>
    <div class="product-list" id="product-list">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $product_id = $row['id'];
            $in_wishlist = false;

            if ($user_id) {
              $stmt = $mysqli->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND product_id = ?");
              $stmt->bind_param("ii", $user_id, $product_id);
              $stmt->execute();
              $stmt->store_result();
              $in_wishlist = $stmt->num_rows > 0;
              $stmt->close();
            }
          ?>
          <div class="product-card"
               data-id="<?= $row['id'] ?>"
               data-name="<?= htmlspecialchars($row['name']) ?>"
               data-price="<?= $row['price'] ?>"
               data-description="<?= htmlspecialchars($row['description']) ?>"
               data-image="<?= htmlspecialchars($row['image']) ?>"
               onclick="openProductModal(this)">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <img src="admin/<?= htmlspecialchars($row['image']) ?>" width="100" alt="<?= htmlspecialchars($row['name']) ?>" />
            <p><?= htmlspecialchars($row['description']) ?></p>
            <p>Price: $<?= $row['price'] ?></p>
            <input type="number" id="quantity-<?= $row['id'] ?>" value="1" min="1" />
            <button class="add-to-cart" data-product-id="<?= $row['id'] ?>">Add to Cart</button>
            <form action="wishlist/<?= $in_wishlist ? 'delete.php' : 'add_to_wishlist.php' ?>" method="post" style="display:inline;">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <button type="submit" style="background:none;border:none;font-size:1.5em;cursor:pointer;" title="<?= $in_wishlist ? 'Remove from wishlist' : 'Add to wishlist' ?>">
                  <?= $in_wishlist ? '❤️' : '🤍' ?>
              </button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products found.</p>
      <?php endif; ?>
    </div>
  </section>

  <div id="productModal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" onclick="closeProductModal()">&times;</span>
      <h2 id="modalProductName"></h2>
      <img id="modalProductImage" src="" alt="" width="200" />
      <p id="modalProductDescription"></p>
      <p id="modalProductPrice"></p>
      <label>Quantity:
        <input type="number" id="modalProductQuantity" value="1" min="1" />
      </label>
      <br><br>
      <button id="modalAddToCart">Add to Cart</button>
    </div>
  </div>

  <script src="./js/main-main.js"></script>
</body>
</html>
