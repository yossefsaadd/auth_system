<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die("disconnect : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $email = $_SESSION['reset_email'] ?? '';

    if (!$email) {
        die("❌An error occurred. Please start the process again.");
    }

    $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($db_code);
    $stmt->fetch();

    if ($code == $db_code) {
        $_SESSION['code_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        die("❌ The code is incorrect.");
    }
} else {
    die("Invalid request.");
}
?>
