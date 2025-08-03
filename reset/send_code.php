<?php
session_start();
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "auth_system");
if ($conn->connect_error) {
    die("dissconnect : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("incorrect email ❌");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {

        $code = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $code;


        $update = $conn->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        $update->bind_param("is", $code, $email);
        $update->execute();


        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zamzor.company@gmail.com'; 
            $mail->Password   = 'noai dppj spjc ofwf';    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_email@gmail.com', 'Zamzor');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password reset code';
            $mail->Body    = "Your password reset code is: <b>$code</b>";

            $mail->send();

            header("Location: verify_code.php");
            exit();
        } catch (Exception $e) {
            die("❌ Mail not sent. Error: {$mail->ErrorInfo}");
        }
    } else {
        die("❌ Mail not found.");
    }
} else {
    die("Invalid request.");
}
?>
