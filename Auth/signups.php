<?php
session_start();
include_once __DIR__ . "/../Config/db.php";

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require_once __DIR__ . "/../vendor/autoload.php";
}
if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenvPath = dirname(__DIR__);
    if (file_exists($dotenvPath . '/.env')) {
        $dotenv = \Dotenv\Dotenv::createImmutable($dotenvPath);
        $dotenv->safeLoad();
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $Username = trim($_POST['Username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if (empty($Username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT UserID FROM users WHERE Email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "An account with this email already exists.";
        } else {
            $token = bin2hex(random_bytes(16));
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (Username, Email, PasswordHash, token) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $Username, $email, $passwordHash, $token);

            if ($stmt->execute()) {
                $name = htmlspecialchars($Username);
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();                         
                    $mail->Host       = 'smtp.gmail.com';   
                    $mail->SMTPAuth   = true;     
                    $mail->Username = $_ENV['GMAIL_USERNAME'];
                    $mail->Password = $_ENV['GMAIL_APP_PASSWORD'];
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;     
                    $mail->setFrom('thezulekhacollection@gmail.com', 'E-Books');
                    $mail->addAddress($email, $name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification';
                    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                    $base_url = (strpos($host, 'localhost') !== false) ? "$proto://$host/EBook" : "$proto://$host";
                    $verificationUrl = "$base_url/Auth/verify.php?token=$token";
                    $mail->Body = <<<HTML
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Email Verification - E - Books</title>
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
                                                <h2 style="font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif; font-weight: 600; font-size: 24px; color: #ffffff; margin: 0 0 8px;">Hello, $Username</h2>
                                            </td>
                                        </tr>
                                        
                                        <!-- Content -->
                                        <tr>
                                            <td style="text-align: center; margin-bottom: 32px;">
                                                <table role="presentation" cellpadding="0" cellspacing="0" style="width: 100%;">
                                                    <tr>
                                                        <td>
                                                            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=192&q=80" alt="Welcome Illustration" style="max-width: 400px; width: 100%; height: 192px; border-radius: 8px; margin-bottom: 24px;">
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style="font-size: 16px; color: #cbd5e1; margin: 0 0 24px; line-height: 1.5;">
                                                    Thank you for joining <span style="color: #34d399;">E - Books</span>! We're thrilled to have you in our community of book lovers. To start exploring our vast collection and personalized recommendations, please verify your email address.
                                                </p>
                                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 24px;">
                                                    <tr>
                                                        <td style="background-color: #10b981; border-radius: 8px;">
                                                            <a href="$verificationUrl" style="display: inline-block; padding: 16px 32px; color: #ffffff; font-weight: 600; font-size: 16px; text-decoration: none; border-radius: 8px;">Verify Your Email</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p style="font-size: 14px; color: #94a3b8; margin: 0 0 24px;">
                                                    If you didn’t create an account, you can safely ignore this email.
                                                </p>
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
                    $mail->send();
                    header("Location: Login.php");
                    exit();
                } catch (Exception $e) {
                    $error = "Email sending failed: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Something went wrong: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - E - Books</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        :root {
            --ease-apple: cubic-bezier(0.16, 1, 0.3, 1);
        }
        button:active, a:active {
            transform: scale(0.96) !important;
            transition: transform 80ms var(--ease-apple) !important;
        }
        button:focus-visible, a:focus-visible, input:focus-visible {
            outline: 2px solid #10b981 !important;
            outline-offset: 2px !important;
        }
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms var(--ease-apple), transform 280ms var(--ease-apple);
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .form-input {
            transition: border-color 0.2s var(--ease-apple), box-shadow 0.2s var(--ease-apple);
        }
        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .tab-active {
            color: #10b981;
            border-bottom: 2px solid #10b981;
        }
        .form-button {
            position: relative;
            overflow: hidden;
        }
        .form-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: 0;
        }
        .form-button:hover::before {
            left: 100%;
        }
        .toggle-password {
            cursor: pointer;
        }
        .password-strength {
            height: 4px;
            transition: width 0.3s ease;
        }
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                transition-duration: 150ms !important;
            }
            .scroll-animate, .form-button {
                transform: none !important;
                opacity: 1 !important;
            }
            button:active, a:active {
                transform: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <!-- Sign Up Section -->
    <section class="pt-8 px-4 sm:px-6 relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-blue-500/5 to-purple-500/5 rounded-3xl blur-3xl animate-pulse z-[-1]"></div>

        <div class="container mx-auto flex items-center justify-center">
            <div class="w-full max-w-md backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl p-6 sm:p-8 md:p-10 scroll-animate">
                <div class="text-center mb-8">
                    <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                        Join <span class="text-emerald-400">E - Books</span>
                    </h2>
                    <p class="text-slate-400 text-base sm:text-lg font-light">
                        Create an account to start your reading journey.
                    </p>
                </div>

                <!-- Tabs -->
                <div class="flex justify-center mb-8">
                    <a href="login.php" class="px-4 sm:px-6 py-3 font-semibold text-base sm:text-lg text-slate-400 hover:text-emerald-400 transition-colors duration-300">
                        Login
                    </a>
                    <button class="tab-active px-4 sm:px-6 py-3 font-semibold text-base sm:text-lg">
                        Sign Up
                    </button>
                </div>

                <!-- Sign Up Form -->
                <form method="POST">
                    <div class="mb-5">
                        <label for="Username" class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-user-line text-slate-400"></i>
                            </div>
                            <input
                                type="text"
                                id="Username"
                                name="Username"
                                class="form-input w-full pl-10 pr-3 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Enter your full name"
                                value="<?php echo htmlspecialchars($_POST['Username'] ?? ''); ?>"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="signup-email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-mail-line text-slate-400"></i>
                            </div>
                            <input
                                type="email"
                                id="signup-email"
                                name="email"
                                class="form-input w-full pl-10 pr-3 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Enter your email"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="signup-password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-lock-line text-slate-400"></i>
                            </div>
                            <input
                                type="password"
                                id="signup-password"
                                name="password"
                                class="form-input w-full pl-10 pr-10 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Create a password"
                                required
                            />
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 toggle-password min-w-[44px] min-h-[44px]" aria-label="Toggle password visibility">
                                <i class="ri-eye-line text-slate-400"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-slate-700/50 rounded-full h-1 mb-1">
                                <div id="password-strength" class="password-strength bg-red-400 rounded-full h-1" style="width: 0%"></div>
                            </div>
                            <p id="password-strength-text" class="text-xs text-slate-400">Password strength: Too weak</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="confirm-password" class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-lock-line text-slate-400"></i>
                            </div>
                            <input
                                type="password"
                                id="confirm-password"
                                name="confirm-password"
                                class="form-input w-full pl-10 pr-3 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Confirm your password"
                                required
                            />
                        </div>
                    </div>

                    <?php if (!empty($error)): ?>
                        <p class="text-red-400 text-sm mb-4"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>

                    <button
                        type="submit"
                        name="signup"
                        class="form-button w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-3 sm:py-4 min-h-[48px] text-base sm:text-lg font-semibold rounded-xl transition-all duration-200 btn-press"
                    >
                        Create Account
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-slate-400 text-sm">
                        Already have an account? <a href="login.php" class="text-emerald-400 hover:text-emerald-500 transition-colors duration-300">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });

        // Password toggle
        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = document.querySelector(".toggle-password");
            const password = document.getElementById("signup-password");

            togglePassword.addEventListener("click", function () {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);
                togglePassword.innerHTML = type === "password"
                    ? '<i class="ri-eye-line text-slate-400"></i>'
                    : '<i class="ri-eye-off-line text-slate-400"></i>';
            });

            // Password strength indicator
            const passwordInput = document.getElementById("signup-password");
            const strengthBar = document.getElementById("password-strength");
            const strengthText = document.getElementById("password-strength-text");

            passwordInput.addEventListener("input", function () {
                const value = passwordInput.value;
                let strength = 0;

                if (value.length >= 8) strength += 25;
                if (/[a-z]/.test(value)) strength += 25;
                if (/[A-Z]/.test(value)) strength += 25;
                if (/[0-9]/.test(value) || /[^a-zA-Z0-9]/.test(value)) strength += 25;

                strengthBar.style.width = strength + "%";

                if (strength <= 25) {
                    strengthBar.className = "password-strength bg-red-400 rounded-full h-1";
                    strengthText.textContent = "Password strength: Too weak";
                    strengthText.className = "text-xs text-red-400";
                } else if (strength <= 50) {
                    strengthBar.className = "password-strength bg-orange-400 rounded-full h-1";
                    strengthText.textContent = "Password strength: Weak";
                    strengthText.className = "text-xs text-orange-400";
                } else if (strength <= 75) {
                    strengthBar.className = "password-strength bg-yellow-400 rounded-full h-1";
                    strengthText.textContent = "Password strength: Good";
                    strengthText.className = "text-xs text-yellow-400";
                } else {
                    strengthBar.className = "password-strength bg-emerald-400 rounded-full h-1";
                    strengthText.textContent = "Password strength: Strong";
                    strengthText.className = "text-xs text-emerald-400";
                }
            });

            // Client-side validation for confirm password
            const form = document.querySelector("form");
            const confirmpassword = document.getElementById("confirm-password");

            form.addEventListener("submit", function (event) {
                if (password.value !== confirmpassword.value) {
                    event.preventDefault();
                    const errorDiv = document.createElement("p");
                    errorDiv.className = "text-red-400 text-sm mb-4";
                    errorDiv.textContent = "Passwords do not match.";
                    form.insertBefore(errorDiv, form.querySelector("button"));
                }
            });
        });
    </script>
</body>
</html>