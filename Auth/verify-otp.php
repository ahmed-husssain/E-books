<?php
session_start();

if (!isset($_POST['otp'])) {
    header("Location: enter-otp.php");
    exit();
}

// 1. Ensure an active OTP session exists
if (empty($_SESSION['otp']) || empty($_SESSION['otp_expires'])) {
    $_SESSION['otp_error'] = "No active OTP request found. Please request a new code.";
    header("Location: forgot-password.php");
    exit();
}

// 2. Check 10-minute expiration timestamp
if (time() > $_SESSION['otp_expires']) {
    unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['otp_attempts']);
    $_SESSION['otp_error'] = "The verification code has expired. Please request a new one.";
    header("Location: enter-otp.php");
    exit();
}

// 3. Increment attempt counter and enforce max 5 attempts to prevent brute-force attacks
$_SESSION['otp_attempts'] = ($_SESSION['otp_attempts'] ?? 0) + 1;
if ($_SESSION['otp_attempts'] > 5) {
    unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['otp_attempts']);
    $_SESSION['otp_error'] = "Too many failed attempts. For your security, this code has been invalidated.";
    header("Location: forgot-password.php");
    exit();
}

$enteredOtp = trim($_POST['otp']);
$storedOtp  = (string)$_SESSION['otp'];

// 4. Timing-attack safe verification
if (strlen($enteredOtp) === 6 && hash_equals($storedOtp, $enteredOtp)) {
    unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['otp_attempts'], $_SESSION['otp_error']);
    $_SESSION['otp_verified'] = true;
    $_SESSION['reset_email'] = $_SESSION['email'] ?? '';
    header("Location: reset-password.php");
    exit();
} else {
    $remaining = 5 - $_SESSION['otp_attempts'];
    $_SESSION['otp_error'] = "Invalid verification code. " . ($remaining > 0 ? "$remaining attempts remaining." : "Code will be locked out.");
    header("Location: enter-otp.php");
    exit();
}
?>
