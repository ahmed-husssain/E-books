<?php 
include "../../Config/db.php";
session_start();

$sql = "SELECT c.CategoryID, c.CategoryName, COUNT(b.BookID) AS BookCount,
        (SELECT b2.image 
         FROM books b2 
         WHERE b2.Category = c.CategoryID 
         ORDER BY b2.BookID DESC 
         LIMIT 1) AS LatestBookImage
        FROM categories c
        LEFT JOIN books b ON b.Category = c.CategoryID
        GROUP BY c.CategoryID, c.CategoryName
        ORDER BY c.CategoryName";
$result = $conn->query($sql);
if ($result === false) {
    die("Query execution failed: " . $conn->error);
}

$all_categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all_categories[] = $row;
    }
}

$author_counts = [];
foreach ($all_categories as $category) {
    $category_id = $category['CategoryID'];

    // Unique author count
    $query = $conn->prepare("SELECT COUNT(DISTINCT Author) AS AuthorCount FROM books WHERE Category = ?");
    if ($query) {
        $query->bind_param("i", $category_id);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $author_counts[$category_id] = $row['AuthorCount'] ?? 0;
        $query->close();
    } else {
        $author_counts[$category_id] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Categories - E - Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
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
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background-color: #020617; /* slate-950 */
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
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1), transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .book-container {
            perspective: 1000px;
            width: 100%;
            aspect-ratio: 3 / 4; /* Standard book cover ratio */
            margin: 0 auto;
            max-width: 240px; /* was 280px+ */
            max-height: 280px; /* was 300px+ */        
        }
        .book {
            transform-style: preserve-3d;
            transition: transform 0.5s ease-out, box-shadow 0.3s ease;
            width: 100%;
            height: 100%;
        }
        .book:hover {
            transform: rotateY(10deg) rotateX(5deg);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
        .book img {
            width: 100%;
            height: 100%; /* Fill container */
            object-fit: cover;
            border-radius: 16px; /* Match rounded-2xl */
            transition: brightness 0.3s ease;
        }
        .book:hover img {
            filter: brightness(1.1);
        }
        .category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
        .category-nav-button {
            transition: all 0.3s ease;
        }
        .category-nav-button:hover {
            transform: scale(1.1);
            background-color: #10b981;
            border-color: #10b981;
        }
        .text-split span {
            display: inline-block;
            animation: textReveal 0.5s forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes textReveal {
            to { opacity: 1; transform: translateY(0); }
        }
        .explore-button {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .explore-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }
        .explore-button:hover::before {
            left: 100%;
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate, .book, .category-card, .category-nav-button, .text-split span {
                opacity: 1 !important;
                transform: none !important;
                animation: none !important;
                transition-duration: 150ms !important;
            }
        }
        @media (min-width: 640px) {
            .category-header h2 { font-size: 4rem; }
            .category-stats { max-width: 500px; }
            .book-container { 
                max-width: 320px; 
                max-height: 350px; 
            }
        }
        @media (min-width: 768px) {
            .category-section { padding: 2rem; }
            .category-header h2 { font-size: 5rem; }
            .book-container { 
                max-width: 360px; 
                max-height: 350px; 
            }
            .category-stats { grid-template-columns: repeat(2, minmax(0, 200px)); gap: 1.5rem; }
            .category-footer { flex-direction: row; justify-content: space-between; }
        }
        @media (min-width: 1024px) {
            .category-section { padding: 3rem; }
            .category-header h2 { font-size: 6rem; }
            .book-container { 
                max-width: 320px; 
                max-height: 350px; 
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <?php include "../Components/nav.php"; ?>
    <main class="min-h-screen overflow-hidden pt-24 sm:pt-28 lg:pt-32 px-4 sm:px-6 lg:px-8 relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-blue-500/5 to-purple-500/5 rounded-3xl blur-3xl animate-pulse z-[-1]"></div>

        <!-- Category Showcase -->
        <div class="container mx-auto py-12 relative">
            <div id="category-header" class="text-center mb-12 sm:mb-16 lg:mb-20 scroll-animate">
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black mb-4 sm:mb-6 text-white">
                    Explore <span class="text-emerald-400">Categories</span>
                </h1>
                <p class="text-slate-400 text-sm sm:text-base md:text-lg lg:text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Discover books across various genres, curated to spark your imagination
                </p>
            </div>

            <?php
            if (empty($all_categories)) {
                echo '<p class="text-center text-slate-300">No categories found.</p>';
            } else {
                foreach ($all_categories as $index => $category) {
                    $category_id = $category['CategoryID'];
                    $category_name = htmlspecialchars($category['CategoryName']);
                    $book_count = $category['BookCount'];
                    $author_count = $author_counts[$category_id];
                    $latest_book_image = $category['LatestBookImage'];
                    $is_visible = ($index === 0) ? '' : 'hidden';
            ?>
                <div class="category-section backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl rounded-3xl p-2 sm:p-4 lg:p-6 scroll-animate <?php echo $is_visible; ?>" data-category-id="<?php echo $category_id; ?>">
                    <!-- Category Header -->
                    <div class="category-header mb-6">
                        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white text-split text-center">
                            <?php
                            $chars = str_split($category_name);
                            $delay = 0.1;
                            foreach ($chars as $char) {
                                echo "<span style='animation-delay: {$delay}s;'>" . htmlspecialchars($char) . "</span>";
                                $delay += 0.05;
                            }
                            ?>
                        </h2>
                        <p class="text-slate-400 mt-2 text-sm sm:text-base lg:text-lg text-center max-w-md mx-auto">
                            Explore our collection of <?php echo htmlspecialchars(strtolower($category_name)); ?> books with captivating narratives.
                        </p>
                    </div>

                    <!-- Book Showcase -->
                    <div class="book-container relative w-full mx-auto mb-6">
                        <div class="book backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500">
                            <img src="../../uploads/Images/<?php echo htmlspecialchars($latest_book_image ?? 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($category_name); ?> Book" class="rounded-xl w-full object-cover" onerror="this.onerror=null; this.src='../../uploads/Images/placeholder.jpg';">
                        </div>  
                    </div>

                    <!-- Category Stats -->
                    <div class="category-stats grid grid-cols-2 gap-4 sm:gap-6 mb-6 max-w-md mx-auto">
                        <div class="category-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-4 sm:p-6 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300">
                            <p class="text-xs sm:text-sm text-slate-400">Books in collection</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-black text-white"><?php echo $book_count; ?></p>
                        </div>
                        <div class="category-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-4 sm:p-6 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-300">
                            <p class="text-xs sm:text-sm text-slate-400">Featured authors</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-black text-white"><?php echo $author_count; ?></p>
                        </div>
                    </div>

                    <!-- Navigation and CTA -->
                    <div class="category-footer flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <button class="category-nav-button prev-category w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:text-white hover:border-emerald-500/50 flex items-center justify-center transition-all duration-300">
                                <i class="ri-arrow-left-s-line text-lg sm:text-xl"></i>
                            </button>
                            <button class="category-nav-button next-category w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-800/50 border border-slate-700/50 text-slate-300 hover:text-white hover:border-emerald-500/50 flex items-center justify-center transition-all duration-300">
                                <i class="ri-arrow-right-s-line text-lg sm:text-xl"></i>
                            </button>
                            <div class="text-center sm:text-left">
                                <p class="text-slate-400 text-xs sm:text-sm">Current</p>
                                <p class="text-slate-300 font-medium text-sm sm:text-base"><?php echo htmlspecialchars($category_name); ?></p>
                            </div>
                        </div>
                        <a href="books.php?category_id=<?php echo $category_id; ?>" class="explore-button w-full sm:w-auto bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-3 sm:py-4 px-6 text-sm sm:text-base md:text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 flex items-center justify-center">
                            <span>Explore Books</span>
                            <i class="ri-arrow-right-line ml-2"></i>
                        </a>
                    </div>
                </div>
            <?php } } ?>
        </div>
    </main>

    <?php include "../Components/footer.php"; ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
            document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));

            // Category navigation
            const categoryIds = [<?php
                $first = true;
                foreach ($all_categories as $category) {
                    if (!$first) echo ",";
                    echo $category['CategoryID'];
                    $first = false;
                }
            ?>];
            let currentIndex = 0;

            function showCategory(index) {
                const sections = document.querySelectorAll(".category-section");
                sections.forEach(section => section.classList.add("hidden"));
                const activeSection = document.querySelector(`.category-section[data-category-id="${categoryIds[index]}"]`);
                if (activeSection) {
                    activeSection.classList.remove("hidden");
                }
            }

            document.querySelectorAll(".next-category").forEach(button => {
                button.addEventListener("click", () => {
                    currentIndex = (currentIndex + 1) % categoryIds.length;
                    showCategory(currentIndex);
                });
            });

            document.querySelectorAll(".prev-category").forEach(button => {
                button.addEventListener("click", () => {
                    currentIndex = (currentIndex - 1 + categoryIds.length) % categoryIds.length;
                    showCategory(currentIndex);
                });
            });

            // Book hover effect (disabled on touch devices)
            if (!('ontouchstart' in window)) {
                document.querySelectorAll(".book-container").forEach(container => {
                    const book = container.querySelector(".book");
                    if (book) {
                        container.addEventListener("mousemove", (e) => {
                            const rect = container.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;
                            const centerX = rect.width / 2;
                            const centerY = rect.height / 2;
                            const moveX = (x - centerX) / 20;
                            const moveY = (y - centerY) / 20;
                            book.style.transform = `rotateY(${moveX}deg) rotateX(${-moveY}deg) translateZ(50px)`;
                        });
                        container.addEventListener("mouseleave", () => {
                            book.style.transform = "rotateY(0deg) rotateX(0deg) translateZ(0px)";
                        });
                    }
                });
            }

            // Navigation link styling
            const navLinks = document.querySelectorAll("nav a");
            navLinks.forEach(link => {
                link.classList.remove("text-emerald-400", "font-bold", "font-medium");
                link.classList.add("text-slate-300", "font-bold");
                const underline = link.querySelector("span");
                if (underline) {
                    underline.classList.remove("w-full");
                    underline.classList.add("w-0", "group-hover:w-full", "bg-emerald-400");
                }
            });

            const desktopActiveLink = document.querySelector('nav a[href="../Pages/Category.php"]');
            if (desktopActiveLink) {
                desktopActiveLink.classList.remove("text-slate-300");
                desktopActiveLink.classList.add("text-emerald-400", "font-bold");
                const activeUnderline = desktopActiveLink.querySelector("span");
                if (activeUnderline) {
                    activeUnderline.classList.remove("w-0", "group-hover:w-full");
                    activeUnderline.classList.add("w-full", "bg-emerald-400");
                }
            }

            const mobileActiveLink = document.querySelector('nav a[href="Category.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400", "font-bold");
            }
        });
    </script>
</body>
</html>