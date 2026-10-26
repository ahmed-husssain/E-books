<?php
include "../../Config/db.php";

session_start();

$currentDate = new DateTime();

// Fetch Ongoing competitions
$queryOngoing = $conn->prepare("SELECT CompetitionID, Title, Description, Type, StartDate, EndDate, Prize FROM competitions WHERE StartDate <= ? AND EndDate >= ? ORDER BY StartDate DESC");
if (!$queryOngoing) {
    die("Query preparation failed: " . $conn->error);
}
$currentDateStr = $currentDate->format('Y-m-d H:i:s');
$queryOngoing->bind_param("ss", $currentDateStr, $currentDateStr);
$queryOngoing->execute();
$ongoingCompetitions = $queryOngoing->get_result()->fetch_all(MYSQLI_ASSOC);
$queryOngoing->close();

// Fetch Upcoming competitions
$queryUpcoming = $conn->prepare("SELECT CompetitionID, Title, Description, Type, StartDate, EndDate, Prize FROM competitions WHERE StartDate > ? ORDER BY StartDate DESC");
if (!$queryUpcoming) {
    die("Query preparation failed: " . $conn->error);
}
$queryUpcoming->bind_param("s", $currentDateStr);
$queryUpcoming->execute();
$upcomingCompetitions = $queryUpcoming->get_result()->fetch_all(MYSQLI_ASSOC);
$queryUpcoming->close();

// Fetch Ended competitions
$queryEnded = $conn->prepare("SELECT CompetitionID, Title, Description, Type, StartDate, EndDate, Prize FROM competitions WHERE EndDate < ? ORDER BY StartDate DESC");
if (!$queryEnded) {
    die("Query preparation failed: " . $conn->error);
}
$queryEnded->bind_param("s", $currentDateStr);
$queryEnded->execute();
$endedCompetitions = $queryEnded->get_result()->fetch_all(MYSQLI_ASSOC);
$queryEnded->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions - E - Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
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
                        'none': '0px',
                        'sm': '4px',
                        DEFAULT: '8px',
                        'md': '12px',
                        'lg': '16px',
                        'xl': '20px',
                        '2xl': '24px',
                        '3xl': '32px',
                        'full': '9999px',
                        'button': '8px'
                    }
                }
            }
        }
    </script>
    <style>
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
            .scroll-animate, .competition-card {
                opacity: 1 !important;
                transform: none !important;
                animation: none !important;
                transition-duration: 150ms !important;
            }
        }
        .competition-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .competition-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .status-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 3;
        }
        .type-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 3;
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
    <!-- Page Header -->
    <?php
include "../../Config/db.php";
include "../Components/nav.php";

$isLoggedIn = isset($_SESSION['email']);
$isVerified = true;

