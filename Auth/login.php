<?php
session_start();
include "../Config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, Username, PasswordHash, Verified FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $storedHash = $row['PasswordHash'];
        $isLegacy = is_string($storedHash) && hash_equals($storedHash, (string)$password);
        $isValid = password_verify($password, $storedHash) || $isLegacy;

        if ($isValid) {
            // Automatically upgrade legacy plaintext passwords to standard bcrypt
            if (password_needs_rehash($storedHash, PASSWORD_DEFAULT) || $storedHash === $password) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = $conn->prepare("UPDATE users SET PasswordHash = ? WHERE UserID = ?");
                $up->bind_param("si", $newHash, $row['UserID']);
                $up->execute();
                $up->close();
            }

            $_SESSION['user'] = $row['Username'];
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $row['UserID'];
            header("Location: ../User/Pages/home.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Login - E - Books</title>
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: all 1s ease-out;
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .form-input {
            transition: border-color 0.3s, box-shadow 0.3s;
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
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .mobile-menu.open {
            max-height: 500px;
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <!-- Login Section -->
    <section class="pt-32 px-4 sm:px-6 relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-blue-500/5 to-purple-500/5 rounded-3xl blur-3xl animate-pulse z-[-1]"></div>

        <div class="container mx-auto flex items-center justify-center">
            <div class="w-full max-w-md backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl p-6 sm:p-8 md:p-10 scroll-animate">
                <div class="text-center mb-8">
                    <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                        Sign In to <span class="text-emerald-400">E - Books</span>
                    </h2>
                    <p class="text-slate-400 text-base sm:text-lg font-light">
                        Access your account and continue your reading journey.
                    </p>
                </div>

                <!-- Tabs -->
                <div class="flex justify-center mb-8">
                    <button class="tab-active px-4 sm:px-6 py-3 font-semibold text-base sm:text-lg">
                        Login
                    </button>
                    <a href="signups.php" class="px-4 sm:px-6 py-3 font-semibold text-base sm:text-lg text-slate-400 hover:text-emerald-400 transition-colors duration-300">
                        Sign Up
                    </a>
                </div>

                <!-- Login Form -->
                <form method="POST">
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-mail-line text-slate-400"></i>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input w-full pl-10 pr-3 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Enter your email"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="flex justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            <a href="forgot-password.php" class="text-sm text-emerald-400 hover:text-emerald-500 transition-colors duration-300">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none w-10 h-10">
                                <i class="ri-lock-line text-slate-400"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input w-full pl-10 pr-10 py-3 bg-slate-900/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none"
                                placeholder="Enter your password"
                                required
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password w-10 h-10">
                                <i class="ri-eye-line text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($error)): ?>
                        <p class="text-red-400 text-sm mb-4"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>

                    <button
                        type="submit"
                        class="form-button w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-3 sm:py-4 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105"
                    >
                        Sign In
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-slate-400 text-sm">
                        Don’t have an account? <a href="signups.php" class="text-emerald-400 hover:text-emerald-500 transition-colors duration-300">Sign Up</a>
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
            const password = document.getElementById("password");

            togglePassword.addEventListener("click", function () {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);
                togglePassword.innerHTML = type === "password"
                    ? '<i class="ri-eye-line text-slate-400"></i>'
                    : '<i class="ri-eye-off-line text-slate-400"></i>';
            });
        });
    </script>
</body>
</html>