<?php
session_start();
include_once __DIR__ . "/../../Config/db.php";

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

// Redirect if no order details or success parameter
if (!isset($_GET['success']) || !isset($_SESSION['order_details']) || empty($_SESSION['order_details'])) {
    header("Location: cart.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../Auth/login.php");
    exit();
}

$order = $_SESSION['order_details'];
$delivery_date = date('F j, Y', strtotime($order['order_date'] . ' +3 days')); // e.g., July 20, 2025

// Determine if the order contains PDF items
$has_pdf = false;
$requires_shipping = false;
foreach ($order['items'] as $item) {
    if ($item['format'] === 'PDF') {
        $has_pdf = true;
    } else {
        $requires_shipping = true;
    }
}

// Precompute values for display and email
$subtotal = number_format($order['subtotal'], 2);
$shipping = $requires_shipping ? '$5.99' : '$0.00';
$total = number_format($order['total'], 2);
$address = htmlspecialchars($order['address'] ?? 'Digital delivery (no address required)');
$recipientEmail = $_SESSION['email'] ?? $order['email'] ?? '';
$userName = $_SESSION['user'] ?? $order['user_name'] ?? 'Customer';

// Send thank you email with invoice
if (!empty($recipientEmail)) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['GMAIL_USERNAME'];
        $mail->Password = $_ENV['GMAIL_APP_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('thezulekhacollection@gmail.com', 'E - Books');
        $mail->addAddress($recipientEmail, $userName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Thank You for Your Order - E - Books';
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
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca3b5d78b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=80" alt="Book Icon" style="width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%;">
                            <h1 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 800; font-size: 30px; color: #34d399; margin: 0 0 8px;">E - Books</h1>
                            <div style="width: 100px; height: 2px; background: linear-gradient(to right, transparent, #10b981, transparent); margin: 24px auto;"></div>
                        </td>
                    </tr>
                    <!-- Greeting -->
                    <tr>
                        <td style="text-align: center; margin-bottom: 32px;">
                            <h2 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 600; font-size: 24px; color: #ffffff; margin: 0 0 8px;">Thank You, $userName!</h2>
                            <p style="font-size: 16px; color: #cbd5e1; margin: 0 0 24px; line-height: 1.5;">
                                Your order has been received at <span style="color: #34d399;">E - Books</span>. Below is your invoice with the order details.
                            </p>
                        </td>
                    </tr>
                    <!-- Order Summary -->
                    <tr>
                        <td style="text-align: left; margin-bottom: 32px;">
                            <h3 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 600; font-size: 20px; color: #ffffff; margin: 0 0 16px;">Order Summary</h3>
                            <table role="presentation" cellpadding="10" cellspacing="0" style="width: 100%; background-color: #1e293b; border: 1px solid #334155; border-radius: 8px; margin-bottom: 24px;">
                                <tr style="background-color: #334155;">
                                    <th style="color: #34d399; font-size: 14px; font-weight: 600; text-align: left;">Item</th>
                                    <th style="color: #34d399; font-size: 14px; font-weight: 600; text-align: left;">Format</th>
                                    <th style="color: #34d399; font-size: 14px; font-weight: 600; text-align: center;">Qty</th>
                                    <th style="color: #34d399; font-size: 14px; font-weight: 600; text-align: right;">Price</th>
                                </tr>
HTML;
        // Append order items
        foreach ($order['items'] as $item) {
            $qty = $item['format'] === 'PDF' ? 1 : $item['qty'];
            $item_title = htmlspecialchars($item['title']);
            $item_format = ucfirst($item['format']);
            $item_price = number_format($item['price'] * $qty, 2);
            $mail->Body .= <<<HTML
                                <tr>
                                    <td style="color: #cbd5e1; font-size: 14px;">$item_title</td>
                                    <td style="color: #cbd5e1; font-size: 14px;">$item_format</td>
                                    <td style="color: #cbd5e1; font-size: 14px; text-align: center;">$qty</td>
                                    <td style="color: #cbd5e1; font-size: 14px; text-align: right;">\$$item_price</td>
                                </tr>
HTML;
        }
        $mail->Body .= <<<HTML
                            </table>
                            <table role="presentation" cellpadding="5" cellspacing="0" style="width: 100%; margin-bottom: 24px;">
                                <tr>
                                    <td style="color: #cbd5e1; font-size: 14px;">Subtotal:</td>
                                    <td style="color: #cbd5e1; font-size: 14px; text-align: right;">\$$subtotal</td>
                                </tr>
                                <tr>
                                    <td style="color: #cbd5e1; font-size: 14px;">Shipping:</td>
                                    <td style="color: #34d399; font-size: 14px; text-align: right;">$shipping</td>
                                </tr>
                                <tr>
                                    <td style="color: #ffffff; font-size: 16px; font-weight: 600;">Total:</td>
                                    <td style="color: #34d399; font-size: 16px; font-weight: 600; text-align: right;">\$$total</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
HTML;
        // Append delivery information only for non-PDF orders
        if ($requires_shipping) {
            $mail->Body .= <<<HTML
                    <!-- Delivery Information -->
                    <tr>
                        <td style="text-align: left; margin-bottom: 32px;">
                            <h3 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 600; font-size: 20px; color: #ffffff; margin: 0 0 16px;">Delivery Information</h3>
                            <p style="font-size: 14px; color: #cbd5e1; margin: 0 0 8px;">
                                <strong>Estimated Delivery Date:</strong> $delivery_date
                            </p>
                            <p style="font-size: 14px; color: #cbd5e1; margin: 0 0 24px;">
                                <strong>Shipping Address:</strong> $address
                            </p>
                        </td>
                    </tr>
HTML;
        }
        $mail->Body .= <<<HTML
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

        // Plain text version
        $mail->AltBody = "Dear $userName,\n\nThank you for your order at E - Books! Below is your invoice with the order details.\n\nOrder Summary:\n" .
                         implode("\n", array_map(function($item) {
                             $qty = $item['format'] === 'PDF' ? 1 : $item['qty'];
                             return "- " . htmlspecialchars($item['title']) . " (" . ucfirst($item['format']) . ", Qty: $qty): $" . number_format($item['price'] * $qty, 2);
                         }, $order['items'])) .
                         "\n\nSubtotal: $$subtotal" .
                         "\nShipping: $shipping" .
                         "\nTotal: $$total";
        if ($requires_shipping) {
            $mail->AltBody .= "\n\nDelivery Information:\nShipping Address: $address\nEstimated Delivery: $delivery_date";
        }
        $mail->AltBody .= "\n\n© 2025 E - Books";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - E - Books</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            950: '#020617',
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            500: '#64748b',
                            400: '#94a3b8',
                            300: '#cbd5e1'
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #020617 0%, #1e293b 100%);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            overflow: hidden;
        }
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-effect rounded-2xl p-8 animate-fade-in">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    <i class="fas fa-check-circle mr-4 text-emerald-400"></i>Order Received!
                </h1>
                <p class="text-xl text-slate-400">Thank you, <?php echo htmlspecialchars($order['user_name']); ?>, for your purchase!</p>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-1 <?php echo $requires_shipping ? 'md:grid-cols-2' : ''; ?> gap-8">
                <!-- Order Summary -->
                <div>
                    <h2 class="text-2xl font-bold text-white mb-4">
                        <i class="fas fa-receipt mr-2"></i>Order Summary
                    </h2>
                    <div class="space-y-4 mb-6">
                        <?php foreach ($order['items'] as $item) {
                            $qty = $item['format'] === 'PDF' ? 1 : $item['qty'];
                        ?>
                            <div class="flex justify-between items-center py-2">
                                <div class="flex-1">
                                    <h4 class="text-white font-medium"><?php echo htmlspecialchars($item['title']); ?></h4>
                                    <p class="text-slate-400 text-sm">Format: <?php echo ucfirst($item['format']); ?> | Qty: <?php echo $qty; ?></p>
                                </div>
                                <span class="text-white font-semibold">$<?php echo number_format($item['price'] * $qty, 2); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="border-t border-slate-700/50 pt-4 space-y-2">
                        <div class="flex justify-between text-white">
                            <span>Subtotal:</span>
                            <span>$<?php echo $subtotal; ?></span>
                        </div>
                        <div class="flex justify-between text-white">
                            <span>Shipping:</span>
                            <span><?php echo $shipping; ?></span>
                        </div>
                        <div class="border-t border-slate-700/50 pt-2 flex justify-between text-xl font-bold text-white">
                            <span>Total:</span>
                            <span>$<?php echo $total; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information (only for non-PDF orders) -->
                <?php if ($requires_shipping): ?>
                <div>
                    <h2 class="text-2xl font-bold text-white mb-4">
                        <i class="fas fa-truck mr-2"></i>Delivery Information
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-slate-400 text-sm">Estimated Delivery Date</p>
                            <p class="text-white font-semibold"><?php echo $delivery_date; ?></p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-sm">Shipping Address</p>
                            <p class="text-white font-semibold"><?php echo $address; ?></p>
                        </div>
                        <div class="map-container" style="height: 200px;">
                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAOVYRIgupAurZup5y1PRh8Ismb1A3lLao&q=<?php echo urlencode($order['address'] ?? ''); ?>" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="books.php" class="bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                    Continue Shopping
                </a>
                <a href="orderhistory.php" class="bg-slate-800/30 text-slate-300 hover:bg-slate-700/50 hover:text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300">
                    View Order Details
                </a>
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center">
                <p class="text-slate-400 text-sm">
                    <?php echo $has_pdf && !$requires_shipping ? 'Your digital items will be available for download in your <a href="orderhistory.php" class="text-emerald-400 hover:underline">order history</a> upon confirmation.' : 'Your order will be processed soon. Check your <a href="orderhistory.php" class="text-emerald-400 hover:underline">order history</a> for details.'; ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>