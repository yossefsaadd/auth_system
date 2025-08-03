<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mysqli = new mysqli("localhost", "root", "", "auth_system");
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $name  = $_POST["name"] ?? '';
    $code  = rand(100000, 999999);

    $_SESSION['reg_email'] = $email;
    $_SESSION['reg_name'] = $name;
    $_SESSION['reg_code'] = $code;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'zamzor.company@gmail.com';
        $mail->Password   = 'noai dppj spjc ofwf'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('your_email@gmail.com', 'Zamzor System');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Your activation code';
        $mail->Body    = "Your activation code is: <b>$code</b>";

        $mail->send();


        header("Location: step2_verify.php");
        exit;
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>
