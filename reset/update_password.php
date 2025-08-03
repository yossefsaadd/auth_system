<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if (!($_SESSION['code_verified'] ?? false)) {
    $_SESSION['error_message'] = "❌ You are not authorized. Please verify your code first.";
    header("Location: reset_password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm'] ?? '');


if ($password !== $confirm) {
        $_SESSION['error_message'] = "❌ The password and confirmation do not match.";
        header("Location: reset_password.php");
        exit;
    }



    $errors = [];
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }



    if (!empty($errors)) {
        $_SESSION['error_message'] = "❌ " . implode("<br>❌ ", $errors);
        header("Location: reset_password.php");
        exit;
    }


    $email = $_SESSION['reset_email'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, verification_code = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    $_SESSION['success_message'] = "✅ Your password has been updated successfully! You can now log in.";
    header("refresh:2; url=../login.html");
    exit();

    unset($_SESSION['code_verified']);
    unset($_SESSION['reset_email']);

    header("Location: reset_password.php");
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: reset_password.php");
    exit;
    
    header("refresh:2; url=../login.html");
exit();
}
?>
