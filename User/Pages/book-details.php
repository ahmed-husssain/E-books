<?php
session_start();
include "../../Config/db.php"; // Include database connection

// Check if book ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

// Fetch book details
$sql = "SELECT b.*, c.CategoryName FROM books b LEFT JOIN categories c ON b.Category = c.CategoryID WHERE b.BookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: books.php");
    exit();
}
$book = $result->fetch_assoc();
$stmt->close();

// Determine available formats
$available_formats = [];
if ($book['HasHardCopy'] && $book['Stock'] > 0) {
    $available_formats['hardcover'] = 'Hardcover ($' . number_format($book['Price'], 2) . ')';
}
if (!empty($book['PDFPath'])) {
    $available_formats['pdf'] = 'PDF ($' . number_format($book['Price'], 2) . ')';
}
if ($book['HasCD']) {
    $available_formats['cd'] = 'CD ($' . number_format($book['Price'], 2) . ')';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['Title']) ?> - E - Books</title>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .book-image {
            transition: transform 220ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .book-image:hover {
            transform: scale(1.03);
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate, .book-image {
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <br><br><br>

        <!-- Book Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Book Image -->
            <div class="scroll-animate">
                <img src="../../uploads/Images/<?php echo htmlspecialchars($book['image']); ?>"
                     alt="<?= htmlspecialchars($book['Title']) ?>"
                     class="book-image w-full h-[600px] object-cover rounded-2xl shadow-2xl"
                     onerror="this.onerror=null; this.src='../../uploads/Images/placeholder.jpg';">
            </div>

            <!-- Book Information -->
            <div class="space-y-6 scroll-animate">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2"><?= $book['Title'] ?></h1>
                    <p class="text-xl text-slate-400">by <?= $book['Author'] ?></p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 text-sm font-semibold rounded-full">
                        <?= $book['CategoryName'] ?>
                    </span>
                   
                </div>
                <p class="text-lg text-slate-300 leading-relaxed">
                    <?= $book['Description'] ?? 'No description available.' ?>
                </p>
                <div class="border-t border-slate-700/50 pt-4">
                    <h3 class="text-xl font-semibold text-white mb-2">Available Formats</h3>
                    <?php if (empty($available_formats)): ?>
                        <p class="text-slate-400">No formats available.</p>
                    <?php else: ?>
                        <form method="POST" action="../../Config/addtocart.php" class="space-y-4">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($book['BookID']) ?>">
                        <input type="hidden" name="title" value="<?= htmlspecialchars($book['Title']) ?>">
                        <div>
                            <label for="format" class="block text-sm font-medium text-white mb-2">Select Format</label>
                            <select id="format" name="format" required
                                class="w-full px-4 py-3 rounded-lg bg-slate-800/30 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all duration-300">
                                <?php foreach ($available_formats as $format => $label): ?>
                                    <?php
                                    $formatValues = [
                                        'pdf' => 'PDF',
                                        'cd' => 'CD',
                                        'hardcover' => 'HardCopy'
                                    ];
                                    $formatValue = $formatValues[$format] ?? $format;
                                    ?>
                                    <option value="<?= $formatValue ?>" data-price="<?= $book['Price'] ?>">
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="qty" class="block text-sm font-medium text-white mb-2">Quantity</label>
                            <input type="number" id="qty" name="qty" min="1" max="5" value="1" required
                                class="w-full px-4 py-3 rounded-lg bg-slate-800/30 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all duration-300">
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-white">Price: <span id="price"><?= ($book['Price'] == 0.00) ? 'Free' : '$' . number_format($book['Price'], 2); ?></span></p>
                        </div>
                        <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-4 px-6 rounded-lg font-semibold text-lg transition-all duration-300 shadow-lg flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                        </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="text-slate-400">
                    <?php if ($book['HasHardCopy']): ?>
                        <p><strong>Stock:</strong> <?= $book['Stock'] ?> available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

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
            rootMargin: '0px 0px -50px 0px'
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

        // Update price based on format selection
        const formatSelect = document.getElementById('format');
        const priceSpan = document.getElementById('price');
        if (formatSelect && priceSpan) {
            formatSelect.addEventListener('change', function() {
                const selectedOption = formatSelect.options[formatSelect.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price'));
                priceSpan.textContent = '$' + price.toFixed(2);
            });
        }

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
        document.addEventListener("DOMContentLoaded", function () {
            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(link => {
                link.classList.remove("text-emerald-400");

                if (link.closest(".lg\\:flex")) { 
                    link.classList.add("text-slate-300");
                } else if (link.closest(".mobile-menu")) { 
                    link.classList.add("text-slate-300");
                }

                const underline = link.querySelector("span");
                if (underline) {
                    // Reset underline to default hover state
                    underline.classList.remove("w-full");
                    underline.classList.add("w-0", "group-hover:w-full", "bg-emerald-400");
                }
            });

            // Desktop active link
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/books.php"]');
            if (desktopActiveLink) {
                desktopActiveLink.classList.remove("text-slate-300");
                desktopActiveLink.classList.add("text-emerald-400");

                const activeUnderline = desktopActiveLink.querySelector("span");
                if (activeUnderline) {
                    activeUnderline.classList.remove("w-0", "group-hover:w-full");
                    activeUnderline.classList.add("w-full", "bg-emerald-400");
                }
            }

            // Mobile active link
            const mobileActiveLink = document.querySelector('nav a[href="books.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>