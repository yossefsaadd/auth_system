<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "auth_system");
if ($mysqli->connect_error) {
    die(" dissconnect: " . $mysqli->connect_error);
}

$query = "SELECT * FROM products";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>admin_dashbourd</title>
</head>
<body>
    <h1>Hi Admin<?= $_SESSION['name'] ?> 👑</h1>
    <a href="../auth/logout.php">Logout</a>

    <h2>all products</h2>

    <table  cellpadding="10">
        <tr>
            <th>ID</th>
            <th> name of product</th>
            <th>price</th>
            <th>image</th>
            <th>rules</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><img src="../<?= $row['image'] ?>" width="50" height="50"></td>
                    <td>
                        <form action="delete_product.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" onclick="return confirm('are you sure you will delete?');">🗑️ delete</button>
                        </form>
                        <a href="edit_product.php?id=<?= $row['id'] ?>">edit</a> |
                        <a href="add_product.php" style="display:inline-block; 
                                margin-bottom: 20px; 
                                background-color: #28a745;
                                color: white; padding: 10px 15px;
                                text-decoration: none;
                                border-radius: 5px;">➕ Add New Product</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">no products avalibale<a href="add_product.php" style="display:inline-block;
             margin-bottom: 20px;
              background-color: #28a745; 
              color: white; 
              padding: 10px 15px; 
              text-decoration: none;
               border-radius: 5px;">➕ Add New Product</a>
</td></tr>
        <?php endif; ?>
    </table>
    <a href="../main-main.php">main_page</a>

</body>
</html>
