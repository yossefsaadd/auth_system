<?php
session_start();

if (!isset($_SESSION['reg_name'], $_SESSION['reg_email'], $_SESSION['reg_code'])) {
    die("❌ Unauthorized access. Please <a href='step1_email.php'>start registration</a>.");
}

$mysqli = new mysqli("localhost", "root", "", "auth_system");

if ($mysqli->connect_error) {
    die("❌ Database connection failed: " . $mysqli->connect_error);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"] ?? '';
    $confirm  = $_POST["confirm"] ?? '';


    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
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
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $name   = $_SESSION['reg_name'];
        $email  = $_SESSION['reg_email'];
        $code   = $_SESSION['reg_code'];
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $checkStmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "This email is already registered.";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, verification_code) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $email, $hashed, $code);

            if ($stmt->execute()) {
                session_destroy();
                header("Location: ./success.php");
                exit;
            } else {
                $errors[] = "Error while saving your data.";
            }
            $stmt->close();
        }

        $checkStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../css/register.css">
  <script src="../js/hide-pass.js"></script>
  <title>Zamzor Register</title>
</head>
<body>
  <div class="con">
        <h1>Zamzor</h1>
       <div class="form-box">
            <h2>Create Account</h2>
                <form method="POST" action="">

                <div class="password-container">
                            <input type="password" id="password" name="password" placeholder="Password" required>
                            <span class="toggle-password" onclick="togglePassword('password', this)">👁</span>
                        </div>


                        <div class="password-container">
                            <input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required>
                            <span class="toggle-password" onclick="togglePassword('confirm', this)">👁</span>
                        </div>

                        <ul class="error-list" id="password-errors"></ul>

                        <button type="submit">Register</button>
                </form>
        </div>

  </div>
</body>
</html>
