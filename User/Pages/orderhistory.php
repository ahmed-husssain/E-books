<?php
session_start();
include "../../Config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch orders with book titles and PDF paths
$sql = "SELECT o.*, b.Title, b.PDFPath FROM orders o JOIN books b ON o.BookID = b.BookID WHERE o.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Order History - E - Books</title>
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
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
        .table-header {
            background: linear-gradient(to bottom, #1e293b, #0f172a);
        }
        .table-row:hover {
            background-color: #1e293b;
        }
        .status-pending {
            background-color: #f59e0b; /* Amber for pending */
        }
        .status-completed {
            background-color: #10b981; /* Emerald for completed */
        }
        .status-cancelled {
            background-color: #ef4444; /* Red for cancelled */
        }
        @media (max-width: 640px) {
            .table-container {
                display: none;
            }
            .mobile-order-card {
                display: block;
            }
        }
        @media (min-width: 641px) {
            .mobile-order-card {
                display: none;
            }
        }
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
    </style>
</head>
<body class="bg-slate-950 text-white">    
    <!-- Order History Section -->
    <section class="pt-32 pb-24 px-4 sm:px-6 relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-blue-500/5 to-purple-500/5 rounded-3xl blur-3xl animate-pulse z-[-1]"></div>
        
        <div class="container mx-auto">
            <div id="order-header" class="text-center mb-12 sm:mb-20 scroll-animate">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6">
                    Your <span class="text-emerald-400">Order History</span>
                </h1>
                <p class="text-slate-400 text-base sm:text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Review your past orders and track your reading journey with E - Books.
                </p>
            </div>

            <?php if ($result->num_rows === 0): ?>
                <div class="text-center scroll-animate">
                    <p class="text-slate-300 text-lg sm:text-xl">No orders found.</p>
                    <a href="books.php" class="mt-6 inline-block bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-3 px-6 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                        Explore Books
                    </a>
                </div>
            <?php else: ?>
                <!-- Desktop Table -->
                <div class="table-container backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl p-4 sm:p-6 scroll-animate">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="table-header text-slate-300 font-semibold text-sm sm:text-base">
                                <th class="p-3 sm:p-4 text-left">Order ID</th>
                                <th class="p-3 sm:p-4 text-left">Book Title</th>
                                <th class="p-3 sm:p-4 text-left">Format</th>
                                <th class="p-3 sm:p-4 text-left">Quantity</th>
                                <th class="p-3 sm:p-4 text-left">Total</th>
                                <th class="p-3 sm:p-4 text-left">Date</th>
                                <th class="p-3 sm:p-4 text-left">Expected Delivery</th>
                                <th class="p-3 sm:p-4 text-left">Status</th>
                                <th class="p-3 sm:p-4 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $result->fetch_assoc()): ?>
                                <tr class="table-row border-b border-slate-700/50 text-sm sm:text-base transition-all duration-300">
                                    <td class="p-3 sm:p-4"><?= htmlspecialchars($order['OrderID']) ?></td>
                                    <td class="p-3 sm:p-4"><?= htmlspecialchars($order['Title']) ?></td>
                                    <td class="p-3 sm:p-4"><?= htmlspecialchars($order['Format']) ?></td>
                                    <td class="p-3 sm:p-4"><?= htmlspecialchars($order['Quantity']) ?></td>
                                    <td class="p-3 sm:p-4">$<?= number_format($order['TotalAmount'], 2) ?></td>
                                    <td class="p-3 sm:p-4"><?= date('M d, Y', strtotime($order['OrderDate'])) ?></td>
                                    <td class="p-3 sm:p-4">
                                        <?php 
                                        if ($order['Format'] === 'PDF' && $order['PaymentStatus'] === 'Paid') {
                                            echo 'Delivered';
                                        } else {
                                            echo date('M d, Y', strtotime($order['OrderDate'] . ' +3 days'));
                                        }
                                        ?>
                                    </td>
                                    <td class="p-3 sm:p-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-white text-xs sm:text-sm font-semibold 
                                            <?php 
                                            if ($order['PaymentStatus'] === 'Pending') echo 'status-pending';
                                            elseif ($order['PaymentStatus'] === 'Paid') echo 'status-completed';
                                            else echo 'status-cancelled';
                                            ?>">
                                            <?= htmlspecialchars($order['PaymentStatus']) ?>
                                        </span>
                                    </td>
                                    <td class="p-3 sm:p-4 flex space-x-2">
                                        <?php if ($order['Format'] === 'PDF' && $order['PaymentStatus'] === 'Paid'): ?>
                                            <a href="download.php?book_id=<?= htmlspecialchars($order['BookID']) ?>" target="_blank" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="download.php?book_id=<?= htmlspecialchars($order['BookID']) ?>&download=1" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-order-card space-y-6">
                    <?php 
                    $result->data_seek(0); // Reset result pointer for mobile view
                    while ($order = $result->fetch_assoc()): ?>
                        <div class="order-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-4 sm:p-6 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300 scroll-animate">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Order ID</span>
                                    <span class="text-white font-semibold"><?= htmlspecialchars($order['OrderID']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Book Title</span>
                                    <span class="text-white font-semibold text-xs"><?= htmlspecialchars($order['Title']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Format</span>
                                    <span class="text-white font-semibold"><?= htmlspecialchars($order['Format']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Quantity</span>
                                    <span class="text-white font-semibold"><?= htmlspecialchars($order['Quantity']) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Total</span>
                                    <span class="text-white font-semibold">$<?= number_format($order['TotalAmount'], 2) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Date</span>
                                    <span class="text-white font-semibold"><?= date('M d, Y', strtotime($order['OrderDate'])) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Expected Delivery</span>
                                    <span class="text-white font-semibold">
                                        <?php 
                                        if ($order['Format'] === 'PDF' && $order['PaymentStatus'] === 'Paid') {
                                            echo 'Delivered';
                                        } else {
                                            echo isset($order['ExpectedDeliveryDate']) ? date('M d, Y', strtotime($order['ExpectedDeliveryDate'])) : 'N/A';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400 text-sm">Status</span>
                                    <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-semibold 
                                        <?php 
                                        if ($order['PaymentStatus'] === 'Pending') echo 'status-pending';
                                        elseif ($order['PaymentStatus'] === 'Paid') echo 'status-completed';
                                        else echo 'status-cancelled';
                                        ?>">
                                        <?= htmlspecialchars($order['PaymentStatus']) ?>
                                    </span>
                                </div>
                                <?php if ($order['Format'] === 'PDF' && $order['PaymentStatus'] === 'Paid'): ?>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 text-sm">Action</span>
                                        <div class="flex space-x-2">
                                            <a href="download.php?book_id=<?= htmlspecialchars($order['BookID']) ?>" target="_blank" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="download.php?book_id=<?= htmlspecialchars($order['BookID']) ?>&download=1" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mt-16 text-center scroll-animate">
            <a href="home.php" class="inline-block bg-slate-700 hover:bg-slate-600 text-slate-200 shadow-xl shadow-slate-700/30 py-3 px-6 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                <i class="ri-arrow-left-line text-lg"></i> Go Back to Home
            </a>
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
    </script>
</body>
</html>
<?php $stmt->close(); ?>