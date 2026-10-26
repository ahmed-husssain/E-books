<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management | E - Books</title>
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
            margin-left: 4rem;
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem;
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
        .category-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.5), rgba(4, 120, 87, 0.1));
            border: 1px solid rgba(51, 65, 85, 0.3);
            border-radius: 40px;
            min-height: 180px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
            border-color: rgba(16, 185, 129, 0.5);
        }
        .category-card .icon {
            transition: all 0.3s ease;
            opacity: 0.1;
        }
        .category-card:hover .icon {
            opacity: 0.3;
            transform: scale(1.1);
        }
        .category-card h2 {
            background: linear-gradient(to right, #ffffff, #34d399);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            transition: all 0.3s ease;
        }
        .category-card:hover h2 {
            transform: translateX(4px);
        }
        .category-card .action-btn {
            background: rgba(51, 65, 85, 0.5);
            transition: all 0.3s ease;
        }
        .category-card .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        /* Mobile View (max-width: 639px) */
        @media (max-width: 639px) {
            .grid-responsive {
                -webkit-overflow-scrolling: touch;
                max-height: 36rem;
                overflow-y: auto;
                padding: 0.5rem;
            }
            .grid-responsive .category-card {
                max-width: 99%;
                font-size: 0.875rem;
                padding: 1rem;
                border-radius: 32px;
                min-height: 160px;
            }
            .grid-responsive .category-card h2 {
                font-size: 1.125rem;
            }
            .grid-responsive .category-card .icon {
                font-size: 2rem;
            }
            .grid-responsive .category-card .label::before {
                content: attr(data-label);
                font-weight: 600;
                color: #94a3b8;
                display: inline-block;
                width: 100%;
                margin-bottom: 0.25rem;
            }
            .grid-responsive .category-card p {
                font-size: 0.75rem;
            }
            .grid-responsive .category-card .action-btn {
                width: 36px;
                height: 36px;
            }
            .scroll-animate {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* iPad View (min-width: 640px and max-width: 1023px) */
        @media (min-width: 640px) and (max-width: 1023px) {
            .grid-responsive {
                -webkit-overflow-scrolling: touch;
                padding: 1rem;
            }
            .grid-responsive .category-card {
                min-width: 180px;
                font-size: 0.9rem;
                padding: 1.25rem;
                border-radius: 36px;
                min-height: 170px;
            }
            .grid-responsive .category-card h2 {
                font-size: 1.25rem;
            }
            .grid-responsive .category-card .icon {
                font-size: 2.5rem;
            }
            .grid-responsive .category-card p {
                font-size: 0.875rem;
            }
            .grid-responsive .category-card .action-btn {
                width: 40px;
                height: 40px;
            }
            .scroll-animate {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Desktop View (min-width: 640px) */
        @media (min-width: 640px) {
            .grid-responsive .category-card {
                min-width: 200px;
                font-size: 0.875rem;
                padding: 1.5rem;
            }
            .grid-responsive .category-card h2 {
                font-size: 1.25rem;
            }
            .grid-responsive .category-card .icon {
                font-size: 3rem;
            }
            .grid-responsive .category-card p {
                font-size: 0.875rem;
            }
            .grid-responsive .category-card .action-btn {
                width: 44px;
                height: 44px;
            }
        }
        /* Larger Desktop View (min-width: 768px) */
        @media (min-width: 768px) {
            .grid-responsive .category-card {
                min-width: 220px;
                font-size: 1rem;
                padding: 1.5rem;
            }
            .grid-responsive .category-card h2 {
                font-size: 1.5rem;
            }
            .grid-responsive .category-card .icon {
                font-size: 3.5rem;
            }
            .grid-responsive .category-card p {
                font-size: 1rem;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
</head>
<body class="bg-slate-950 text-white font-['Inter'] flex min-h-screen">
    <!-- Sidebar -->
    <?php include "../Components/sider.php"; ?>
    <!-- Main Content -->
    <main id="main-content" class="flex-1 p-4 sm:p-6 lg:p-8 transition-all duration-300">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mt-14 mb-8 sm:mb-12 scroll-animate">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-white">Category Management</h1>
                <p class="text-slate-400 text-sm sm:text-base">View all categories in the system</p>
            </div>
            <a href="newcategory.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center mt-4 sm:mt-0">
                <span class="relative z-10 flex items-center">
                    <i class="ri-add-line mr-2 text-lg sm:text-xl"></i>
                    Add New Category
                </span>
            </a>
        </div>
        <!-- Category Grid -->
        <div id="categories-page" class="grid-responsive my-10 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 scroll-animate">
            <?php
            $categories = $conn->query("SELECT * FROM categories ORDER BY CategoryID DESC");
            if ($categories->num_rows > 0) {
                while ($row = $categories->fetch_assoc()) {
            ?>
               <div class="category-card w-72 h-40 backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group relative overflow-hidden">
                    <div class="absolute right-3 top-3 opacity-10 text-4xl sm:text-5xl pointer-events-none group-hover:opacity-20 transition-opacity duration-300">
                        <i class="ri-folder-3-fill text-emerald-400 icon"></i>
                    </div>
                    <div class="relative z-10 p-4 sm:p-6">
                        <h2 class="font-bold text-white group-hover:text-emerald-400 transition-colors duration-300" data-label="Category Name">
                            <?= htmlspecialchars($row['CategoryName']); ?>
                        </h2>
                        <p class="text-slate-400 mt-1 label" data-label="Category ID">
                            <span class="font-medium text-slate-300"><?= htmlspecialchars($row['CategoryID']); ?></span>
                        </p>
                        <div class="flex items-center gap-2 sm:gap-3 mt-3">
                            <a href="categoryupdate.php?id=<?= htmlspecialchars($row['CategoryID']); ?>" class="action-btn w-8 sm:w-12 h-8 sm:h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                <i class="ri-edit-line text-base sm:text-lg"></i>
                            </a>
                            <form method="POST" action="categorydelete.php" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline m-0 p-0">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['CategoryID']); ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                <button type="submit" class="action-btn w-8 sm:w-12 h-8 sm:h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                    <i class="ri-delete-bin-line text-base sm:text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </main>
    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const isMobileOrTablet = window.innerWidth <= 1023;
            const elements = document.querySelectorAll('.scroll-animate');

            elements.forEach((el, index) => {
                if (!isMobileOrTablet) {
                    el.style.animationDelay = `${index * 100}ms`;
                    observer.observe(el);
                } else {
                    el.classList.add('visible');
                }
            });

            // Debug grid and sidebar
            const gridContainer = document.querySelector('#categories-page .grid-responsive');
            console.log('Grid height:', gridContainer.offsetHeight, 'px');
            console.log('Grid scrollable:', gridContainer.scrollHeight > gridContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });
    </script>
</body>
</html>