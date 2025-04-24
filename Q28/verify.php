<!-- 28.Develop email verification application using PHP. -->

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

session_start();

$senderEmail = 'onkarj012@gmail.com';
$senderPassword = 'nekhllqqwokuhoipk';
$email = 'leoeccentric@gmail.com';
$showForm = false;

function sendOTP($email, $otp) {
    global $senderEmail, $senderPassword;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $senderEmail;
        $mail->Password   = $senderPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom($senderEmail, 'OTP Verification');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP is <b>$otp</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['send'])) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        if (sendOTP($email, $otp)) {
            $showForm = true;
        } else {
            echo "Failed to send OTP.";
        }
    }

    if (isset($_POST['verify'])) {
        $enteredOtp = $_POST['otp'];
        if ($_SESSION['otp'] == $enteredOtp) {
            echo "<h3 style='color:green;'>OTP Verified Successfully!</h3>";
        } else {
            echo "<h3 style='color:red;'>Invalid OTP.</h3>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email OTP Verification</title>
</head>
<body>
    <h2>Email OTP Verification</h2>

    <?php if (!$showForm && !isset($_POST['verify'])): ?>
        <form method="post">
            <input type="text" name="email" placeholder="Email"></input>
            <button type="submit" name="send">Send OTP</button>
        </form>
    <?php elseif ($showForm || isset($_POST['verify'])): ?>
        <form method="post">
            <input type="text" name="otp" placeholder="Enter OTP sent to <?php echo $_SESSION['email']; ?>" required>
            <button type="submit" name="verify">Verify</button>
        </form>
    <?php endif; ?>
</body>
</html>