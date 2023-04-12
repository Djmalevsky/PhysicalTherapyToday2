<?php
// Include code to connect to the database
require_once 'db_connect.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $sql = "SELECT * FROM therapist_profiles WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token and store it in the database along with the user's email and expiration time
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); // Set the expiration time to 1 hour from now

        $sql = "INSERT INTO password_reset_requests (email, token, expires) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        // Send an email to the user with the password reset link containing the unique token
        $resetLink = "http://localhost/SignUpPageshtml/php/reset-password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $resetLink;

        // Instantiate PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = 'localhost'; // Set the SMTP server to send through
            $mail->SMTPAuth   = false; // Enable SMTP authentication
            //$mail->Username   = 'davidsecond359@gmail.com'; // SMTP username
           // $mail->Password   = 'Pp@123455'; // SMTP password
            $mail->SMTPSecure = false; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            // Recipients
$mail->setFrom('noreply@localhost.com', 'Physical Therapist Today');
$mail->addAddress($email); // Make sure $email contains a valid email address with the 'localhost' domain

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            echo "Password reset link sent! Check your email.";
        } catch (Exception $e) {
            echo "Error: Unable to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: Email not found!";
    }
}
?>
