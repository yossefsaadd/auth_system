<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if (!($_SESSION['code_verified'] ?? false)) {
    die("❌ You are not authorized to access this page.");
}

$error_message = "";
$success_message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error_message = "❌ The password and confirmation do not match.";
    } else {
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
        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        if (!empty($errors)) {
            $error_message = "❌ " . implode("<br>❌ ", $errors);
        } else {
            $email = $_SESSION['reset_email'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ?, verification_code = NULL WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();

            $success_message = "✅ Your password has been updated successfully!";
            unset($_SESSION['code_verified']);
            unset($_SESSION['reset_email']);
                header("refresh:1; url=../login.html");
exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <title>change the password</title>
    <link rel="stylesheet" href="../css/forgot-password.css" />
    <script src="../js/reset_pass.js"></script>
</head>
<body>
<div class="con">
  <h1>Zamzor</h1>
  <div class="form-box">
    <h2>Reset Password</h2>

    <?php if (!empty($error_message)): ?>
      <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
      <p class="success-message"><?= $success_message ?></p>
    <?php endif; ?>

    <form method="post">
      <div class="password-container">
        <input type="password" id="password" name="password" placeholder="New Password" required>
        <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
      </div>

      <div class="password-container">
        <input type="password" id="repassword" name="confirm_password" placeholder="Re-enter Password" required>
        <span class="toggle-password" onclick="togglePassword('repassword', this)">👁️</span>
      </div>

      <ul id="password-rules" class="password-rules">
        <li id="rule-length">🔴 At least 8 characters</li>
        <li id="rule-uppercase">🔴 At least one uppercase letter</li>
        <li id="rule-lowercase">🔴 At least one lowercase letter</li>
        <li id="rule-number">🔴 At least one number</li>
        <li id="rule-specialkey">🔴 At least one special character</li>
      </ul>
      <button  type="submit">Change Password</button>
    </form>
  </div>
</div>
</body>
</html>