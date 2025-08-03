v<?php

$conn = new mysqli("localhost", "root", "", "auth_system");


if ($conn->connect_error) {
    die(" dissconnect: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $code = trim($_POST['code']);


    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("si", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $update = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();

        echo "✅ Your account has been successfully activated! You can now log in.";
    } else {
        echo "❌ The email or activation code is incorrect.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo"❌ Invalid request.";
}
?>