if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT Verified FROM users WHERE Email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $isVerified = $row['Verified'];
    }
    $stmt->close();
}
?>
<div class="sm:px-6">
    <section class="pt-32 pb-12 px-6 relative">
        <div class="container mx-auto relative">
            <div id="page-header" class="text-center scroll-animate">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 text-white">
                Writing <span class="text-emerald-400">Competitions</span>
            </h1>

                <p class="text-slate-400 text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Showcase your creativity, compete with fellow writers, and win amazing prizes
                </p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="pb-8 px-6">
        <div class="container mx-auto">
            <div id="search-section" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-8 shadow-2xl scroll-animate">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="flex-1 relative">
                    <i class="ri-search-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" id="searchInput" placeholder="Search competitions..." class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl pl-12 pr-4 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg">
                    </div>
                    <div class="flex flex-wrap justify-center gap-4">
                        <button class="tab-btn active px-8 py-4 rounded-2xl font-bold text-lg bg-emerald-500 text-white transition-all duration-300" data-tab="ongoing">
                        <i class="ri-time-line"></i> Ongoing
                        </button>
                        <button class="tab-btn px-8 py-4 rounded-2xl font-bold text-lg bg-slate-700/50 text-slate-300 hover:bg-slate-600/50 transition-all duration-300" data-tab="upcoming">
                        <i class="ri-calendar-event-line"></i> Upcoming
                        </button>
                        <button class="tab-btn px-8 py-4 rounded-2xl font-bold text-lg bg-slate-700/50 text-slate-300 hover:bg-slate-600/50 transition-all duration-300" data-tab="ended">
                        <i class="ri-close-circle-line"></i> Ended
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Competition Content -->
    <section class="pb-24 px-6">
        <div class="container mx-auto">
            <!-- Ongoing Tab -->
            <div id="ongoing" class="tab-content active">
                <?php if (empty($ongoingCompetitions)): ?>
                    <p class="text-center text-slate-300 col-span-full">No ongoing competitions found.</p>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($ongoingCompetitions as $comp):
                            $endDate = new DateTime($comp['EndDate']);
                            $timeLeft = $currentDate->diff($endDate)->format('%a days');
                        ?>
                            <div class="competition-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="type-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <?php echo htmlspecialchars($comp['Type']); ?>
                                </div>
                                <div class="status-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <i class="ri-time-line mr-1"></i> Ongoing
                                </div>
                                <div class="p-8 relative flex flex-col h-full mt-12">
                                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300 line-clamp-2 h-[70px]"><?php echo htmlspecialchars($comp['Title']); ?></h3>
                                    <div>
                                    <p class="text-slate-400 mb-6 leading-relaxed line-clamp-3 h-[50px]"><?php echo htmlspecialchars($comp['Description']); ?></p>
                                    </div>
                                    <div class="space-y-4 mb-6">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Start Date</span>
                                            <span class="text-slate-300 font-semibold"><?php echo date('F j, Y', strtotime($comp['StartDate'])); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Time Left</span>
                                            <span class="text-red-400 font-semibold"><?php echo htmlspecialchars($timeLeft); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Prize Pool</span>
                                            <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($comp['Prize']); ?></span>
                                        </div>
                                    </div>
                                    <?php if (isset($_SESSION['email'])): ?>
                                        <a href="submission.php?comp_id=<?php echo $comp['CompetitionID']; ?>" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/20 py-3 text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 block text-center">
                                            Submit Your Entry
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upcoming Tab -->
            <div id="upcoming" class="tab-content">
                <?php if (empty($upcomingCompetitions)): ?>
                    <p class="text-center text-slate-300 col-span-full">No upcoming competitions found.</p>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($upcomingCompetitions as $comp):
                            $startDate = new DateTime($comp['StartDate']);
                            $startsIn = $currentDate->diff($startDate)->format('%a days');
                        ?>
                            <div class="competition-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="type-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <?php echo htmlspecialchars($comp['Type']); ?>
                                </div>
                                <div class="status-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <i class="ri-time-line mr-1"></i> Upcoming
                                </div>
                                <div class="p-8 relative flex flex-col h-full mt-12">
                                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300 line-clamp-2 h-[70px]"><?php echo htmlspecialchars($comp['Title']); ?></h3>
                                    <p class="text-slate-400 mb-6 leading-relaxed line-clamp-3 h-[50px]"><?php echo htmlspecialchars($comp['Description']); ?></p>
                                    <div class="space-y-4 mb-6">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Start Date</span>
                                            <span class="text-slate-300 font-semibold"><?php echo date('F j, Y', strtotime($comp['StartDate'])); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Starts In</span>
                                            <span class="text-blue-400 font-semibold"><?php echo htmlspecialchars($startsIn); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Prize Pool</span>
                                            <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($comp['Prize']); ?></span>
                                        </div>
                                    </div>
                                    <a href="#" class="w-full bg-slate-600 text-slate-300 py-3 text-lg font-semibold rounded-xl cursor-not-allowed block text-center">
                                        View Results
                                    </a>    
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Ended Tab -->
            <div id="ended" class="tab-content">
                <?php if (empty($endedCompetitions)): ?>
                    <p class="text-center text-slate-300 col-span-full">No ended competitions found.</p>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($endedCompetitions as $comp): ?>
                            <div class="competition-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl opacity-75">
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="type-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <?php echo htmlspecialchars($comp['Type']); ?>
                                </div>
                                <div class="status-badge px-4 py-2 border text-xs font-semibold rounded-full shadow-sm">
                                    <i class="ri-time-line mr-1"></i> Ended
                                </div>
                                <div class="p-8 relative flex flex-col h-full mt-12">
                                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300 line-clamp-2 h-[70px]"><?php echo htmlspecialchars($comp['Title']); ?></h3>
                                    <p class="text-slate-400 mb-6 leading-relaxed line-clamp-3 h-[50px]"><?php echo htmlspecialchars($comp['Description']); ?></p>
                                    <div class="space-y-4 mb-6">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Start Date</span>
                                            <span class="text-slate-300 font-semibold"><?php echo date('F j, Y', strtotime($comp['StartDate'])); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Ended On</span>
                                            <span class="text-slate-300 font-semibold"><?php echo date('F j, Y', strtotime($comp['EndDate'])); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-400">Prize Pool</span>
                                            <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($comp['Prize']); ?></span>
                                        </div>
                                    </div>
                                    <a href="#" class="w-full bg-slate-600 text-slate-300 py-3 text-lg font-semibold rounded-xl cursor-not-allowed block text-center">
                                        View Results
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    </div>
    <?php 
    include "../Components/footer.php";
    $conn->close(); 
    ?>

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

        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetTab = btn.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                tabBtns.forEach(b => {
                    b.classList.remove('active', 'bg-emerald-500', 'text-white');
                    b.classList.add('bg-slate-700/50', 'text-slate-300', 'hover:bg-slate-600/50');
                });
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                btn.classList.add('active', 'bg-emerald-500', 'text-white');
                btn.classList.remove('bg-slate-700/50', 'text-slate-300', 'hover:bg-slate-600/50');
                document.getElementById(targetTab).classList.add('active');
                
                // Trigger search filter after tab switch
                filterCompetitions();
            });
        });
        

        // Competition search
        const searchInput = document.getElementById('searchInput');
        const competitionCards = document.querySelectorAll('.competition-card');

        function filterCompetitions() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const activeTab = document.querySelector('.tab-content.active');
            const cards = activeTab ? activeTab.querySelectorAll('.competition-card') : competitionCards;
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                if (searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide "No competitions found" message
            const noResultsMsg = activeTab.querySelector('.text-center.text-slate-300.col-span-full');
            if (noResultsMsg) {
                noResultsMsg.style.display = visibleCount > 0 ? 'none' : 'block';
            }
        }

        searchInput.addEventListener('input', filterCompetitions);

        // Initialize search
        filterCompetitions();
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
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/competitions.php"]');
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
            const mobileActiveLink = document.querySelector('nav a[href="competitions.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>