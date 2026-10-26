<?php
include "../../Config/db.php";
include "../Components/checkifadmin.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winners Management | E - Books</title>
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
            .table-responsive .competition-title {
                min-width: 100px;
            }
            .table-responsive .username {
                min-width: 80px;
            }
        }
        @media (min-width: 640px) {
            .table-responsive th, .table-responsive td {
                font-size: 0.875rem;
                padding: 0.75rem 1.5rem;
            }
            .table-responsive .competition-title {
                min-width: 120px;
            }
            .table-responsive .username {
                min-width: 100px;
            }
        }
        @media (min-width: 768px) {
            .table-responsive th, .table-responsive td {
                font-size: 1rem;
                padding: 1rem 1.5rem;
            }
            .table-responsive .competition-title {
                min-width: 150px;
            }
            .table-responsive .username {
                min-width: 120px;
            }
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
    </style>
</head>
<body class="bg-slate-950 text-white font-inter">
    <div class="flex min-h-screen">
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="ml-20 sm:ml-64 min-h-screen flex-1 transition-all duration-300">
            <!-- Page Content -->
            <div class="pt-20 sm:pt-24 p-4 sm:p-6 relative">
                <!-- Winners Page -->
                <div id="winners-page" class="page active container mx-auto">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 sm:mb-12 mt-3 scroll-animate">
                        <h1 class="text-3xl sm:text-4xl font-black text-white">Winners Management</h1>
                        <a href="newwinner.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center mt-4 sm:mt-0">
                            <span class="relative z-10 flex items-center">
                                <i class="ri-add-line mr-2"></i>
                                Add New Winner
                            </span>
                        </a>
                    </div>
                    <!-- Winners Table -->
                    <div class="backdrop-blur-3xl bg-slate-800/40 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
                        <div class="overflow-x-auto overflow-y-hidden">
                            <table class="w-full table-responsive">
                                <thead>
                                    <tr class="text-left text-sm sm:text-base text-slate-400 bg-slate-800/20">
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Winner ID</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold competition-title">Competition Title</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold username">User Name</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hidden sm:table-cell">Position</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Awarded At</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Prize</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "
                                        SELECT 
                                            w.WinnerID,
                                            c.Title AS CompetitionTitle,
                                            c.Prize AS CompetitionPrize,
                                            u.Username AS UserName,
                                            w.Position,
                                            w.AwardedAt
                                        FROM winners w
                                        JOIN competitions c ON w.CompetitionID = c.CompetitionID
                                        JOIN users u ON w.UserID = u.UserID
                                    ";

                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all duration-300 scroll-animate">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Winner ID"><?php echo htmlspecialchars($row['WinnerID']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 competition-title" data-label="Competition Title"><?php echo htmlspecialchars($row['CompetitionTitle']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 username" data-label="User Name"><?php echo htmlspecialchars($row['UserName']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 hidden sm:table-cell" data-label="Position"><?php echo htmlspecialchars($row['Position']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Awarded At"><?php echo htmlspecialchars($row['AwardedAt']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Prize"><?php echo htmlspecialchars($row['CompetitionPrize']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Actions">
                                            <div class="flex items-center gap-3">
                                                <a href="winners.update.php?id=<?php echo htmlspecialchars($row['WinnerID']); ?>" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                                    <i class="ri-edit-line text-lg"></i>
                                                </a>
                                                <a href="winners.delete.php?id=<?php echo htmlspecialchars($row['WinnerID']); ?>" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                                    <i class="ri-delete-bin-line text-lg"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="px-4 sm:px-6 py-4 text-center text-slate-400">No winners found.</td></tr>';
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

        // Ensure main content margin is set on page load
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });
    </script>
</body>
</html>