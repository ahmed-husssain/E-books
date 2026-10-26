<?php
session_start();

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot-password.php"); // Force back to forgot flow
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../Config/db.php";

    $email = $_SESSION['reset_email'] ?? '';
    $newPasswordRaw = $_POST['password'] ?? '';
    $hashedPassword = password_hash($newPasswordRaw, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET PasswordHash = ? WHERE Email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        unset($_SESSION['otp_verified'], $_SESSION['otp'], $_SESSION['reset_email']);
        $_SESSION['reset_message'] = 'Password updated successfully. Please log in.';
        $_SESSION['reset_error'] = false;
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - E - Books</title>
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
            overflow: hidden; /* Prevent scrolling */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6 py-8">
        <div class="glass-effect rounded-2xl p-8 animate-fade-in">
            <!-- Icon -->
            <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-white mb-2">Reset Your Password</h2>
                <p class="text-slate-300 text-sm">Enter your new password to complete the reset process.</p>
            </div>

            <!-- Session Messages -->
            <?php
            if (isset($_SESSION['reset_message'])) {
                echo '<div class="' . ($_SESSION['reset_error'] ? 'bg-red-900 border border-red-700 text-red-200' : 'bg-slate-800 border border-slate-700 text-emerald-400') . ' rounded-lg p-3 mb-6 text-sm text-center">' . htmlspecialchars($_SESSION['reset_message']) . '</div>';
                unset($_SESSION['reset_message'], $_SESSION['reset_error']);
            }
            ?>

            <!-- Form -->
            <form method="POST" id="resetForm">
                <div class="mb-6 relative">
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-2">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password" required
                        class="w-full p-3 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <i class="fas fa-eye toggle-password absolute right-3 top-10 text-slate-400 cursor-pointer" onclick="togglePassword()"></i>
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg" id="submitBtn">
                    Update Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center border-t border-slate-700 pt-4">
                <a href="login.php" class="text-emerald-400 hover:text-emerald-500 text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>