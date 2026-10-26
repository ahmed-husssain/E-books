<?php
include "../../Config/db.php";

$isLoggedIn = isset($_SESSION['email']);
$isVerified = true;

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT Verified,Role FROM users WHERE Email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $isVerified = $row['Verified'];
    }
    $stmt->close();
}
?>

<nav class="fixed top-0 w-full z-50 backdrop-blur-2xl bg-slate-900/90 border-b border-slate-800/50 shadow-2xl">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <a href="home.php">
            <div class="flex items-center">
                <img src="../../Logos/logo_white.png"
                    alt="Logo"
                    class="[width:180px] object-contain cursor-pointer" />
            </div>
            </a>
            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-10">
                <a href="../Pages/home.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="../Pages/books.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    Books
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="../Pages/category.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    Categories
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="../Pages/competitions.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    Competition
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="../Pages/About.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    About
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="../Pages/contact.php" class="relative text-slate-300 hover:text-emerald-400 transition-all duration-300 font-medium group">
                    Contact
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-400 group-hover:w-full transition-all duration-300"></span>
                </a>
            </div>

            <!-- Desktop User Box & Cart -->
            <div class="hidden lg:flex items-center space-x-4">
                <?php if ($isLoggedIn): ?>
                    <a href="cart.php" class="relative inline-block p-3 text-slate-300 hover:text-emerald-400 transition-colors duration-300 text-xl">
                        <!-- Cart Icon using Remix Icon -->
                        <i class="ri-shopping-cart-line"></i>

                        <!-- Cart Count Badge -->
                        <?php if (!empty($_SESSION['cart'])): ?>
                            <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-emerald-500 text-white text-xs font-bold flex items-center justify-center shadow-md ring-1 ring-white">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- Desktop User Dropdown -->
                    <div class="relative dropdown">
                        <div id="userMenuBtn" class="inline-block text-xl text-slate-300 hover:text-emerald-400 transition-colors duration-300 relative">
                            <i class="ri-user-3-line"></i>
                            <i class="ri-arrow-down-s-line text-base"></i>
                        </div>

                        <!-- Dropdown Menu -->  
                        <div id="userMenu" class="dropdown-menu absolute right-0 top-16 w-64 backdrop-blur-2xl bg-slate-800/95 border border-slate-700/50 rounded-2xl shadow-2xl hidden opacity-0 translate-y-2">
                            <div class="p-4 space-y-2">
                                <div class="flex items-center border-b border-slate-700/50 pb-3 mb-2">
                                    <div class="ml-3">
                                        <p class="text-slate-300 font-medium"><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                                    </div>
                                </div>
                                <a href="AccountDetails.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                    Account Settings
                                </a>
                                <a href="orderhistory.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                    Order History
                                </a>
                                <a href="Mybooks.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                    My Books
                                </a>
                                <?php if ($row['Role'] == 'Admin'): ?>
                                    <a href="../../Admin/Admin/admin.php" class="block px-4 py-3 rounded-xl hover:bg-emerald-600/50 hover:text-white transition-all duration-200 text-emerald-400 font-medium">
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <?php if (!$isVerified): ?>
                                <p id="pcverifiybtn" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                        Please Verify
                                </p>
                                <?php endif; ?>
                                <a href="contact.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                    Contact & Support
                                </a>
                                <hr class="border-slate-700/50 my-3">
                                <a href="../../Auth/logout.php" class="block px-4 py-3 rounded-xl hover:bg-red-500/10 transition-all duration-200 text-red-400 hover:text-red-300 font-medium">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="../../Auth/login.php" class="backdrop-blur-xl bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:bg-slate-700/50 hover:text-emerald-400 hover:border-emerald-500/50 transition-all duration-300 px-4 py-2 rounded-lg font-medium">
                        Sign In
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button id="mobile-menu-btn" class="text-slate-300 hover:text-emerald-400 hover:bg-slate-800/50 p-2 rounded">
                    <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden mt-4 backdrop-blur-2xl bg-slate-800/95 border-slate-700/50 rounded-2xl shadow-2xl">
            <div class="space-y-4">
                <a href="../Pages/home.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">Home</a>
                <a href="../Pages/books.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">Books</a>
                <a href="../Pages/category.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">Categories</a>
                <a href="../Pages/competitions.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">Competition</a>
                <a href="../Pages/About.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">About</a>
                <a href="../Pages/contact.php" class="block text-slate-300 hover:text-emerald-400 transition-colors duration-300 font-medium py-2">Contact</a>
                <hr class="border-slate-700/50 my-4">
                <div class="flex flex-col space-y-4">
                    <?php if ($isLoggedIn): ?>
                        <a href="cart.php" class="backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 text-slate-300 px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0h9"></path>
                            </svg>
                            Cart
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <span class="ml-2 w-5 h-5 rounded-full bg-emerald-500 text-white text-xs flex items-center justify-center animate-pulse">
                                    <?php echo count($_SESSION['cart']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <!-- Mobile User Dropdown -->
                        <div class="relative">
                            <div class="backdrop-blur-xl bg-slate-800/50 border border-slate-700/50 rounded-2xl p-2 shadow-xl cursor-pointer hover:bg-slate-700/50 hover:border-emerald-500/50 transition-all duration-300 flex items-center space-x-2" id="mobileUserMenuBtn">
                                <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-emerald-600 text-white text-sm font-semibold rounded-full flex items-center justify-center">
                                    <?php echo htmlspecialchars(strtoupper(substr($_SESSION['user'], 0, 2))); ?>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 hover:text-emerald-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <!-- Mobile Dropdown Menu -->
                            <div id="mobileUserMenu" class="dropdown-menu absolute left-0 top-12 w-full backdrop-blur-2xl bg-slate-800/95 border border-slate-700/50 rounded-2xl shadow-2xl hidden opacity-0 translate-y-2">
                                <div class="p-4 space-y-2">
                                    <div class="flex items-center border-b border-slate-700/50 pb-3 mb-2">
                                        <div class="ml-3">
                                            <p class="text-slate-300 font-medium"><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                                        </div>
                                    </div>
                                    <a href="AccountDetails.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                        Account Settings
                                    </a>
                                    <?php if (!$isVerified): ?>
                                    <p class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                            Email Sent
                                    </p>
                                    <?php endif; ?>
                                    <a href="#" class="block px-4 py-3 rounded-xl hover:bg-slate-700/50 hover:text-emerald-400 transition-all duration-200 text-slate-300 font-medium">
                                        Contact & Support
                                    </a>
                                    <hr class="border-slate-700/50 my-3">
                                    <a href="../../Auth/logout.php" class="block px-4 py-3 rounded-xl hover:bg-red-500/10 transition-all duration-200 text-red-400 hover:text-red-300 font-medium">
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="../../Auth/login.php" class="backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 text-slate-300 px-4 py-2 rounded-lg font-medium">
                            Sign In
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            const mobileMenuBtn = document.getElementById("mobile-menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            const menuIcon = document.getElementById("menu-icon");
            const closeIcon = document.getElementById("close-icon");
            const userMenuBtn = document.getElementById("userMenuBtn");
            const userMenu = document.getElementById("userMenu");
            const mobileUserMenuBtn = document.getElementById("mobileUserMenuBtn");
            const mobileUserMenu = document.getElementById("mobileUserMenu");
            let isMobileMenuOpen = false;
            let isUserMenuOpen = false;
            let isMobileUserMenuOpen = false;

            // Ensure dropdowns are hidden on page load
            if (userMenu) {
                userMenu.classList.add("hidden", "opacity-0");
            }
            if (mobileUserMenu) {
                mobileUserMenu.classList.add("hidden", "opacity-0");
            }

            // Mobile Menu Toggle
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    isMobileMenuOpen = !isMobileMenuOpen;
                    if (isMobileMenuOpen) {
                        mobileMenu.classList.add("open");
                        mobileMenu.classList.add("p-6");
                        menuIcon.classList.add("hidden");
                        closeIcon.classList.remove("hidden");
                    } else {
                        mobileMenu.classList.remove("open");
                        mobileMenu.classList.remove("p-6");
                        menuIcon.classList.remove("hidden");
                        closeIcon.classList.add("hidden");
                    }
                });

                document.addEventListener("click", function (e) {
                    if (isMobileMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        isMobileMenuOpen = false;
                        mobileMenu.classList.remove("open");
                        menuIcon.classList.remove("hidden");
                        closeIcon.classList.add("hidden");
                    }
                });
            }

            // Desktop User Menu Toggle
            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    isUserMenuOpen = !isUserMenuOpen;
                    if (isUserMenuOpen) {
                        userMenu.classList.remove("hidden", "opacity-0", "translate-y-2");
                        userMenu.classList.add("opacity-100", "translate-y-0");
                    } else {
                        userMenu.classList.add("hidden", "opacity-0", "translate-y-2");
                        userMenu.classList.remove("opacity-100", "translate-y-0");
                    }
                });

                document.addEventListener("click", function (e) {
                    if (isUserMenuOpen && !userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        isUserMenuOpen = false;
                        userMenu.classList.add("hidden", "opacity-0", "translate-y-2");
                        userMenu.classList.remove("opacity-100", "translate-y-0");
                    }
                });
            }

            // Mobile User Menu Toggle
            if (mobileUserMenuBtn && mobileUserMenu) {
                mobileUserMenuBtn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    isMobileUserMenuOpen = !isMobileUserMenuOpen;
                    if (isMobileUserMenuOpen) {
                        mobileUserMenu.classList.remove("hidden", "opacity-0", "translate-y-2");
                        mobileUserMenu.classList.add("opacity-100", "translate-y-0");
                    } else {
                        mobileUserMenu.classList.add("hidden", "opacity-0", "translate-y-2");
                        mobileUserMenu.classList.remove("opacity-100", "translate-y-0");
                    }
                });

                document.addEventListener("click", function (e) {
                    if (isMobileUserMenuOpen && !mobileUserMenu.contains(e.target) && !mobileUserMenuBtn.contains(e.target)) {
                        isMobileUserMenuOpen = false;
                        mobileUserMenu.classList.add("hidden", "opacity-0", "translate-y-2");
                        mobileUserMenu.classList.remove("opacity-100", "translate-y-0");
                    }
                });
            }
        });
    </script>