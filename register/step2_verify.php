<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entered_code = $_POST["code"] ?? '';
    if ($entered_code == $_SESSION['reg_code']) {
        header("Location: step3_password.php");
        exit;
    } else {
        $error = "incorrect code❌";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../css/register.css">
  <title>Zamzor Register</title>
</head>
<body>
    <div class="con">
        <h1>Zamzor</h1>
    <div class="form-box" id="form-register">
        <form method="post">
            <h2>Verify code</h2>
        <input type="text" name="code" placeholder="Enter activation code" required>
        <button type="submit">Verify Code</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </div>
  </div>
</body>
</html>

