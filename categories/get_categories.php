<?php

$conn = new mysqli("localhost", "root", "", "your_database_name");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM categories";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li><a href="products.php?category_id=' . $row['id'] . '">' .
         htmlspecialchars($row['name']) . '</a></li>';
    }
} else {
    echo '<li>No categories found.</li>';
}

$conn->close();
?>
