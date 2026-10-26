<?php
include "../../Config/db.php";
include "../Components/checkifadmin.php";

$sql = "
SELECT 
    s.SubmissionID, 
    s.SubmissionDate, 
    s.IsWinner, 
    s.TimeLimitExceeded,
    u.Username, 
    c.Title AS CompetitionTitle
FROM submissions s
JOIN users u ON s.UserID = u.UserID
JOIN competitions c ON s.CompetitionID = c.CompetitionID
ORDER BY s.SubmissionDate DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competition Submissions | E - Books</title>
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
                        button: "8px"
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
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2);
        }
        .table-responsive {
            max-width: 100%;
        }
        .table-responsive .submission-user, .table-responsive .submission-competition {
            min-width: 150px;
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
        @media (max-width: 1024px) {
            body{
                overflow: hidden;
            }
            .table-responsive {
                -webkit-overflow-scrolling: touch;
                max-height: 100vh;
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
                font-size: 0.75rem;
            }
            .table-responsive td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #94a3b8;
                width: 40%;
            }
            .table-responsive thead {
                position: sticky;
                top: 0;
                background-color: #1e293b;
                z-index: 10;
            }
            .table-responsive .submission-user, .table-responsive .submission-competition {
                min-width: 100px;
                max-width: none;
            }
        }
        @media (min-width: 640px) {
            .table-responsive .submission-user, .table-responsive .submission-competition {
                min-width: 120px;
            }
        }
        @media (min-width: 768px) {
            .table-responsive .submission-user, .table-responsive .submission-competition {
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
<body class="bg-slate-950 text-white font-['Inter'] flex min-h-screen">
    <div class="flex min-h-screen w-full">
        <!-- Sidebar -->
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="min-h-screen flex-1 transition-all duration-300 mt-16">
            <!-- Page Content -->
            <div class="pt-24 p-4 sm:p-6">
                <div id="submissions-page" class="container mx-auto scroll-animate">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Competition Submissions</h1>
                        <a href="newsubmission.php" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all duration-300 hover-scale">
                            <i class="ri-add-line mr-2"></i>Add Submission
                        </a>
                    </div>
                    <div class="table-responsive backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500">
                        <table class="w-full table-auto text-sm divide-y divide-slate-700/50">
                        <thead class="bg-slate-800/20 text-slate-400 hidden sm:table-header-group">
                        <tr>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider">#</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider submission-user">Username</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider submission-competition">Competition</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider hidden sm:table-cell">Date</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider">Winner</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider hidden sm:table-cell">Time Limit</th>
                                    <th class="px-3 sm:px-4 py-3 font-semibold tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50">
                                <?php
                                if (!$result) {
                                    error_log("Submissions query failed: " . $conn->error);
                                    echo '<tr><td colspan="7" class="text-center text-slate-400 py-12"><div class="flex flex-col items-center"><i class="ri-folder-open-line text-4xl text-slate-400 mb-2"></i><span class="text-lg font-medium">Error fetching data: ' . htmlspecialchars($conn->error) . '</span></div></td></tr>';
                                } elseif ($result->num_rows > 0) {
                                    $index = 0;
                                    while ($rows = $result->fetch_assoc()) {
                                ?>
                                <tr class="odd:bg-slate-800/20 hover:bg-slate-700/30 transition duration-200 scroll-animate" style="animation-delay: <?php echo $index * 0.1; ?>s;">
                                    <td class="px-3 sm:px-4 py-3 text-slate-300" data-label="#"> <?php echo htmlspecialchars($rows['SubmissionID']); ?></td>
                                    <td class="px-3 sm:px-4 py-3 font-medium text-white submission-user" data-label="Username"><?php echo htmlspecialchars($rows['Username']); ?></td>
                                    <td class="px-3 sm:px-4 py-3 text-slate-300 submission-competition" data-label="Competition"><?php echo htmlspecialchars($rows['CompetitionTitle']); ?></td>
                                    <td class="px-3 sm:px-4 py-3 text-slate-300 hidden sm:table-cell" data-label="Date"><?php echo htmlspecialchars($rows['SubmissionDate']); ?></td>
                                    <td class="px-3 sm:px-4 py-3" data-label="Winner">
                                        <?php if ($rows['IsWinner']): ?>
                                            <span class="inline-flex items-center px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl font-medium hover-scale transition duration-300">
                                                <i class="ri-trophy-line mr-1"></i> Winner
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 sm:px-4 py-3 hidden sm:table-cell" data-label="Time Limit">
                                        <?php if ($rows['TimeLimitExceeded']): ?>
                                            <span class="inline-flex items-center px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl font-medium hover-scale transition duration-300">
                                                <i class="ri-time-line mr-1"></i> Exceeded
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl font-medium hover-scale transition duration-300">
                                                <i class="ri-check-line mr-1"></i> On Time
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 sm:px-4 py-3" data-label="Actions">
                                        <div class="flex items-center gap-2">
                                            <a href="updatesubmission.php?id=<?php echo htmlspecialchars($rows['SubmissionID']); ?>" class="w-8 h-8 flex items-center justify-center text-emerald-400 hover:text-emerald-500 hover-scale rounded-full bg-slate-700/50 transition duration-300" title="Edit Submission">
                                                <i class="ri-edit-line text-base"></i>
                                            </a>
                                            <form method="POST" action="deletesubmission.php" onsubmit="return confirm('Are you sure you want to delete this submission?');" class="inline m-0 p-0">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($rows['SubmissionID']); ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-500 hover-scale rounded-full bg-slate-700/50 transition duration-300 border-0 cursor-pointer" title="Delete Submission">
                                                    <i class="ri-delete-bin-line text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                        $index++;
                                    }
                                } else {
                                    echo '<tr><td colspan="7" class="text-center text-slate-400 py-12"><div class="flex flex-col items-center"><i class="ri-folder-open-line text-4xl text-slate-400 mb-2"></i><span class="text-lg font-medium">No submissions found.</span></div></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
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
            const tableContainer = document.querySelector('#submissions-page .table-responsive');
            console.log('Table height:', tableContainer.offsetHeight, 'px');
            console.log('Table width:', tableContainer.offsetWidth, 'px');
            console.log('Table scrollable:', tableContainer.scrollHeight > tableContainer.clientHeight ? 'Yes (Y-axis)' : 'No');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            console.log('Sidebar state:', sidebar.classList.contains('collapsed') ? 'Collapsed' : 'Expanded');
            console.log('Main content margin-left:', mainContent.style.marginLeft || getComputedStyle(mainContent).marginLeft);
        });
    </script>
</body>
</html>