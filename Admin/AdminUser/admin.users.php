<?php
include "../../Config/db.php";
include "../Components/checkifadmin.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | E - Books</title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
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
        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        .table-responsive table {
            width: 100%;
            max-width: 100%;
        }
        .table-responsive tr {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.5), rgba(4, 120, 87, 0.1));
            transition: all 0.3s ease;
        }
        .table-responsive tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.2);
        }
        .table-responsive .action-btn {
            background: rgba(51, 65, 85, 0.5);
            transition: all 0.3s ease;
        }
        .table-responsive .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }
        /* Mobile View (max-width: 639px) */
        @media (max-width: 639px) {
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                max-height: 24rem;
                overflow-y: auto;
                width: 100%;
                max-width: 100%;
            }
            .table-responsive table {
                width: 100%;
                max-width: 100%;
            }
            .table-responsive tr {
                display: block;
                border: 1px solid rgba(51, 65, 85, 0.5);
                border-radius: 16px;
                background: linear-gradient(135deg, rgba(30, 41, 59, 0.5), rgba(4, 120, 87, 0.1));
                margin-bottom: 1rem;
                padding: 1rem;
                visibility: visible;
                opacity: 1;
            }
            .table-responsive td {
                display: block;
                width: 100%;
                max-width: 100%;
                padding: 0.5rem 0;
                text-align: left;
                position: relative;
                visibility: visible;
                opacity: 1;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #94a3b8;
                display: block;
                margin-bottom: 0.25rem;
            }
            .table-responsive thead {
                display: none;
            }
            .table-responsive .user-name, .table-responsive .user-email {
                max-width: 100%;
            }
            #roleFilter {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                padding: 0.5rem;
            }
            #roleFilter .filter-btn {
                padding: 0.5rem 1rem;
            }
            .table-responsive .action-btn {
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
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto;
                width: 100%;
                max-width: 100%;
            }
            .table-responsive table {
                width: 100%;
                min-width: 800px;
            }
            .table-responsive th, .table-responsive td {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
                vertical-align: middle;
            }
            .table-responsive .user-name, .table-responsive .user-email {
                max-width: 130px;
            }
            .table-responsive .hidden.sm\\:table-cell {
                display: table-cell;
            }
            .table-responsive td .flex.items-center.gap-3 {
                gap: 1.5rem;
            }
            .table-responsive .action-btn {
                width: 40px;
                height: 40px;
            }
            .table-responsive thead {
                position: sticky;
                top: 0;
                background-color: #1e293b;
                z-index: 10;
            }
            #roleFilter {
                flex-direction: row;
                align-items: center;
            }
            .scroll-animate {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Desktop View (min-width: 1024px) */
        @media (min-width: 1024px) {
            .table-responsive {
                overflow-x: hidden;
                width: 100%;
                max-width: 100%;
            }
            .table-responsive table {
                width: 100%;
                max-width: 100%;
            }
            .table-responsive th, .table-responsive td {
                font-size: 1rem;
                padding: 1rem 1.5rem;
                vertical-align: middle;
            }
            .table-responsive .user-name, .table-responsive .user-email {
                max-width: 150px;
            }
            .table-responsive .hidden.sm\\:table-cell {
                display: table-cell;
            }
            .table-responsive .action-btn {
                width: 44px;
                height: 44px;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white font-inter">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="ml-20 sm:ml-64 min-h-screen flex-1 transition-all duration-300">
            <!-- Page Content -->
            <div class="pt-20 sm:pt-24 p-4 sm:p-6 relative">
                <div id="users-page" class="page active container mx-auto">
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 sm:mb-12 mt-3 gap-4 scroll-animate">
                        <h1 class="text-3xl sm:text-4xl font-black text-white">User Management</h1>
                        <!-- Role Filter Buttons -->
                        <div class="flex flex-wrap gap-2 sm:gap-3 p-4 bg-slate-800/30 backdrop-blur-2xl border border-slate-700/50 rounded-xl" id="roleFilter">
                            <button onclick="filterUsers(this, 'all')" class="filter-btn px-4 py-2 bg-emerald-500 text-white rounded-full text-sm font-medium hover:bg-emerald-600 active:bg-emerald-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">All</button>
                            <button onclick="filterUsers(this, 'Admin')" class="filter-btn px-4 py-2 bg-slate-700/50 text-slate-300 rounded-full text-sm font-medium hover:bg-slate-600 active:bg-slate-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Admins</button>
                            <button onclick="filterUsers(this, 'User')" class="filter-btn px-4 py-2 bg-slate-700/50 text-slate-300 rounded-full text-sm font-medium hover:bg-slate-600 active:bg-slate-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Users</button>
                        </div>
                        <a href="newuser.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white px-4 sm:px-6 py-2 sm:py-3 text-base sm:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 inline-flex items-center">
                            <span class="relative z-10 flex items-center">
                                <i class="ri-add-line mr-2"></i>
                                Add New User
                            </span>
                        </a>
                    </div>
                    <!-- Users Table -->
                    <div class="backdrop-blur-3xl bg-slate-800/40 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
                        <div class="overflow-x-auto overflow-y-hidden">
                            <table class="w-full table-responsive">
                                <thead>
                                    <tr class="text-left text-sm sm:text-base text-slate-400 bg-slate-800/20">
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">ID</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold user-name">Name</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold user-email">Email</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hidden sm:table-cell">City</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hidden sm:table-cell">Registered On</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Role</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Status</th>
                                        <th class="px-4 sm:px-6 py-3 sm:py-4 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM users";
                                    $data = $conn->query($sql);
                                    if (!$data) {
                                        error_log("Users query failed: " . $conn->error);
                                        echo "<tr><td colspan='8' class='px-4 sm:px-6 py-4 text-center text-slate-400'>Error fetching data: " . htmlspecialchars($conn->error) . "</td></tr>";
                                    } elseif ($data->num_rows > 0) {
                                        while ($rows = $data->fetch_assoc()) {
                                    ?>
                                    <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all duration-300 scroll-animate user-row" data-role="<?php echo htmlspecialchars($rows['Role']); ?>">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="ID"><?php echo htmlspecialchars($rows['UserID']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 user-name" data-label="Name"><?php echo htmlspecialchars($rows['Username']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 user-email" data-label="Email">
                                            <span class="text-sm font-medium px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full"><?php echo htmlspecialchars($rows['Email']); ?></span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 hidden sm:table-cell" data-label="City"><?php echo htmlspecialchars($rows['City'] ?? 'N/A'); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300 hidden sm:table-cell" data-label="Registered On"><?php echo date('Y-m-d', strtotime($rows['DateRegistered'])); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-base text-slate-300" data-label="Role"><?php echo htmlspecialchars($rows['Role']); ?></td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Status">
                                            <span class="text-sm font-medium px-3 py-1 <?php echo ($rows['Verified'] == 0) ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'; ?> rounded-full">
                                                <?php echo ($rows['Verified'] == 0) ? 'Unverified' : 'Verified'; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Actions">
                                            <div class="flex items-center gap-3">
                                                <a href="userupdate.php?id=<?php echo htmlspecialchars($rows['UserID']); ?>" class="action-btn w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-emerald-400 rounded-full transition-all duration-300">
                                                    <i class="ri-edit-line text-lg"></i>
                                                </a>
                                                <form method="POST" action="userdelete.php" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline m-0 p-0">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($rows['UserID']); ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                                    <button type="submit" class="action-btn w-12 h-12 flex items-center justify-center text-slate-400 hover:bg-slate-700/50 hover:text-red-400 rounded-full transition-all duration-300">
                                                        <i class="ri-delete-bin-line text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='px-4 sm:px-6 py-4 text-center text-slate-400'>No users found.</td></tr>";
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

            // Debug table and sidebar
            const tableContainer = document.querySelector('#users-page .table-responsive');
            console.log('Table width:', tableContainer.offsetWidth, 'px');
            console.log('Viewport width:', window.innerWidth, 'px');
            console.log('Table scrollable:', tableContainer.scrollWidth > tableContainer.clientWidth ? 'Yes (X-axis)' : 'No');
            console.log('Table height:', tableContainer.offsetHeight, 'px');
            console.log('Table scrollable:', tableContainer.scrollHeight > tableContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });

        // Filter script
        function filterUsers(button, role) {
            const rows = document.getElementsByClassName('user-row');
            for (let i = 0; i < rows.length; i++) {
                const userRole = rows[i].getAttribute('data-role');
                rows[i].style.display = (role === 'all' || userRole.toLowerCase() === role.toLowerCase()) ? '' : 'none';
            }
            const buttons = document.getElementsByClassName('filter-btn');
            for (let j = 0; j < buttons.length; j++) {
                buttons[j].classList.remove('bg-emerald-500', 'text-white');
                buttons[j].classList.add('bg-slate-700/50', 'text-slate-300');
            }
            button.classList.remove('bg-slate-700/50', 'text-slate-300');
            button.classList.add('bg-emerald-500', 'text-white');
        }
    </script>
</body>
</html>