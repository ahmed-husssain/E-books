<?php 
include "../../Config/db.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - E - Books</title>
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1), transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .bg-blur {
            filter: blur(3rem);
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate {
                opacity: 1 !important;
                transform: none !important;
                transition-duration: 150ms !important;
            }
        }
        /* Center the last two cards in the second row for five items on large screens */
        #team-grid.five-items {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            justify-items: center;
        }

        @media (min-width: 1024px) {
            #team-grid.five-items > :nth-child(4),
            #team-grid.five-items > :nth-child(5) {
                grid-column: span 1;
            }
            #team-grid.five-items {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 3rem; /* Matches gap-12 */
            }
            #team-grid.five-items > :nth-child(1),
            #team-grid.five-items > :nth-child(2),
            #team-grid.five-items > :nth-child(3) {
                flex: 0 0 30%;
                max-width: 20rem; /* Matches max-w-sm */
            }
            #team-grid.five-items > :nth-child(4),
            #team-grid.five-items > :nth-child(5) {
                flex: 0 0 30%;
                max-width: 20rem; /* Matches max-w-sm */
            }
        }

        @media (max-width: 1023px) and (min-width: 640px) {
            #team-grid.five-items {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                justify-items: center;
            }
            #team-grid.five-items > :nth-child(5) {
                grid-column: 1 / -1;
                justify-self: center;
            }
        }
        @media (max-width: 640px) and (min-width: 140px) {
            #team-grid.five-items {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                justify-items: center;
            }
            #team-grid.five-items > :nth-child(5) {
                grid-column: 1 / -1;
                justify-self: center;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">

