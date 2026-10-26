<?php
include "../../Config/db.php";
include "../Components/checkifadmin.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions Management | E - Books</title>
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
        }
        @media (min-width: 640px) {
            .table-responsive th, .table-responsive td {
                font-size: 0.875rem;
                padding: 0.75rem 1.5rem;
            }
            .table-responsive .competition-title {
                min-width: 120px;
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
        <main id="main-content" class="mt-16 min-h-screen flex-1 transition-all duration-300">
            <!-- Page Content -->
            <div class="pt-24 p-4 sm:p-6 relative">
                <div id="competitions-page" class="page active container mx-auto">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 sm:mb-12 mt-3 scroll-animate">
                        <h1 class="text-3xl sm:text-4xl font-black text-white">Competitions Management</h1>
                        <a href="newcompetition.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center mt-4 sm:mt-0">
                            <span class="relative z-10 flex items-center">
                                <i class="ri-add-line mr-2"></i>
                                Add New Competition
                            </span>
                        </a>
                    </div>
                    <!-- Competitions Table -->
                    <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
                        <div class="overflow-x-auto overflow-y-hidden">
                            <table class="w-full table-responsive">
                                <thead>
                                    <tr class="text-left text-sm sm:text-base text-slate-400 bg-slate-800/20">
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">ID</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold competition-title">Title</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hidden sm:table-cell">Type</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Status</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Start Date</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">End Date</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Prize</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM competitions";
                                    $data = $conn->query($sql);
                                    if (!$data) {
                                        error_log("Competitions query failed: " . $conn->error);
                                        echo "<tr><td colspan='8' class='px-4 sm:px-6 py-4 text-center text-slate-400'>Error fetching data: " . htmlspecialchars($conn->error) . "</td></tr>";
                                    } elseif ($data->num_rows > 0) {
                                        while ($rows = $data->fetch_assoc()) {
                                    ?>
                                    <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all duration-300 scroll-animate">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300" data-label="ID"><?php echo htmlspecialchars($rows['CompetitionID']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300 competition-title" data-label="Title"><?php echo htmlspecialchars($rows['Title']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300 hidden sm:table-cell" data-label="Type"><?php echo htmlspecialchars($rows['Type']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Status">
                                            <?php
                                            $today = date('Y-m-d');
                                            $start = $rows['StartDate'];
                                            $end = $rows['EndDate'];
                                            if ($today < $start) {
                                                $status = 'Upcoming';
                                                $badge = 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30';
                                            } elseif ($today >= $start && $today <= $end) {
                                                $status = 'Ongoing';
                                                $badge = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                            } else {
                                                $status = 'Ended';
                                                $badge = 'bg-red-500/20 text-red-400 border border-red-500/30';
                                            }
                                            ?>
                                            <span class="text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 rounded-full <?php echo $badge; ?>">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300" data-label="Start Date"><?php echo htmlspecialchars($rows['StartDate']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300" data-label="End Date"><?php echo htmlspecialchars($rows['EndDate']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-sm sm:text-base text-slate-300" data-label="Prize"><?php echo htmlspecialchars($rows['Prize']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Actions">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <a href="competitions.update.php?id=<?php echo htmlspecialchars($rows['CompetitionID']); ?>" class="w-8 sm:w-12 h-8 sm:h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                                    <i class="ri-edit-line text-base sm:text-lg"></i>
                                                </a>
                                                <form method="POST" action="competitions.delete.php" onsubmit="return confirm('Are you sure you want to delete this competition?');" class="inline m-0 p-0">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($rows['CompetitionID']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="w-8 sm:w-12 h-8 sm:h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                                        <i class="ri-delete-bin-line text-base sm:text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='px-4 sm:px-6 py-4 text-center text-slate-400'>No competitions found.</td></tr>";
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

        // Debug table and sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const tableContainer = document.querySelector('#competitions-page .table-responsive');
            console.log('Table height:', tableContainer.offsetHeight, 'px');
            console.log('Table scrollable:', tableContainer.scrollHeight > tableContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });
    </script>
</body>
</html>