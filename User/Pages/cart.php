<?php
session_start();
include "../../Config/db.php"; // Include database connection
require_once "../../Config/csrf.php";

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $cart_key = $_POST['cart_key'];
        if ($_POST['action'] === 'remove') {
            unset($_SESSION['cart'][$cart_key]);
        } elseif ($_POST['action'] === 'update_qty' && isset($_POST['qty'])) {
            $qty = (int)$_POST['qty'];
            if (isset($_SESSION['cart'][$cart_key])) {
                $book_id = $_SESSION['cart'][$cart_key]['id'];
                $format = $_SESSION['cart'][$cart_key]['format'];
                if ($format === 'pdf') {
                    // Restrict PDF to quantity of 1
                    if ($qty !== 1) {
                        $_SESSION['error'] = "PDF items are limited to 1 copy.";
                    } else {
                        $_SESSION['cart'][$cart_key]['qty'] = 1;
                    }
                } elseif ($format === 'hardcover') {
                    $sql = "SELECT Stock FROM books WHERE BookID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $book_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $book = $result->fetch_assoc();
                    $stmt->close();
                    if ($qty < 1 || $qty > 5 || $qty > $book['Stock']) {
                        $_SESSION['error'] = "Quantity must be between 1 and 5, and not exceed available stock (" . $book['Stock'] . ").";
                    } else {
                        $_SESSION['cart'][$cart_key]['qty'] = $qty;
                    }
                } else {
                    if ($qty < 1 || $qty > 5) {
                        $_SESSION['error'] = "Quantity must be between 1 and 5.";
                    } else {
                        $_SESSION['cart'][$cart_key]['qty'] = $qty;
                    }
                }
            }
        }
        header("Location: cart.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - E - Books</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1), transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate {
                opacity: 1 !important;
                transform: none !important;
                transition-duration: 150ms !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <!-- Navigation -->
    <?php include "../Components/nav.php"; ?>

    <!-- Page Header -->
    <section class="pt-32 pb-12 px-6 relative">
        <div class="container mx-auto relative">
            <div id="page-header" class="text-center scroll-animate">
                <h1 class="text-6xl lg:text-7xl font-black mb-6 text-white">
                    Shopping <span class="text-emerald-400">Cart</span>
                </h1>
                <p class="text-slate-400 text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Review your selected books and proceed to checkout
                </p>
            </div>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="pb-24 px-6">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div id="cart-items" class="space-y-6 scroll-animate">
                    <?php if (empty($_SESSION['cart'])): ?>
                        <p class="text-slate-400 text-lg">Your cart is empty.</p>
                    <?php else: ?>
                        <?php foreach ($_SESSION['cart'] as $cart_key => $item): ?>
                            <?php
                            // Fetch book details for category and stock
                            $sql = "SELECT c.CategoryName, b.image, b.Stock FROM books b LEFT JOIN categories c ON b.Category = c.CategoryID WHERE b.BookID = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $item['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $book = $result->num_rows ? $result->fetch_assoc() : ['CategoryName' => 'Unknown', 'Stock' => 0];
                            $stmt->close();
                            ?>
                            <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-6 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <div class="relative">
                                        <img src="../../uploads/images/<?php echo $book['image'] ?>" 
                                            alt="<?= htmlspecialchars($item['title']) ?>" 
                                            class="w-32 h-48 object-cover rounded-xl group-hover:scale-105 transition-transform duration-300">
                                        <form method="POST">
                                            <input type="hidden" name="cart_key" value="<?= $cart_key ?>">
                                            <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="absolute -top-2 -right-2 min-w-[44px] min-h-[44px] w-11 h-11 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200 shadow-lg btn-press" aria-label="Remove <?= htmlspecialchars($item['title']) ?> from cart">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex-1 space-y-4">
                                        <div>
                                            <h3 class="text-2xl font-bold text-white group-hover:text-emerald-400 transition-colors duration-300">
                                                <?= htmlspecialchars($item['title']) ?>
                                            </h3>
                                            <p class="text-slate-400 text-lg">Format: <?= ucfirst($item['format']) ?></p>
                                            <div class="flex items-center mt-2">
                                                <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 text-sm font-semibold rounded-full">
                                                    <?= htmlspecialchars($book['CategoryName']) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <form method="POST" class="flex items-center backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-xl">
                                                <?php if ($item['format'] === 'pdf'): ?>
                                                    <input type="hidden" name="cart_key" value="<?= $cart_key ?>">
                                                    <input type="hidden" name="action" value="update_qty">
                                                    <input type="hidden" name="qty" value="1">
                                                <?php else: ?>
                                                    <input type="hidden" name="cart_key" value="<?= $cart_key ?>">
                                                    <input type="hidden" name="action" value="update_qty">
                                                    <button type="submit" name="qty" value="<?= $item['qty'] - 1 ?>" 
                                                        class="min-w-[44px] min-h-[44px] flex items-center justify-center p-3 hover:bg-slate-600/50 rounded-l-xl transition-colors duration-200 btn-press <?= $item['qty'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>" 
                                                        <?= $item['qty'] <= 1 ? 'disabled' : '' ?>
                                                        aria-label="Decrease quantity of <?= htmlspecialchars($item['title']) ?>">
                                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Quantity Display -->
                                                    <span class="px-4 py-3 text-white font-semibold"><?= $item['qty'] ?></span>

                                                    <!-- Plus Button -->
                                                    <button type="submit" name="qty" value="<?= $item['qty'] + 1 ?>" 
                                                        class="min-w-[44px] min-h-[44px] flex items-center justify-center p-3 hover:bg-slate-600/50 rounded-r-xl transition-colors duration-200 btn-press <?= $item['qty'] >= 5 || $item['qty'] >= $book['Stock'] ? 'opacity-50 cursor-not-allowed' : '' ?>" 
                                                        <?= $item['qty'] >= 5 || $item['qty'] >= $book['Stock'] ? 'disabled' : '' ?>
                                                        aria-label="Increase quantity of <?= htmlspecialchars($item['title']) ?>">
                                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                            </form>
                                            <?php if ($item['format'] === 'PDF'): ?>
                                                    <p class="text-slate-400 text-sm px-4 py-3">PDF limited to 1 copy</p>
                                            <?php else: ?>
                                            <p class="text-slate-400 text-sm">Stock: <?= $book['Stock'] ?> available</p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-black text-emerald-400">$<?= number_format($item['price'] * $item['qty'], 2) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-8 scroll-animate">
                        <a href="books.php" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 transition-colors duration-300 text-lg font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <?php if (!empty($_SESSION['cart'])): ?>
                <div class="lg:col-span-1">
                    <div id="order-summary" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-8 shadow-2xl sticky top-32 scroll-animate">
                        <h2 class="text-3xl font-bold text-white mb-8">Order Summary</h2>
                        <div class="space-y-6">
                        <?php
                        $subtotal = 0;
                        $item_count = 0;
                        $has_only_pdf = true;
                        
                        foreach ($_SESSION['cart'] as $item) {
                            $subtotal += $item['price'] * $item['qty'];
                            $item_count += $item['qty'];
                            if (strtolower($item['format']) !== 'pdf') {
                                $has_only_pdf = false;
                            }
                        }
                        
                        $shipping = $has_only_pdf ? 0 : ($subtotal >= 50 ? 0 : 5.99);
                        $total = $subtotal + $shipping;
                        
                        // Format after calculating
                        $subtotal = number_format($subtotal, 2);
                        $total = number_format($total, 2);                        
                        ?>
                        <!-- Subtotal -->
                        <div class="flex justify-between items-center text-lg">
                            <span class="text-slate-300">Subtotal (<?= $item_count ?> items)</span>
                            <span class="text-white font-semibold">$<?= $subtotal ?></span>
                        </div>
                        <!-- Shipping -->
                        <div class="flex justify-between items-center text-lg">
                            <span class="text-slate-300">Shipping</span>
                            <span class="text-emerald-400 font-semibold"><?= $shipping === '0.00' ? 'FREE' : '$' . $shipping ?></span>
                        </div>
                        <hr class="border-slate-700/50">
                        <!-- Total -->
                        <div class="flex justify-between items-center text-2xl font-bold">
                            <span class="text-white">Total</span>
                            <span class="text-emerald-400"><?= $total == 0.0 ? 'FREE' : '$' . $total ?></span>
                        </div>
                        <!-- Checkout Button -->
                        <?php if ($total > 0.1): ?>
                            <a href="payment.php" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-4 text-xl font-semibold rounded-2xl transition-all duration-300 hover:scale-105 flex items-center justify-center mt-8">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 713.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 713.138-3.138z"></path>
                                </svg>
                                Proceed to Checkout
                            </a>
                            <?php else: ?>
                                <form method="POST" action="process_free_order.php" class="w-full">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-4 text-xl font-semibold rounded-2xl transition-all duration-300 hover:scale-105 flex items-center justify-center mt-8">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M...z"></path>
                                        </svg>
                                        Confirm Free Order
                                    </button>
                                </form>
                            <?php endif; ?>
                        <!-- Payment Methods -->
                        <div class="text-center mt-6">
                            <p class="text-slate-400 text-sm mb-4">We accept</p>
                            <div class="flex justify-center space-x-2">
                                <div class="w-16 h-8 bg-slate-700/50 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-slate-300">Card</span>
                                </div>
                                <div class="w-16 h-8 bg-slate-700/50 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-slate-300">DD</span>
                                </div>
                                <div class="w-16 h-8 bg-slate-700/50 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-slate-300">Cheque</span>
                                </div>
                                <div class="w-16 h-8 bg-slate-700/50 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-slate-300">COD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "../Components/footer.php"; ?>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

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

        // Parallax background effect
        let scrollY = 0;

        function updateParallax() {
            scrollY = window.scrollY;
            const bg1 = document.getElementById('bg1');
            const bg2 = document.getElementById('bg2');
            const bg3 = document.getElementById('bg3');

            if (bg1) bg1.style.transform = `translateY(${scrollY * 0.1}px)`;
            if (bg2) bg2.style.transform = `translateY(${scrollY * -0.15}px)`;
            if (bg3) bg3.style.transform = `translateY(${scrollY * 0.05}px)`;
        }

        window.addEventListener('scroll', updateParallax);
        updateParallax();

        // Update cart count
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = totalItems;
            }
            const mobileCartText = document.querySelector('#mobile-menu a[href="cart.php"]');
            if (mobileCartText) {
                mobileCartText.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9m-9 0h9"></path>
                    </svg>
                    Cart (${totalItems})
                `;
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>