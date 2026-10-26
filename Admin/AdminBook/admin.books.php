<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management | E - Books</title>
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
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
        #sidebar.collapsed ~ #main-content {
            margin-left: 4rem;
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem;
        }
        @media (max-width: 639px) {
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                max-height: 24rem; /* 384px */
                overflow-y: auto;
            }
            .table-responsive tr {
                display: flex;
                flex-direction: column;
                padding: 1rem;
                border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            }
            .table-responsive td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #94a3b8; /* slate-400 */
                width: 40%;
            }
            .table-responsive thead {
                position: sticky;
                top: 0;
                background-color: #1e293b; /* slate-800 */
                z-index: 10;
            }
            .table-responsive .book-title {
                min-width: 100px;
            }
            .table-responsive .category {
                min-width: 80px;
            }
        }
        @media (max-width: 1024px) {
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                max-height: 24rem; /* 384px */
                overflow-y: auto;
            }
            .table-responsive tr {
                display: flex;
                flex-direction: column;
                padding: 1rem;
                border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            }
            .table-responsive td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #94a3b8; /* slate-400 */
                width: 40%;
            }
            .table-responsive thead {
                position: sticky;
                top: 0;
                background-color: #1e293b; /* slate-800 */
                z-index: 10;
            }
            .table-responsive .book-title {
                min-width: 100px;
            }
            .table-responsive .category {
                min-width: 80px;
            }
        }
        @media (min-width: 640px) {
            .table-responsive th, .table-responsive td {
                font-size: 0.875rem;
                padding: 0.75rem 1.5rem;
            }
            .table-responsive .book-title {
                min-width: 120px;
            }
            .table-responsive .category {
                min-width: 100px;
            }
        }
        @media (min-width: 768px) {
            .table-responsive th, .table-responsive td {
                font-size: 1rem;
                padding: 1rem 1.5rem;
            }
            .table-responsive .book-title {
                min-width: 150px;
            }
            .table-responsive .category {
                min-width: 120px;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
</head>
<body class="bg-slate-950 text-white font-['Inter']">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="px-3 ml-20 sm:ml-64 min-h-screen flex-1 transition-all duration-300">
            <!-- Page Content -->
            <div class="pt-20 sm:pt-24 p-4 sm:p-6 relative">
                <!-- Books Page -->
                <div id="books-page" class="page active container mx-auto">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 sm:mb-12 mt-3 scroll-animate">
                        <h1 class="text-3xl sm:text-4xl font-black text-white">Books Management</h1>
                        <a href="newbook.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center mt-4 sm:mt-0">
                            <span class="relative z-10 flex items-center">
                                <i class="ri-add-line mr-2"></i>
                                Add New Book
                            </span>
                        </a>
                    </div>
                    <!-- Books Table -->
                    <div class="backdrop-blur-3xl bg-slate-800/40 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
                        <div class="overflow-x-auto overflow-y-hidden">
                            <table class="w-full table-responsive">
                                <thead>
                                    <tr class="text-left text-sm sm:text-base text-slate-400 bg-slate-800/20">
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">ID</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold book-title">Book</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Author</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold category hidden sm:table-cell">Category</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Price</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Stock</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT b.*, c.CategoryName 
                                            FROM books b
                                            LEFT JOIN categories c ON b.Category = c.CategoryID";
                                    $data = $conn->query($sql);
                                    if (!$data) {
                                        error_log("Books query failed: " . $conn->error);
                                        echo "<tr><td colspan='7' class='px-4 sm:px-6 py-4 text-center text-slate-400'>Error fetching data: " . htmlspecialchars($conn->error) . "</td></tr>";
                                    } elseif ($data->num_rows > 0) {
                                        while ($rows = $data->fetch_assoc()) {
                                            $imagePath = !empty($rows['image']) && file_exists("../../Uploads/images/" . $rows['image']) 
                                                ? "../../Uploads/images/" . htmlspecialchars($rows['image']) 
                                                : "../../Uploads/images/placeholder.jpg";
                                    ?>
                                    <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all duration-300 scroll-animate">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="ID">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="w-8 h-12 bg-slate-800 rounded-lg overflow-hidden">
                                                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($rows['Title']); ?> Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='../../Uploads/images/placeholder.jpg'">
                                                </div>
                                                <span class="text-base font-medium text-slate-300"><?php echo htmlspecialchars($rows['BookID']); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 book-title" data-label="Book"><?php echo htmlspecialchars($rows['Title']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Author"><?php echo htmlspecialchars($rows['Author']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 category hidden sm:table-cell" data-label="Category">
                                            <span class="text-sm font-medium px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full"><?php echo htmlspecialchars($rows['CategoryName'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Price">$<?php echo number_format($rows['Price'], 2); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Stock">
                                            <span class="text-sm font-medium px-3 py-1 rounded-full <?php echo ($rows['Stock'] > 0) ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30'; ?>">
                                                <?php 
                                                if ($rows['Stock'] > 0) {
                                                    echo 'In Stock (' . htmlspecialchars($rows['Stock']) . ')';
                                                } else {
                                                    echo 'Out of Stock';
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Actions">
                                            <div class="flex items-center gap-3">
                                                <a href="booksupdate.php?id=<?php echo htmlspecialchars($rows['BookID']); ?>" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                                    <i class="ri-edit-line text-lg"></i>
                                                </a>
                                                <form method="POST" action="booksdelete.php" onsubmit="return confirm('Are you sure you want to delete this book?');" class="inline m-0 p-0">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($rows['BookID']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                                        <i class="ri-delete-bin-line text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='px-4 sm:px-6 py-4 text-center text-slate-400'>No books found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isMobile = window.innerWidth <= 1024;
            const elements = document.querySelectorAll('.scroll-animate');

            elements.forEach((el, index) => {
                if (!isMobile) { // Apply animation only on non-mobile devices
                    el.style.animationDelay = `${index * 100}ms`;
                    observer.observe(el);
                } else {
                    el.classList.add('visible'); // Immediately show elements on mobile
                }
            });
        });
        // Scroll animations
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-animate').forEach((el, index) => {
            el.style.animationDelay = `${index * 100}ms`;
            observer.observe(el);
        });
    </script>
</body>
</html>