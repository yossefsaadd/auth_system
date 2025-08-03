<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.html");
    exit;
}

$name = $_SESSION['name'] ?? "user";
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>user_dashbourd </title>
    <link rel="stylesheet" href="../css/user.css" />
</head>
<body>

<h1>أهلاً بك يا <?= htmlspecialchars($name) ?></h1>
<p>this your dashbourd</p>
<a href="../zamzor-main.html">main page</a>
<a href="../register/step1_email.php">switch acounts</a>
<a href="../logout/logout.php" class="logout-btn">logout</a>

</body>
</html>
