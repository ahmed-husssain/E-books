<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['user'])) {
    die("Session variables not set.");
}

if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
    require_once __DIR__ . "/../../vendor/autoload.php";
}
if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenvPath = dirname(__DIR__, 2);
    if (file_exists($dotenvPath . '/.env')) {
        $dotenv = \Dotenv\Dotenv::createImmutable($dotenvPath);
        $dotenv->safeLoad();
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$recipientEmail = $_SESSION['email'];
$userName = $_SESSION['user'];
$mail = new PHPMailer();
$mail->isSMTP();                         
$mail->Host       = 'smtp.gmail.com';   
$mail->SMTPAuth   = true;     
$mail->Username = $_ENV['GMAIL_USERNAME'];
$mail->Password = $_ENV['GMAIL_APP_PASSWORD']; 
$mail->SMTPSecure = 'tls';
$mail->Port       = 587;     
$mail->setFrom('thezulekhacollection@gmail.com', 'E - Books');
$mail->addAddress($recipientEmail, $userName);
$mail->isHTML(true);
$mail->Subject = 'Story Submission Confirmation';
$mail->Body = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #020617; font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #cbd5e1;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%; min-height: 100vh; background-color: #020617; padding: 16px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #0f172a; border: 1px solid #334155; border-radius: 16px; padding: 24px; text-align: center;">
                    <!-- Header -->
                    <tr>
                        <td>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td>
                                        <img src="https://images.unsplash.com/photo-1512820790803-83ca3b5d78b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=80" alt="Book Icon" style="width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%;">
                                    </td>
                                </tr>
                            </table>
                            <h1 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 800; font-size: 30px; color: #34d399; margin: 0 0 8px;">E - Books</h1>
                            <div style="width: 100px; height: 2px; background: linear-gradient(to right, transparent, #10b981, transparent); margin: 24px auto;"></div>
                        </td>
                    </tr>
                    
                    <!-- Greeting -->
                    <tr>
                        <td style="text-align: center; margin-bottom: 32px;">
                            <h2 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 600; font-size: 24px; color: #ffffff; margin: 0 0 8px;">Thank You, $userName!</h2>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="text-align: center; margin-bottom: 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td>
                                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=192&q=80" alt="Story Submission Illustration" style="max-width: 400px; width: 100%; height: 192px; border-radius: 8px; margin-bottom: 24px;">
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size: 16px; color: #cbd5e1; margin: 0 0 24px; line-height: 1.5;">
                                Your story has been successfully submitted for the competition at <span style="color: #34d399;">E - Books</span>. We appreciate your participation and look forward to reviewing your creative work!
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 24px; background-color: #1e293b; border: 1px solid #334155; border-radius: 8px; padding: 15px;">
                                <tr>
                                    <td style="font-size: 14px; color: #94a3b8;">
                                        Our team will review your submission, and we’ll notify you with the results soon. Stay tuned!
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td>
                            <div style="width: 100px; height: 2px; background: linear-gradient(to right, transparent, #10b981, transparent); margin: 24px auto;"></div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="text-align: center; color: #64748b; font-size: 12px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 16px;">
                                <tr>
                                    <td style="padding: 0 12px;">
                                        <a href="#" style="display: inline-block; text-decoration: none;">
                                            <img src="https://img.icons8.com/ios-filled/24/64748b/facebook.png" alt="Facebook" style="width: 24px; height: 24px;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 12px;">
                                        <a href="#" style="display: inline-block; text-decoration: none;">
                                            <img src="https://img.icons8.com/ios-filled/24/64748b/twitter.png" alt="Twitter" style="width: 24px; height: 24px;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 12px;">
                                        <a href="#" style="display: inline-block; text-decoration: none;">
                                            <img src="https://img.icons8.com/ios-filled/24/64748b/instagram-new.png" alt="Instagram" style="width: 24px; height: 24px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 0;">© 2025 E - Books. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
if ($mail->send()) {
    header("Location: ../Pages/competitions.php");
    exit;
}

?>
