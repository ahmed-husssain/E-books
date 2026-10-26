<?php
session_start();
include "../../Config/db.php";
require_once "../../Config/csrf.php";

// If user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Handle form update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    require_csrf_token();
    $oldEmail = $_SESSION['email'];

    $newEmail = $_POST['Email'];
    $fullName = $_POST['FullName'];
    $phone = $_POST['PhoneNumber'];
    $city = $_POST['City'];
    $state = $_POST['State'];

    $stmt = $conn->prepare("UPDATE users SET Email=?, FullName=?, PhoneNumber=?, City=?, State=? WHERE Email=?");
    $stmt->bind_param("ssssss", $newEmail, $fullName, $phone, $city, $state, $oldEmail);

    if ($stmt->execute()) {
        $_SESSION['email'] = $newEmail; // update session email
        $success = "Your information has been updated.";
    } else {
        $error = "Update failed: " . $stmt->error;
    }
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Account Settings - E-Books</title>
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
                    },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        "2xl": "24px",
                        "3xl": "32px",
                        full: "9999px",
                        button: "8px",
                    },
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #1e293b; /* slate-800 */
        }
        ::-webkit-scrollbar-thumb {
            background-color: #10b981; /* emerald-500 */
            border-radius: 8px;
            border: 2px solid #1e293b; /* matches track */
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: #059669; /* emerald-600 */
        }
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .form-input {
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }
        input:not([readonly]), button, a {
            pointer-events: auto !important;
            opacity: 1 !important;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <div class="px-6">
        <section class="pt-32 pb-24 px-6">
            <div class="container mx-auto">
                <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 rounded-2xl">
                    <div class="p-6">
                        <h2 class="text-4xl font-black mb-8 text-white text-center">Account <span class="text-emerald-400">Settings</span></h2>

                        <?php if (isset($success)): ?>
                            <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                            <div class="bg-red-500/20 text-red-400 border border-red-400/30 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                            <?php if ($user['Verified']) { ?>
                                <div class="bg-emerald-500/20 border-l-4 border-emerald-500 p-3 mb-6 flex items-center">
                                    <i class="ri-checkbox-circle-line text-emerald-400 text-lg mr-3"></i>
                                    <p class="text-sm text-emerald-400 font-medium">Your account is verified.</p>
                                </div>
                            <?php } else { ?>
                                <div class="bg-amber-500/20 border-l-4 border-amber-400 p-4 mb-8">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-error-warning-line text-amber-400 text-xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-amber-400">Please verify your account</h3>
                                            <div class="mt-1 text-sm text-amber-300">
                                                <p>Your account is not verified. Please check your email and click the verification link to access all features.</p>
                                            </div>
                                            <div class="mt-2">
                                                <button class="text-sm font-medium text-amber-400 hover:text-amber-500 underline">Resend verification email</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">Username</label>
                                        <input type="text" name="Username" value="<?php echo $user['Username']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                                        <input type="email" name="Email" value="<?php echo $user['Email']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">Full Name</label>
                                        <input type="text" name="FullName" value="<?php echo $user['FullName']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">Phone Number</label>
                                        <input type="tel" name="PhoneNumber" value="<?php echo $user['PhoneNumber']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">City</label>
                                        <input type="text" name="City" value="<?php echo $user['City']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-1">State</label>
                                        <input type="text" name="State" value="<?php echo $user['State']; ?>" class="form-input w-full px-4 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 rounded">
                                    </div>
                                </div>

                                <div class="border-t border-slate-700/50 mt-6 pt-6">
                                    <div class="flex items-center justify-between text-sm text-slate-400">
                                        <span>Account created on</span>
                                        <span class="font-medium"><?php echo $user['DateRegistered']; ?></span>
                                    </div>
                                </div>

                                <div class="flex justify-between mt-6">
                                    <a href="home.php" class="px-6 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:bg-slate-700/50 hover:text-emerald-400 rounded-button text-sm font-medium transition-all duration-300">Go Back</a>
                                    <div>
                                        <button type="reset" class="px-6 py-2 bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:bg-slate-700/50 hover:text-emerald-400 rounded-button text-sm font-medium transition-all duration-300 mr-3">Cancel</button>
                                        <button type="submit" name="update" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-button text-sm font-medium hover:scale-105 transition-all duration-300">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>