<?php include "../Components/nav.php";?>
    <div class="sm:px-6">
    <!-- Page Header -->
    <section class="pt-32 pb-12 px-6 relative">
        <div class="container mx-auto relative">
            <div id="page-header" class="text-center scroll-animate">
                <h1 class="text-5xl lg:text-6xl font-black mb-6 text-white">
                    About <span class="text-emerald-400">E - Books</span>
                </h1>
                <p class="text-slate-400 text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Discover the story behind our passion for books and our mission to connect readers worldwide
                </p>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div id="story-content" class="space-y-8 scroll-animate">
                    <h2 class="text-5xl font-black text-white mb-8">
                        Our <span class="text-emerald-400">Story</span>
                    </h2>
                    <p class="text-slate-400 text-xl leading-relaxed">
                        E - Books was born from a simple belief: that books have the power to transform lives, spark imagination, and connect people across the globe. Founded in 2020 by a group of passionate book lovers, we set out to create more than just another online bookstore.
                    </p>
                    <p class="text-slate-400 text-xl leading-relaxed">
                        We envisioned a digital sanctuary where readers could discover their next great adventure, participate in meaningful competitions, and become part of a vibrant community that celebrates the written word.
                    </p>
                    <div class="flex items-center space-x-8 pt-6">
                        <div class="text-center">
                            <div class="text-4xl font-black text-emerald-400 mb-2">2020</div>
                            <div class="text-slate-400 font-medium">Founded</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-black text-emerald-400 mb-2">
                                <?php
                                $query = "SELECT COUNT(*) AS TotalUsers FROM users";
                                $result = $conn->query($query);
                                if ($result) {
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['TotalUsers']);
                                } else {
                                    echo "Error: " . $conn->error;
                                }
                                ?>
                            </div>
                            <div class="text-slate-400 font-medium">Happy Readers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-black text-emerald-400 mb-2">
                                <?php
                                $query = "SELECT COUNT(*) AS TotalBooks FROM books";
                                $result = $conn->query($query);
                                if ($result) {
                                    $row = $result->fetch_assoc();
                                    echo number_format($row['TotalBooks']);
                                } else {
                                    echo "Error: " . $conn->error;
                                }
                                ?>
                            </div>
                            <div class="text-slate-400 font-medium">Books Available</div>
                        </div>
                    </div>
                </div>

                <div id="story-image" class="relative scroll-animate">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-blue-500/10 to-purple-500/20 rounded-3xl blur-3xl animate-pulse"></div>
                    <div class="relative backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 group">
                        <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRfgG0T7wdlLycHJVH9VyzZZsn9OITtq-WeKA&s" alt="Book Collection" class="rounded-2xl group-hover:scale-105 transition-transform duration-500 w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Mission -->
                <div id="mission-card" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="p-12 relative">
                        <div class="w-20 h-20 bg-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-earth-line text-white text-4xl"></i>
                        </div>
                        <h3 class="text-4xl font-black text-white mb-6 text-center group-hover:text-emerald-400 transition-colors duration-300">
                            Our Mission
                        </h3>
                        <p class="text-slate-400 text-lg leading-relaxed text-center">
                            To democratize access to literature and create a global community where every reader can discover, share, and celebrate the transformative power of books. We believe that great stories should be accessible to everyone, everywhere.
                        </p>
                    </div>
                </div>

                <!-- Vision -->
                <div id="vision-card" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-blue-500/20 transition-all duration-500 group scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="p-12 relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-eye-line text-white text-4xl"></i>
                        </div>
                        <h3 class="text-4xl font-black text-white mb-6 text-center group-hover:text-blue-400 transition-colors duration-300">
                            Our Vision
                        </h3>
                        <p class="text-slate-400 text-lg leading-relaxed text-center">
                            To become the world's most beloved literary platform, where millions of readers connect through shared stories, participate in exciting competitions, and discover books that change their lives forever.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div id="team-header" class="text-center mb-20 scroll-animate">
                <h2 class="text-6xl font-black mb-8 text-white">
                    Meet Our <span class="text-emerald-400">Team</span>
                </h2>
                <p class="text-slate-400 text-2xl max-w-3xl mx-auto font-light leading-relaxed">
                    The passionate individuals behind E - Books who work tirelessly to bring you the best reading experience
                </p>
            </div>
            <div id="team-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 justify-items-center">
                <?php
                $sql = "SELECT name, title FROM developers";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl w-full max-w-sm flex flex-col justify-between">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="flex flex-col justify-between h-full p-8 text-center relative">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/30 group-hover:scale-110 transition-all duration-500">
                                <span class="text-4xl font-bold text-white">
                                    <?php echo strtoupper(substr($row['name'], 0, 1) . substr($row['name'], strpos($row['name'] . ' ', ' ') + 1, 1)); ?>
                                </span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors duration-300">
                            <?php echo $row['name']; ?>
                        </h3>
                        <p class="text-emerald-400 font-semibold mb-4">
                            <?php echo $row['title']; ?>
                        </p>
                    </div>
                </div>
                <?php  
                    }
                }
                $conn->close();
                ?>
            </div>
        </div>
    </section>
    <!-- Values Section -->
    <section class="py-24 px-6 relative">
        <div class="container mx-auto">
            <div id="values-header" class="text-center mb-20 scroll-animate">
                <h2 class="text-6xl font-black mb-8 text-white">
                    Our <span class="text-emerald-400">Values</span>
                </h2>
                <p class="text-slate-400 text-2xl max-w-3xl mx-auto font-light leading-relaxed">
                    The principles that guide everything we do at E - Books
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Value 1 -->
                <div id="value-1" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-8 text-center relative">
                        <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-emerald-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-team-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors duration-300">
                            Community First
                        </h3>
                        <p class="text-slate-400 leading-relaxed">
                            We prioritize our readers' needs and foster meaningful connections within our community.
                        </p>
                    </div>
                </div>

                <!-- Value 2 -->
                <div id="value-2" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-blue-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-8 text-center relative">
                        <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-shield-check-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 group-hover:text-blue-400 transition-colors duration-300">
                            Quality & Trust
                        </h3>
                        <p class="text-slate-400 leading-relaxed">
                            We curate only the finest books and maintain the highest standards in everything we do.
                        </p>
                    </div>
                </div>

                <!-- Value 3 -->
                <div id="value-3" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-purple-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-8 text-center relative">
                        <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-lightbulb-flash-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 group-hover:text-purple-400 transition-colors duration-300">
                            Innovation
                        </h3>
                        <p class="text-slate-400 leading-relaxed">
                            We continuously evolve our platform to provide cutting-edge features for our readers.
                        </p>
                    </div>
                </div>

                <!-- Value 4 -->
                <div id="value-4" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-amber-500/20 transition-all duration-700 hover:-translate-y-4 group overflow-hidden relative scroll-animate rounded-2xl">
                    <div class="absolute inset-0 bg-amber-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="p-8 text-center relative">
                        <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i class="ri-global-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-4 group-hover:text-amber-400 transition-colors duration-300">
                            Accessibility
                        </h3>
                        <p class="text-slate-400 leading-relaxed">
                            We believe great literature should be accessible to everyone, regardless of background or ability.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    <!-- Footer -->
    <?php include "../Components/footer.php"; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const teamGrid = document.getElementById("team-grid");
            const teamMembers = teamGrid.children.length;
            if (teamMembers === 5) {
                teamGrid.classList.add("five-items");
            }
        });
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
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
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/About.php"]');
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
            const mobileActiveLink = document.querySelector('nav a[href="about.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>