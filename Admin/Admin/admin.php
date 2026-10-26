<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | E - Books</title>
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
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617; /* slate-950 */
        }
        .scroll-animate {
            opacity: 0;
            transform: translateY(10px); /* Reduced for mobile */
            transition: all 0.8s ease-out;
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .parallax-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        .bg-blur {
            filter: blur(3rem);
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
        #sidebar.collapsed ~ #main-content {
            margin-left: 5rem; /* Matches collapsed sidebar width (w-20) */
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem; /* Matches expanded sidebar width (w-64) */
        }
        @media (max-width: 639px) {
            #sidebar.collapsed ~ #main-content {
                margin-left: 5rem; /* w-20 on mobile */
            }
            #sidebar:not(.collapsed) ~ #main-content {
                margin-left: 5rem; /* Sidebar always collapsed on mobile */
            }
            .table-responsive {
                -webkit-overflow-scrolling: touch; /* Smooth touch scrolling */
                max-height: 24rem; /* 384px */
                overflow-y: auto;
            }
            .table-responsive th, .table-responsive td {
                font-size: 0.75rem; /* Smaller text on mobile */
                padding: 0.5rem 0.25rem; /* Tighter padding */
                border-right: 1px solid #334155; /* Slate-700 for cell separation */
            }
            .table-responsive th:last-child, .table-responsive td:last-child {
                border-right: none; /* Remove border on last column */
            }
            .table-responsive .book-title {
                min-width: 100px; /* Prevent truncation */
            }
            .table-responsive thead {
                position: sticky;
                top: 0;
                background-color: #1e293b; /* slate-800 */
                z-index: 10;
            }
        }
        @media (min-width: 640px) {
            .table-responsive th, .table-responsive td {
                font-size: 0.875rem;
                padding: 0.75rem 0.5rem;
                border-right: 1px solid #334155;
            }
            .table-responsive th:last-child, .table-responsive td:last-child {
                border-right: none;
            }
            .table-responsive .book-title {
                min-width: 120px;
            }
        }
        @media (min-width: 768px) {
            .table-responsive th, .table-responsive td {
                font-size: 1rem;
                padding: 1rem 0.75rem;
                border-right: 1px solid #334155;
            }
            .table-responsive th:last-child, .table-responsive td:last-child {
                border-right: none;
            }
            .table-responsive .book-title {
                min-width: 150px;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white font-inter">
    <div class="flex min-h-screen">
        <?php include "../../Admin/Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="min-h-screen w-full transition-all duration-300 mt-16">
            <!-- Page Content -->
            <div class="pt-20 sm:pt-24 p-4 sm:p-6 lg:p-8">
                <!-- Parallax Background -->
                <div class="parallax-bg">
                    <div id="bg1" class="absolute w-96 h-96 bg-emerald-500/5 rounded-full bg-blur left-[-10%] top-[5%]"></div>
                    <div id="bg2" class="absolute w-96 h-96 bg-blue-500/5 rounded-full bg-blur left-[60%] top-[30%]"></div>
                    <div id="bg3" class="absolute w-96 h-96 bg-purple-500/5 rounded-full bg-blur left-[20%] top-[60%]"></div>
                </div>
                <!-- Dashboard Page -->
                <div id="dashboard-page" class="page active">
                    <div class="flex items-center justify-between mb-6 scroll-animate">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white">Admin Dashboard</h1>
                        <div class="flex items-center gap-2">
                            <span class="text-sm sm:text-base text-slate-400 font-medium" id="todays-date"></span>
                            <script>
                                const today = new Date();
                                const options = { year: 'numeric', month: 'long', day 'numeric' };
                                document.getElementById('todays-date').textContent = today.toLocaleDateString('en-US', options);
                            </script>
                        </div>
                    </div>
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                        <!-- Total Books -->
                        <div id="stat-0" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-2 group overflow-hidden relative scroll-animate rounded-2xl">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm sm:text-base text-slate-400 font-semibold">Total Books</h3>
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 bg-emerald-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        <i class="ri-book-line text-white text-base sm:text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex items-end gap-2">
                                    <span class="text-2xl sm:text-3xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">
                                        <?php
                                        $query = "SELECT COUNT(*) AS TotalBooks FROM books";
                                        $result = $conn->query($query);
                                        if ($result) {
                                            $row = $result->fetch_assoc();
                                            echo $row['TotalBooks'];
                                        } else {
                                            echo "Query failed: " . $conn->error;
                                        }
                                        ?>
                                    </span>
                                    <span class="text-xs sm:text-sm font-medium text-emerald-400 flex items-center">
                                        <i class="ri-arrow-up-line mr-1"></i>12.5%
                                    </span>
                                </div>
                                <div class="text-xs sm:text-sm text-slate-400 mt-1">Compared to last month</div>
                            </div>
                        </div>
                        <!-- Total Users -->
                        <div id="stat-1" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-2 group overflow-hidden relative scroll-animate rounded-2xl">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm sm:text-base text-slate-400 font-semibold">Total Users</h3>
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 bg-blue-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        <i class="ri-user-line text-white text-base sm:text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex items-end gap-2">
                                    <span class="text-2xl sm:text-3xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">
                                        <?php
                                        $query = "SELECT COUNT(*) AS TotalUsers FROM users";
                                        $result = $conn->query($query);
                                        if ($result) {
                                            $row = $result->fetch_assoc();
                                            echo $row['TotalUsers'];
                                        } else {
                                            echo "Query failed: " . $conn->error;
                                        }
                                        ?>
                                    </span>
                                    <span class="text-xs sm:text-sm font-medium text-emerald-400 flex items-center">
                                        <i class="ri-arrow-up-line mr-1"></i>8.3%
                                    </span>
                                </div>
                                <div class="text-xs sm:text-sm text-slate-400 mt-1">Compared to last month</div>
                            </div>
                        </div>
                        <!-- Total Winners -->
                        <div id="stat-2" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-2 group overflow-hidden relative scroll-animate rounded-2xl">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm sm:text-base text-slate-400 font-semibold">Total Winners</h3>
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 bg-amber-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        <i class="ri-trophy-line text-white text-base sm:text-xl"></i>
                                    </div>
                                </div>
                                <div class="flex items-end gap-2">
                                    <span class="text-2xl sm:text-3xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">
                                        <?php
                                        $query = "SELECT COUNT(*) AS TotalWinners FROM winners";
                                        $result = $conn->query($query);
                                        if ($result) {
                                            $row = $result->fetch_assoc();
                                            echo $row['TotalWinners'];
                                        } else {
                                            echo "Query failed: " . $conn->error;
                                        }
                                        ?>
                                    </span>
                                    <span class="text-xs sm:text-sm font-medium text-red-400 flex items-center">
                                        <i class="ri-arrow-down-line mr-1"></i>2.7%
                                    </span>
                                </div>
                                <div class="text-xs sm:text-sm text-slate-400 mt-1">Compared to last month</div>
                            </div>
                        </div>
                        <!-- Total Revenue -->
                        <div id="stat-3" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-2 group overflow-hidden relative scroll-animate rounded-2xl">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm sm:text-base text-slate-400 font-semibold">Total Revenue</h3>
                                    <div class="w-8 sm:w-10 h-8 sm:h-10 bg-purple-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                        <i class="ri-money-dollar-circle-line text-white text-base sm:text-xl"></i>
                                    </div>
                                </div>
                                <?php
                                // Get current and previous month dates
                                $currentMonthStart = date('Y-m-01 00:00:00');
                                $previousMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
                                $previousMonthEnd = date('Y-m-t 23:59:59', strtotime('last day of last month'));

                                // Query for total revenue this month
                                $queryCurrent = "SELECT SUM(TotalAmount) AS TotalRevenue FROM orders WHERE OrderDate >= '$currentMonthStart'";
                                $resultCurrent = $conn->query($queryCurrent);
                                $totalRevenue = 0;
                                if ($resultCurrent && $rowCurrent = $resultCurrent->fetch_assoc()) {
                                    $totalRevenue = $rowCurrent['TotalRevenue'] ?? 0;
                                }

                                // Query for total revenue last month
                                $queryPrevious = "SELECT SUM(TotalAmount) AS TotalRevenue FROM orders WHERE OrderDate BETWEEN '$previousMonthStart' AND '$previousMonthEnd'";
                                $resultPrevious = $conn->query($queryPrevious);
                                $previousRevenue = 0;
                                if ($resultPrevious && $rowPrevious = $resultPrevious->fetch_assoc()) {
                                    $previousRevenue = $rowPrevious['TotalRevenue'] ?? 0;
                                }

                                // Calculate percentage change
                                $percentageChange = 0;
                                $changeClass = 'text-emerald-400';
                                $changeIcon = 'ri-arrow-up-line';
                                if ($previousRevenue > 0) {
                                    $percentageChange = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
                                    $percentageChange = round($percentageChange, 1);
                                    if ($percentageChange < 0) {
                                        $changeClass = 'text-red-400';
                                        $changeIcon = 'ri-arrow-down-line';
                                        $percentageChange = abs($percentageChange);
                                    }
                                }
                                ?>
                                <div class="flex items-end gap-2">
                                    <span class="text-2xl sm:text-3xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">
                                        $<?php echo number_format($totalRevenue, 2); ?>
                                    </span>
                                    <span class="text-xs sm:text-sm font-medium <?php echo $changeClass; ?> flex items-center">
                                        <i class="<?php echo $changeIcon; ?> mr-1"></i><?php echo $percentageChange; ?>%
                                    </span>
                                </div>
                                <div class="text-xs sm:text-sm text-slate-400 mt-1">Compared to last month</div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Activities and Top Books -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Recent Activities -->
                        <div id="recent-activities" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group scroll-animate rounded-2xl lg:col-span-1">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4 sm:mb-6">
                                    <h3 class="text-xl sm:text-2xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">Recent Activities</h3>
                                </div>
                                <div class="space-y-3 sm:space-y-4">
                                    <?php
                                    // Fetch the latest user
                                    $userSql = "SELECT Username, DateRegistered FROM users ORDER BY DateRegistered DESC LIMIT 1";
                                    $userResult = $conn->query($userSql);
                                    $latestUser = $userResult->fetch_assoc();

                                    // Fetch the latest order
                                    $orderSql = "SELECT OrderID, OrderDate FROM orders ORDER BY OrderDate DESC LIMIT 1";
                                    $orderResult = $conn->query($orderSql);
                                    $latestOrder = $orderResult->fetch_assoc();

                                    // Fetch the latest book
                                    $bookSql = "SELECT Title, Author, CreatedAt FROM books ORDER BY CreatedAt DESC LIMIT 1";
                                    $bookResult = $conn->query($bookSql);
                                    $latestBook = $bookResult->fetch_assoc();

                                    // Fetch the latest submission
                                    $submissionSql = "SELECT s.SubmissionID, c.Title AS CompetitionTitle, s.SubmissionDate 
                                                    FROM submissions s 
                                                    JOIN competitions c ON s.CompetitionID = c.CompetitionID 
                                                    ORDER BY s.SubmissionDate DESC LIMIT 1";
                                    $submissionResult = $conn->query($submissionSql);
                                    $latestSubmission = $submissionResult->fetch_assoc();

                                    // Function to format time difference
                                    function timeAgo($datetime) {
                                        $now = new DateTime();
                                        $past = new DateTime($datetime);
                                        $interval = $now->diff($past);

                                        if ($interval->y > 0) return $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
                                        if ($interval->m > 0) return $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
                                        if ($interval->d > 0) return $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
                                        if ($interval->h > 0) return $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
                                        if ($interval->i > 0) return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
                                        return "just now";
                                    }
                                    ?>
                                    <a href="../../Admin/AdminUser/admin.users.php" class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl bg-slate-700/30 hover:bg-slate-700/50 transition-all duration-300 group/item scroll-animate">
                                        <div class="w-8 sm:w-10 h-8 sm:h-10 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform duration-300">
                                            <i class="ri-user-add-line text-white text-base"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm sm:text-base font-semibold text-white group-hover/item:text-emerald-400 transition-colors duration-300">New user registered</div>
                                            <div class="text-xs sm:text-sm text-slate-400"><?php echo isset($latestUser) ? htmlspecialchars($latestUser['Username']) . " just created an account" : "No users found"; ?></div>
                                            <div class="text-xs text-slate-500 mt-1"><?php echo isset($latestUser) ? timeAgo($latestUser['DateRegistered']) : "N/A"; ?></div>
                                        </div>
                                    </a>
                                    <a href="../../Admin/AdminOrder/admin.order.php" class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl bg-slate-700/30 hover:bg-slate-700/50 transition-all duration-300 group/item scroll-animate">
                                        <div class="w-8 sm:w-10 h-8 sm:h-10 bg-blue-500 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform duration-300">
                                            <i class="ri-shopping-cart-line text-white text-base"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm sm:text-base font-semibold text-white group-hover/item:text-emerald-400 transition-colors duration-300">New order placed</div>
                                            <div class="text-xs sm:text-sm text-slate-400"><?php echo isset($latestOrder) ? "Order #" . $latestOrder['OrderID'] . " was placed successfully" : "No orders found"; ?></div>
                                            <div class="text-xs text-slate-500 mt-1"><?php echo isset($latestOrder) ? timeAgo($latestOrder['OrderDate']) : "N/A"; ?></div>
                                        </div>
                                    </a>
                                    <a href="../../Admin/AdminBook/admin.books.php" class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl bg-slate-700/30 hover:bg-slate-700/50 transition-all duration-300 group/item scroll-animate">
                                        <div class="w-8 sm:w-10 h-8 sm:h-10 bg-amber-500 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform duration-300">
                                            <i class="ri-book-line text-white text-base"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm sm:text-base font-semibold text-white group-hover/item:text-emerald-400 transition-colors duration-300">New book added</div>
                                            <div class="text-xs sm:text-sm text-slate-400"><?php echo isset($latestBook) ? '"' . htmlspecialchars($latestBook['Title']) . '" by ' . htmlspecialchars($latestBook['Author']) : "No books found"; ?></div>
                                            <div class="text-xs text-slate-500 mt-1"><?php echo isset($latestBook) ? timeAgo($latestBook['CreatedAt']) : "N/A"; ?></div>
                                        </div>
                                    </a>
                                    <a href="../../Admin/AdminSubmissions/submisions.php" class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-xl bg-slate-700/30 hover:bg-slate-700/50 transition-all duration-300 group/item scroll-animate">
                                        <div class="w-8 sm:w-10 h-8 sm:h-10 bg-purple-500 rounded-full flex items-center justify-center shadow-lg group-hover/item:scale-110 transition-transform duration-300">
                                            <i class="ri-file-text-line text-white text-base"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm sm:text-base font-semibold text-white group-hover/item:text-emerald-400 transition-colors duration-300">New submission received</div>
                                            <div class="text-xs sm:text-sm text-slate-400"><?php echo isset($latestSubmission) ? "Submission #" . $latestSubmission['SubmissionID'] . " for " . htmlspecialchars($latestSubmission['CompetitionTitle']) : "No submissions found"; ?></div>
                                            <div class="text-xs text-slate-500 mt-1"><?php echo isset($latestSubmission) ? timeAgo($latestSubmission['SubmissionDate']) : "N/A"; ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Top Selling Books -->
                        <?php
                        // Query to fetch top 5 selling books
                        $sql = "
                            SELECT 
                                b.BookID,
                                b.Title,
                                b.Author,
                                b.Price,
                                SUM(o.Quantity) AS TotalSales,
                                (b.Price * SUM(o.Quantity)) AS Revenue,
                                b.Image AS image
                            FROM orders o
                            JOIN books b ON o.BookID = b.BookID
                            GROUP BY b.BookID, b.Title, b.Author, b.Price, b.Image
                            ORDER BY TotalSales DESC
                            LIMIT 5
                        ";
                        $result = $conn->query($sql);
                        if (!$result) {
                            error_log("Top Selling Books query failed: " . $conn->error);
                        }

                        // Define category colors (unused in table but kept for consistency)
                        $categoryColors = [
                            'Fiction' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'Education' => 'bg-green-500/20 text-green-400 border-green-500/30',
                            'Children' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                            'History' => 'bg-red-500/20 text-red-400 border-red-500/30',
                            'Mystery' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                            'Self-Help' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                            'Fantasy' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                            'Photography' => 'bg-teal-500/20 text-teal-400 border-teal-500/30',
                            'Islamic' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30',
                            'Sci-Fi' => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30'
                        ];
                        ?>
                        <div id="top-books" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group scroll-animate rounded-2xl lg:col-span-2">
                            <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                            <div class="p-4 sm:p-6 relative">
                                <div class="flex items-center justify-between mb-4 sm:mb-6">
                                    <h3 class="text-xl sm:text-2xl font-black text-white group-hover:text-emerald-400 transition-colors duration-300">Top Selling Books</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-responsive">
                                        <thead>
                                            <tr class="text-left text-xs sm:text-sm text-slate-400 border-b border-slate-700/50">
                                                <th class="pb-3 font-semibold book-title">Book</th>
                                                <th class="pb-3 font-semibold hidden sm:table-cell">Author</th>
                                                <th class="pb-3 font-semibold">Price</th>
                                                <th class="pb-3 font-semibold">Sales</th>
                                                <th class="pb-3 font-semibold hidden sm:table-cell">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && $result->num_rows > 0) {
                                                $index = 0;
                                                while ($row = $result->fetch_assoc()) {
                                                    $imagePath = !empty($row['image']) && file_exists("../../Uploads/images/" . $row['image']) 
                                                        ? "../../Uploads/images/" . htmlspecialchars($row['image']) 
                                                        : "../../Uploads/images/placeholder.jpg";
                                            ?>
                                            <tr class="border-b border-slate-700/50 group/item">
                                                <td class="py-2 sm:py-4 book-title">
                                                    <div class="flex items-center gap-2 sm:gap-3">
                                                        <div class="w-8 h-12 bg-slate-700 rounded overflow-hidden group-hover/item:scale-105 transition-transform duration-300">
                                                            <img src="<?php echo $imagePath; ?>" alt="Book Cover" class="w-full h-full object-cover" onerror="this.src='../../Uploads/images/placeholder.jpg'">
                                                        </div>
                                                        <span class="text-xs sm:text-sm md:text-base font-semibold text-white group-hover/item:text-emerald-400 transition-colors duration-300"><?php echo htmlspecialchars($row['Title']); ?></span>
                                                    </div>
                                                </td>
                                                <td class="py-2 sm:py-4 text-xs sm:text-sm md:text-base text-slate-300 hidden sm:table-cell"><?php echo htmlspecialchars($row['Author']); ?></td>
                                                <td class="py-2 sm:py-4 text-xs sm:text-sm md:text-base text-slate-300">$<?php echo number_format($row['Price'], 2); ?></td>
                                                <td class="py-2 sm:py-4 text-xs sm:text-sm md:text-base text-slate-300"><?php echo number_format($row['TotalSales']); ?></td>
                                                <td class="py-2 sm:py-4 text-xs sm:text-sm md:text-base font-semibold text-white hidden sm:table-cell">$<?php echo number_format($row['Revenue'], 2); ?></td>
                                            </tr>
                                            <?php
                                                    $index++;
                                                }
                                            } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" class="py-4 text-center text-slate-400 text-xs sm:text-sm">No sales data available.</td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

        // Parallax background effect
        let scrollY = 0;
        
        function updateParallax() {
            scrollY = window.scrollY;
            const bg1 = document.getElementById('bg1');
            const bg2 = document.getElementById('bg2');
            const bg3 = document.getElementById('bg3');
            
            if (bg1) bg1.style.transform = `translateY(${scrollY * 0.05}px)`;
            if (bg2) bg2.style.transform = `translateY(${scrollY * -0.075}px)`;
            if (bg3) bg3.style.transform = `translateY(${scrollY * 0.025}px)`;
        }

        window.addEventListener('scroll', updateParallax);
        updateParallax();

        // Debug sidebar and main content margin
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
            // Debug top books table
            const tableImages = document.querySelectorAll('#top-books img');
            tableImages.forEach(img => {
                console.log('Book image loaded:', img.src, img.complete ? 'Success' : 'Failed');
            });
            const tableContainer = document.querySelector('#top-books .table-responsive');
            console.log('Table height:', tableContainer.offsetHeight, 'px');
            console.log('Table scrollable:', tableContainer.scrollHeight > tableContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
        });
    </script>
</body>
</html>