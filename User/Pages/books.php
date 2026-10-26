<?php
include "../../Config/db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - E - Books</title>
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
        .book-card {
            transition: transform 220ms cubic-bezier(0.16, 1, 0.3, 1), box-shadow 220ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .book-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .category-tag {
            transition: transform 150ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .category-tag:hover {
            transform: scale(1.04);
        }
        .fade-in {
            animation: fadeIn 250ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate, .book-card, .category-tag, .fade-in {
                opacity: 1 !important;
                transform: none !important;
                animation: none !important;
                transition-duration: 150ms !important;
            }
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px;
            height: 16px;
            background: #10b981;
            border-radius: 50%;
            cursor: pointer;
        }
        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 4px;
            background: #64748b;
            border-radius: 2px;
            outline: none;
        }
        @media (min-width: 1024px) {
            #filters-panel {
                position: sticky;
                top: 8rem; /* Adjust based on nav height */
                width: calc(25% - 1rem); /* Matches lg:w-1/4 with padding adjustment */
                height: fit-content;
                overflow-y: auto;
            }
            .books-container {
            }
            .filters-container {
                min-height: 100%; /* Ensure container is tall enough for sticky to work */
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <!-- Page Header -->
    <?php include "../Components/nav.php"; ?>
    <div class="sm:px-6">
    <section class="pt-32 pb-12 px-6 relative">
        <div class="container mx-auto relative">
            <div id="page-header" class="text-center scroll-animate">
                <h1 class="text-6xl lg:text-7xl font-black mb-6 text-white">
                    Discover Amazing <span class="text-emerald-400">Books</span>
                </h1>
                <p class="text-slate-400 text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Explore our vast collection of carefully curated books across all genres and discover your next favorite read
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="pb-24 px-6">
        <div class="container mx-auto">
            <!-- Search Bar -->
            <div class="mb-8">
                <div id="search-section" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-8 shadow-2xl scroll-animate">
                    <div class="flex flex-col lg:flex-row gap-6 items-center">
                        <!-- Search Input -->
                        <div class="w-full lg:flex-1 relative">
                            <i class="ri-search-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="text" id="searchInput" placeholder="Search books, authors, genres..." class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl pl-12 pr-4 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg">
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="w-full lg:w-auto relative">
                            <i class="ri-sort-desc text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                            <select id="sortBy" class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl pl-10 pr-3 py-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 appearance-none text-lg">
                                <option value="newest">Newest First</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Highest Rated</option>
                                <option value="title">Title A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Books Grid -->
            <div class="flex flex-col lg:flex-row gap-8 filters-container">
                <!-- Filters Sidebar -->
                <div id="filters-panel" class="lg:w-1/4 backdrop-blur-xl bg-slate-700/30 border border-slate-600/50 rounded-2xl p-6 scroll-animate">
                    <!-- Genre Filter -->
                    <div class="mb-6">
                        <h3 class="text-white font-semibold mb-4 text-lg">Genres</h3>
                        <div class="space-y-2">
                            <?php
                            $catQuery = $conn->query("SELECT * FROM categories");
                            $selectedCategoryID = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
                            while ($cat = $catQuery->fetch_assoc()):
                                $catVal = htmlspecialchars($cat['CategoryName']);
                                $catID = intval($cat['CategoryID']);
                                $isChecked = ($selectedCategoryID === $catID) ? 'checked' : '';
                            ?>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700 border-slate-600 rounded focus:ring-emerald-500 hidden peer" data-category="<?= $catVal ?>" <?= $isChecked ?>>
                                    <div class="w-4 h-4 border border-slate-600 rounded peer-checked:bg-emerald-500 peer-checked:border-emerald-500"></div>
                                    <span class="text-slate-300 group-hover:text-emerald-400 transition-colors duration-200"><?= $catVal ?></span>
                                </label>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Format Filter -->
                    <div class="mb-6">
                        <h3 class="text-white font-semibold mb-4 text-lg">Format</h3>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700 border-slate-600 rounded focus:ring-emerald-500 hidden peer format-filter" data-format="pdf">
                                <div class="w-4 h-4 border border-slate-600 rounded peer-checked:bg-emerald-500 peer-checked:border-emerald-500"></div>
                                <span class="text-slate-300 group-hover:text-emerald-400 transition-colors duration-200">PDF</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700 border-slate-600 rounded focus:ring-emerald-500 hidden peer format-filter" data-format="cd">
                                <div class="w-4 h-4 border border-slate-600 rounded peer-checked:bg-emerald-500 peer-checked:border-emerald-500"></div>
                                <span class="text-slate-300 group-hover:text-emerald-400 transition-colors duration-200">CD</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-emerald-500 bg-slate-700 border-slate-600 rounded focus:ring-emerald-500 hidden peer format-filter" data-format="hardcopy">
                                <div class="w-4 h-4 border border-slate-600 rounded peer-checked:bg-emerald-500 peer-checked:border-emerald-500"></div>
                                <span class="text-slate-300 group-hover:text-emerald-400 transition-colors duration-200">Hardcopy</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="text-white font-semibold mb-4 text-lg">Price Range</h3>
                        <div class="space-y-4">
                            <input type="range" min="0" max="120" value="120" class="w-full" id="priceRange">
                            <div class="flex justify-between text-sm text-slate-400">
                                <span class="whitespace-nowrap min-w-[3rem]">FREE</span>
                                <span id="priceValue">$120</span>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <div class="mt-6">
                        <button id="clearFilters" class="w-full text-slate-400 hover:text-emerald-400 transition-colors duration-200 flex items-center justify-center">
                            <i class="ri-close-line mr-2 text-base"></i>
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Books Grid and Results Summary -->
                <div class="lg:w-3/4 books-container">
                    <!-- Results Summary -->
                    <div class="flex items-center justify-between mb-6 border-t border-b border-slate-700/50 py-4">
                        <div class="text-slate-400">
                            Showing <span class="text-white font-semibold" id="visibleBooks">0</span> books
                        </div>
                    </div>

                    <!-- Books Grid -->
                    <div id="books-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $sql = "SELECT b.*, c.CategoryName FROM books b LEFT JOIN categories c ON b.Category = c.CategoryID";
                        $result = $conn->query($sql);
                        $formatMap = [
                            'PDF' => ['field' => 'PDFPath', 'label' => 'PDF', 'color' => 'bg-blue-900/50 text-blue-200 border border-blue-500/30'],
                            'HardCopy' => ['field' => 'HasHardCopy', 'label' => 'Hardcopy', 'color' => 'bg-green-900/50 text-green-200 border border-green-500/30'],
                            'CD' => ['field' => 'HasCD', 'label' => 'CD', 'color' => 'bg-orange-900/50 text-orange-200 border border-orange-500/30']
                        ];
                        while ($book = $result->fetch_assoc()):
                            $formats = [];
                            foreach ($formatMap as $key => $info) {
                                if (!empty($book[$info['field']])) {
                                    $formats[] = "<span class='inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {$info['color']} backdrop-blur-xl'>{$info['label']}</span>";
                                }
                            }
                            $priceDisplay = ($book['Price'] == 0.00) ? 'Free' : '$' . number_format($book['Price'], 2);
                            $author = htmlspecialchars($book['Author']);
                            $title = htmlspecialchars($book['Title']);
                            $category = htmlspecialchars($book['CategoryName']);
                            $defaultFormat = '';
                            if ($book['HasHardCopy'] && $book['Stock'] > 0) {
                                $defaultFormat = 'HardCopy';
                            } elseif (!empty($book['PDFPath'])) {
                                $defaultFormat = 'PDF';
                            } elseif ($book['HasCD']) {
                                $defaultFormat = 'CD';
                            }
                        ?>
                            <div class="book-card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-2 group overflow-hidden relative scroll-animate rounded-2xl" 
                                data-title="<?= strtolower($title) ?>" 
                                data-author="<?= strtolower($author) ?>" 
                                data-category="<?= $category ?>" 
                                data-price="<?= $book['Price'] ?>" 
                                data-pdf="<?= !empty($book['PDFPath']) ? 'true' : 'false' ?>" 
                                data-hardcopy="<?= $book['HasHardCopy'] ? 'true' : 'false' ?>" 
                                data-cd="<?= $book['HasCD'] ? 'true' : 'false' ?>" 
                                data-bookid="<?= $book['BookID'] ?>">
                                <div class="absolute inset-0 bg-emerald-500/3 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="p-0 relative">
                                    <div class="relative mb-6 overflow-hidden">
                                        <img src="../../uploads/images/<?php echo $book['image']; ?>" alt="<?= $title ?>" class="object-cover group-hover:scale-110 transition-transform duration-700 w-full h-80">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                                        <div class="absolute top-4 right-4 space-y-2">
                                            <span class="category-tag bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 font-semibold rounded-full backdrop-blur-xl block">
                                                <?= $category ?>
                                            </span>
                                        </div>
                                        <div class="absolute top-4 left-4 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <a href="book-details.php?id=<?php echo $book['BookID']; ?>">
                                                <button class="w-10 h-10 p-0 backdrop-blur-xl bg-slate-800/80 border border-slate-700/50 text-slate-300 hover:text-emerald-400 hover:bg-slate-700/80 rounded-full flex items-center justify-center">
                                                    <i class="ri-eye-line text-base"></i>
                                                </button>
                                            </a>
                                        </div>
                                        <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                            <?= implode(' ', $formats); ?>
                                        </div>
                                    </div>
                                    <div class="px-6 pb-6">
                                    <h3 class="font-bold text-xl mb-2 text-white group-hover:text-emerald-400 transition-colors duration-300 line-clamp-2 min-h-[3rem]">
                                        <?= $title ?>
                                    </h3>
                                        <p class="text-slate-400 mb-3 text-lg">by <?= $author ?></p>
                                        <div class="flex items-center justify-between mb-6">
                                            <span class="font-black text-2xl text-emerald-400"><?= $priceDisplay ?></span>
                                        </div>
                                        <?php if ($book['HasHardCopy'] || $book['HasCD'] || !empty($book['PDFPath'])): ?>
                                            <form method="POST" class="w-full add-to-cart-form">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($book['BookID']) ?>">
                                                <input type="hidden" name="title" value="<?= htmlspecialchars($book['Title']) ?>">
                                                <input type="hidden" name="qty" value="1">
                                                <select name="format" class="w-full mb-3 bg-slate-700/50 border border-slate-600/50 text-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500/50" required>
                                                    <option value="">Select Format</option>
                                                    <?php if (!empty($book['PDFPath'])): ?>
                                                        <option value="PDF">PDF</option>
                                                    <?php endif; ?>
                                                    <?php if ($book['HasHardCopy'] && $book['Stock'] > 0): ?>
                                                        <option value="HardCopy">Hardcopy</option>
                                                    <?php endif; ?>
                                                    <?php if ($book['HasCD'] && $book['Stock'] > 0): ?>
                                                        <option value="CD">CD</option>
                                                    <?php endif; ?>
                                                </select>
                                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/20 py-3 text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105 flex items-center justify-center">
                                                    <i class="ri-shopping-cart-line mr-2 text-lg"></i>
                                                    Add to Cart
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    <?php
    include "../Components/footer.php";
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

        // Filter and sort logic
        const bookCards = document.querySelectorAll('.book-card');
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const searchInput = document.getElementById('searchInput');
        const sortBy = document.getElementById('sortBy');
        const priceRange = document.getElementById("priceRange");
        const priceValue = document.getElementById("priceValue");
        const visibleBooks = document.getElementById("visibleBooks");

        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedCategories = Array.from(document.querySelectorAll('input[data-category]:checked'))
                .map(cb => cb.dataset.category);
            const selectedFormats = Array.from(document.querySelectorAll('input.format-filter:checked'))
                .map(cb => cb.dataset.format);
            const maxPrice = parseFloat(priceRange.value);
            const sortOption = sortBy.value;

            // Convert NodeList to Array and apply filters
            let filteredCards = Array.from(bookCards).filter(card => {
                const title = card.dataset.title;
                const author = card.dataset.author;
                const category = card.dataset.category;
                const price = parseFloat(card.dataset.price);
                const isPDF = card.dataset.pdf === 'true';
                const isCD = card.dataset.cd === 'true';
                const isHardcopy = card.dataset.hardcopy === 'true';

                let visible = true;

                if (searchTerm && !title.includes(searchTerm) && !author.includes(searchTerm)) {
                    visible = false;
                }
                if (selectedCategories.length > 0 && !selectedCategories.includes(category)) {
                    visible = false;
                }
                if (selectedFormats.length > 0) {
                    let hasFormat = false;
                    if (selectedFormats.includes('pdf') && isPDF) hasFormat = true;
                    if (selectedFormats.includes('cd') && isCD) hasFormat = true;
                    if (selectedFormats.includes('hardcopy') && isHardcopy) hasFormat = true;
                    if (!hasFormat) visible = false;
                }
                if (price > maxPrice) {
                    visible = false;
                }

                card.style.display = visible ? 'block' : 'none';
                return visible;
            });

            // Sort the filtered cards
            filteredCards.sort((a, b) => {
                const titleA = a.dataset.title;
                const titleB = b.dataset.title;
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);
                const ratingA = parseFloat(a.dataset.rating || 0);
                const ratingB = parseFloat(b.dataset.rating || 0);

                if (sortOption === 'title') {
                    return titleA.localeCompare(titleB);
                } else if (sortOption === 'price-low') {
                    return priceA - priceB;
                } else if (sortOption === 'price-high') {
                    return priceB - priceA;
                } else if (sortOption === 'rating') {
                    return ratingB - ratingA;
                } else {
                    // Default: newest (assuming newer books have higher BookID)
                    return parseInt(b.dataset.bookid || 0) - parseInt(a.dataset.bookid || 0);
                }
            });

            // Re-append sorted cards to the grid
            const booksGrid = document.getElementById('books-grid');
            booksGrid.innerHTML = '';
            filteredCards.forEach(card => booksGrid.appendChild(card));

            // Update visible books count
            visibleBooks.textContent = filteredCards.length;
        }

        searchInput.addEventListener('input', applyFilters);
        priceRange.addEventListener('input', () => {
            priceValue.textContent = `$${priceRange.value}`;
            applyFilters();
        });
        checkboxes.forEach(cb => {
            cb.addEventListener('change', applyFilters);
        });
        sortBy.addEventListener('change', applyFilters);
        document.getElementById("clearFilters").addEventListener("click", () => {
            searchInput.value = "";
            checkboxes.forEach(cb => cb.checked = false);
            priceRange.value = 120;
            priceValue.textContent = "$120";
            sortBy.value = "newest";
            applyFilters();
            const url = new URL(window.location.href);
            url.searchParams.delete('category_id');
            window.history.replaceState({}, '', url);
        });

        // Initialize filters
        applyFilters();

        // AJAX form submission for Add to Cart
        document.addEventListener("DOMContentLoaded", function () {
            const forms = document.querySelectorAll('.add-to-cart-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent page redirection
                    const formData = new FormData(form);
                    fetch('../../Config/addtocart.php', {
                        method: 'POST',
                        body: formData
                    })
                    window.location.reload()
                });
            });
        });

        // Navigation link styling
        document.addEventListener("DOMContentLoaded", function () {
            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(link => {
                link.classList.remove("text-emerald-400", "font-bold", "font-medium"); // Remove conflicting classes
                link.classList.add("text-slate-300", "font-bold"); // Add font-bold to all links
                if (link.closest(".lg\\:flex") || link.closest(".mobile-menu")) { 
                    // Ensure text-slate-300 for both desktop and mobile
                }
                const underline = link.querySelector("span");
                if (underline) {
                    underline.classList.remove("w-full");
                    underline.classList.add("w-0", "group-hover:w-full", "bg-emerald-400");
                }
            });

            // Desktop active link
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/books.php"]');
            if (desktopActiveLink) {
                desktopActiveLink.classList.remove("text-slate-300");
                desktopActiveLink.classList.add("text-emerald-400", "font-bold"); // Ensure active link is bold
                const activeUnderline = desktopActiveLink.querySelector("span");
                if (activeUnderline) {
                    activeUnderline.classList.remove("w-0", "group-hover:w-full");
                    activeUnderline.classList.add("w-full", "bg-emerald-400");
                }
            }

            // Mobile active link
            const mobileActiveLink = document.querySelector('nav a[href="books.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>