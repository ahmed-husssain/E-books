<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (Username, Email, PasswordHash, Role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: admin.users.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New User | E - Books</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
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
                    },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        '2xl': "24px",
                        '3xl': "32px",
                        full: "9999px",
                        button: "8px",
                    }
                }
            }
        }
    </script>
    <style>
        #sidebar.collapsed ~ #main-content {
            margin-left: 4rem; /* Matches collapsed sidebar width (w-16) */
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem; /* Matches expanded sidebar width (w-64) */
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
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.3s ease;
        }
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .mobile-menu.open {
            max-height: 500px;
        }
        #sidebar.collapsed {
            width: 64px;
        }
        #main-content {
            margin-left: 256px;
            transition: margin-left 0.3s ease;
        }
        #main-content.expanded {
            margin-left: 64px;
        }
        #sidebar.collapsed .nav-text,
        #sidebar.collapsed .logo-text {
            display: none;
        }
        #sidebar.collapsed #toggle-sidebar {
            width: 64px;
            margin-left: 2px;
        }
        .form-input {
            transition: all 0.3s ease;
            border: none;
            border-bottom: 2px solid #475569;
            background: transparent;
            outline: none;
        }
        .form-input:focus {
            border-color: #34d399;
        }
        .submit-button {
            background: #10b981;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
</head>
<body class="bg-slate-950 text-white flex min-h-screen">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="min-h-screen flex-1">
            <!-- Header -->
            <!-- Page Content -->
            <div class="pt-24 p-6">
                <div class="container mx-auto">
                    <div class="backdrop-blur-2xl bg-slate-800/30 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 p-8 max-w-md mx-auto scroll-animate">
                        <h1 class="text-2xl font-bold text-white mb-6 text-center">Add New User</h1>
                        <form method="POST" class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block mb-1 font-medium text-slate-300">Full Name</label>
                                <input type="text" name="name" required class="form-input w-full px-3 py-2 rounded-button text-white" />
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-slate-300">Email</label>
                                <input type="email" name="email" required class="form-input w-full px-3 py-2 rounded-button text-white" />
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-slate-300">Password</label>
                                <input type="password" name="password" required class="form-input w-full px-3 py-2 rounded-button text-white" />
                            </div>
                            <div>
                                <label class="block mb-1 font-medium text-slate-300">Role</label>
                                <select name="role" class="form-input w-full px-3 py-2 rounded-button text-white">
                                    <option value="User" selected>User</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <input type="submit" value="Add User" class="submit-button px-6 py-2 text-white rounded-button font-medium hover:bg-emerald-600" />
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <a href="admin.users.php" class="text-emerald-400 hover:text-emerald-500 text-sm font-medium">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
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
    </script>
</body>
</html>