<?php 
include "../../Config/db.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E - Books - Discover Your Next Great Read</title>
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1), transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
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
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate, .parallax-bg {
                opacity: 1 !important;
                transform: none !important;
                transition-duration: 150ms !important;
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
    <?php include "../Components/nav.php";?>
    <!-- Hero Section -->
    <div class="px-6">
    <section class="pt-32 pb-24 px-6 relative">
        <div class="container mx-auto relative">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div id="hero-content" class="space-y-10 scroll-animate">
                    <div class="space-y-8">
                        

                        <h1 class="text-6xl lg:text-8xl font-black leading-none text-white">
                            Discover Your Next
                            <span class="text-emerald-400 block">Great Read</span>
                        </h1>

                        <p class="text-2xl text-slate-400 leading-relaxed max-w-2xl font-light">
                            Explore thousands of carefully curated books, join reading competitions, and connect with a passionate
                            community of readers worldwide.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6">
                        <a href="books.php" class="group relative overflow-hidden bg-emerald-500 hover:bg-emerald-600 text-white border-0 shadow-2xl shadow-emerald-500/20 px-10 py-6 text-xl font-semibold rounded-2xl transition-all duration-300 hover:scale-105 inline-flex items-center">
                            <span class="relative z-10 flex items-center">
                                Explore Books
                                <i class="ri-arrow-right-line w-6 h-6 ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                            </span>
                        </a>

                        <a href="competitions.php" class="group backdrop-blur-xl bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:bg-slate-700/50 hover:text-white hover:border-emerald-500/50 px-10 py-6 text-xl font-semibold rounded-2xl transition-all duration-300 hover:scale-105 inline-flex items-center">
                        <i class="ri-award-line w-5 h-5 mr-3 group-hover:text-emerald-400 transition-colors duration-300"></i>
                            Join Competition
                        </a>
                    </div>

                </div>

                <div id="hero-image" class="relative scroll-animate">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-blue-500/10 to-purple-500/20 rounded-3xl blur-3xl animate-pulse"></div>
                    <div class="relative backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                        <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Books Collection" class="rounded-2xl group-hover:scale-105 transition-transform duration-500 w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-3 gap-10">
                <div id="stat-0" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-12 text-center relative">
                        <div class="w-24 h-24 bg-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-book-3-line text-white text-4xl"></i>
                        </div>
                        <h3 class="text-5xl font-black text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300">
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
                        </h3>
                        <p class="text-slate-400 font-semibold text-xl">Books Published</p>
                    </div>
                </div>

                <div id="stat-1" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-12 text-center relative">
                        <div class="w-24 h-24 bg-blue-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-user-line text-white text-4xl"></i>
                        </div>
                        <h3 class="text-5xl font-black text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300">
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
                        </h3>
                        <p class="text-slate-400 font-semibold text-xl">Registered Users</p>
                    </div>
                </div>

                <div id="stat-2" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-12 text-center relative">
                        <div class="w-24 h-24 bg-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-medal-line text-white text-4xl"></i>
                        </div>
                        <h3 class="text-5xl font-black text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300">
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
                        </h3>
                        <p class="text-slate-400 font-semibold text-xl">Competition Winners</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Books Section -->
    <?php
    $query = "SELECT books.*, categories.CategoryName 
            FROM books 
            JOIN categories ON books.Category = categories.CategoryID 
            ORDER BY books.CreatedAt DESC 
            LIMIT 3";
    $result = $conn->query($query);
    ?>
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div id="books-header" class="text-center mb-20 scroll-animate">
                <h2 class="text-6xl font-black mb-8 text-white">
                    Latest <span class="text-emerald-400">Books</span>
                </h2>
                <p class="text-slate-400 text-2xl max-w-3xl mx-auto font-light leading-relaxed">
                    Discover our newest additions to the collection, carefully selected for our community
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <?php
                if ($result->num_rows > 0) {
                    $index = 0;
                    while ($row = $result->fetch_assoc()) {
                ?>
                <div id="book-<?php echo $index; ?>" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-6 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-0 relative">
                        <div class="relative mb-8 overflow-hidden">
                            <img src="../../uploads/Images/<?php echo $row['image']?>" alt="<?php echo htmlspecialchars($row['Title']); ?>" class="w-full h-96 object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent"></div>
                            <span class="absolute top-6 right-6 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-4 py-2 font-semibold rounded-full backdrop-blur-xl">
                                <?php echo htmlspecialchars($row['CategoryName']); ?>
                            </span>
                        </div>
                        <div class="px-8 pb-8">
                            <h3 class="font-bold text-2xl mb-3 text-white group-hover:text-emerald-400 transition-colors duration-300">
                                <?php echo htmlspecialchars($row['Title']); ?>
                            </h3>
                            <p class="text-slate-400 mb-6 text-lg">by <?php echo htmlspecialchars($row['Author']); ?></p>
                            <div class="flex items-center justify-between mb-8">
                                <span class="font-black text-2xl text-emerald-400">
                                    <?php echo ($row['Price'] == 0.00) ? 'Free' : '$' . number_format($row['Price'], 2); ?>
                                </span>
                            </div>
                            <a href="book-details.php?id=<?php echo $row['BookID']; ?>" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-4 text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 block text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                        $index++;
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Competition Section -->
    <?php
    $topWinners = [];
    $query = "
        SELECT w.Position, w.AwardedAt, u.Username, u.Email, u.City 
        FROM winners w
        JOIN users u ON w.UserID = u.UserID
        WHERE w.Position IN ('1st', '2nd', '3rd')
        ORDER BY w.AwardedAt DESC
        LIMIT 3
    ";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topWinners[] = $row;
        }
    }
    ?>
    <section class="py-24 relative">
        <div class="absolute inset-0 bg-slate-900/10"></div>
        <div class="container mx-auto relative">
            <div id="competition-header" class="text-center mb-20 scroll-animate">
                <h2 class="text-4xl lg:text-6xl font-black mb-8 text-white">
                    Join <span class="text-emerald-400">Competitions</span>
                </h2>
                <p class="text-slate-400 text-2xl max-w-3xl mx-auto font-light leading-relaxed">
                    Join our community challenges and compete with readers worldwide for amazing prizes
                </p>
            </div>

            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- Ongoing Competition -->
                <div id="ongoing-competition" class="backdrop-blur-xl bg-slate-800/40 border border-slate-600/50 shadow-lg hover:shadow-emerald-400/20 transition-all duration-500 group scroll-animate rounded-xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl"></div>
                    <div class="p-8 relative">
                        <?php
                        $query = "SELECT CompetitionID, Title, Description, StartDate, EndDate, Prize 
                                FROM competitions 
                                ORDER BY EndDate DESC 
                                LIMIT 1";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            $competition = $result->fetch_assoc();
                            $startDate = new DateTime($competition['StartDate']);
                            $endDate = new DateTime($competition['EndDate']);
                            $now = new DateTime("now", new DateTimeZone('Asia/Karachi')); // PKT timezone
                            $interval = ($now < $startDate) ? $now->diff($startDate) : $now->diff($endDate);
                            $status = ($now < $startDate) ? 'Upcoming' : ($now <= $endDate ? 'Ongoing' : 'Ended');
                            $countdown = ($status == 'Ongoing' || $status == 'Upcoming') ? sprintf("%02d:%02d:%02d:%02d", $interval->d, $interval->h, $interval->i, $interval->s) : '';
                        ?>
                        <div class="flex items-center mb-6">
                            <i class="ri-trophy-line text-amber-400 text-xl mr-3"></i>
                            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 px-3 py-1 font-medium text-sm rounded-full">
                                <?php echo $status; ?>
                            </span>
                        </div>
                        <h3 class="text-3xl font-bold mb-6 text-white group-hover:text-emerald-400 transition-colors duration-300">
                            <?php echo htmlspecialchars($competition['Title']); ?>
                        </h3>
                        <p class="text-slate-300 mb-8 text-base leading-relaxed">
                            <?php echo htmlspecialchars($competition['Description']); ?>
                        </p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center text-slate-300 text-base">
                                <i class="ri-time-line text-lg mr-2"></i>
                                <?php echo ($status == 'Upcoming') ? 'Starts in ' : ($status == 'Ongoing' ? 'Ends in ' : 'Ended'); ?>
                                &nbsp;
                                <span class="countdown"><?php echo ($status == 'Ongoing' || $status == 'Upcoming') ?  $countdown : ''; ?></span>
                            </div>
                        </div>
                        <a href="competitions.php" class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/30 py-4 text-lg font-medium rounded-lg transition-all duration-300 hover:scale-105 block text-center">
                            Join Competition
                        </a>
                        <?php } else { ?>
                        <h3 class="text-3xl font-bold mb-6 text-white group-hover:text-emerald-400 transition-colors duration-300">
                            No Active Competitions
                        </h3>
                        <p class="text-slate-300 mb-8 text-base leading-relaxed">
                            There are currently no ongoing competitions. Check back soon for new challenges!
                        </p>
                        <?php } ?>
                    </div>
                </div>
                <!-- Recent Winners -->
                <div id="recent-winners" class="backdrop-blur-xl bg-slate-800/40 border border-slate-600/50 shadow-lg hover:shadow-blue-400/20 transition-all duration-500 group scroll-animate rounded-xl">
                    <div class="absolute inset-0  bg-gradient-to-br from-emerald-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-xl"></div>
                    <div class="p-8 relative">
                        <h3 class="text-3xl font-bold mb-8 text-white group-hover:text-blue-400 transition-colors duration-300">
                            Recent Winners
                        </h3>
                        <div class="space-y-6">
                            <?php foreach ($topWinners as $index => $winner): ?>
                            <div id="winner-<?php echo $index; ?>" class="flex items-center space-x-4 p-4 rounded-lg bg-slate-700/30 hover:bg-slate-700/50 transition-all duration-300 group/item scroll-animate">
                                <div class="relative winner-avatar transition-all duration-300">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 text-white font-medium text-base rounded-full flex items-center justify-center">
                                        <?php echo strtoupper(substr($winner['Username'], 0, 2)); ?>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center text-xs font-bold text-white shadow">
                                        <?php echo substr($winner['Position'], 0, 1); ?>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-white text-base mb-1 group-hover/item:text-emerald-400 transition-colors duration-300">
                                        <?php echo htmlspecialchars($winner['Username']); ?>
                                    </h4>
                                    <p class="text-slate-300 text-sm">City: <?php echo htmlspecialchars($winner['City']); ?></p>
                                </div>
                                <div class="text-right">
                                    <i class="ri-medal-line text-amber-400 text-xl mx-auto mb-1 group-hover/item:scale-110 transition-transform duration-300"></i>
                                    <p class="text-slate-300 text-sm font-medium"><?php echo $winner['Position']; ?> Place</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    <!-- Footer -->
    <?php include "../Components/footer.php";?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const countdownSpan = document.querySelector(".countdown");

            if (countdownSpan && countdownSpan.textContent.trim() !== "") {
                let [days, hours, minutes, seconds] = countdownSpan.textContent.trim().split(":").map(Number);

                function updateCountdown() {
                    let totalSeconds =
                        days * 86400 +
                        hours * 3600 +
                        minutes * 60 +
                        seconds;

                    if (totalSeconds <= 0) {
                        countdownSpan.textContent = "00:00:00:00";
                        return;
                    }

                    totalSeconds--;

                    days = Math.floor(totalSeconds / 86400);
                    hours = Math.floor((totalSeconds % 86400) / 3600);
                    minutes = Math.floor((totalSeconds % 3600) / 60);
                    seconds = totalSeconds % 60;

                    countdownSpan.textContent =
                        String(days).padStart(2, '0') + ":" +
                        String(hours).padStart(2, '0') + ":" +
                        String(minutes).padStart(2, '0') + ":" +
                        String(seconds).padStart(2, '0');
                }

                setInterval(updateCountdown, 1000); // Update every second
            }
        });

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

        // Observe all scroll-animate elements
        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });

        // Parallax background effect
        let scrollY = 0;
        
        function updateParallax() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            scrollY = window.scrollY;
            
            const bg1 = document.getElementById('bg1');
            const bg2 = document.getElementById('bg2');
            const bg3 = document.getElementById('bg3');
            
            if (bg1) bg1.style.transform = `translateY(${scrollY * 0.1}px)`;
            if (bg2) bg2.style.transform = `translateY(${scrollY * -0.15}px)`;
            if (bg3) bg3.style.transform = `translateY(${scrollY * 0.05}px)`;
        }

        window.addEventListener('scroll', updateParallax);
        
        // Initialize parallax
        updateParallax();
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
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/Home.php"]');
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
            const mobileActiveLink = document.querySelector('nav a[href="home.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>