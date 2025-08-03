<?php
session_start();


$mysqli = new mysqli("localhost", "root", "", "auth_system");
if ($mysqli->connect_error) {
    die("failed to process database❌ : " . $mysqli->connect_error);
}

$error = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';


    $stmt = $mysqli->prepare("SELECT id, password, name, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password, $name, $role);
        $stmt->fetch();


        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;


            if ($role === 'admin') {
                header("Location: ../admin/admin_dashboard.php");
            } else {
                header("Location: ../dashboard/user_dashboard.php");
            }
            exit;
        } else {
            $error = "password not correct ❌";
        }
    } else {
        $error = "this email not login❌";
    }

    $stmt->close();
    $mysqli->close();
}
?>
