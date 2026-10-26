<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $prize = $_POST['prize'];

    $sql = "INSERT INTO competitions (Title, Description, Type, StartDate, EndDate, Prize) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $title, $description, $type, $startDate, $endDate, $prize);

    if ($stmt->execute()) {
        header("Location: admin.competitions.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Competition | E - Books</title>
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
            margin-left: 4rem; /* Matches collapsed sidebar width (w-16) */
        }
        #sidebar:not(.collapsed) ~ #main-content {
            margin-left: 16rem; /* Matches expanded sidebar width (w-64) */
        }
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3); /* emerald-500/30 */
            border-color: #10b981; /* emerald-500 */
        }
    </style>
</head>
<body class="bg-slate-950 text-white font-inter">
    <div class="flex min-h-screen">
        <?php include "../Components/sider.php"; ?>
        <!-- Main Content -->
        <main id="main-content" class="main-content min-h-screen w-full transition-all duration-300">
            <!-- Header -->
            <!-- Page Content -->
            <div class="pt-16 p-6">
                <div class="max-w-5xl mx-auto">
                    <h1 class="text-4xl font-black text-white  scroll-animate">Add New Competition</h1>
                    <?php if (isset($error)): ?>
                        <div class="mb-6 p-4 bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl scroll-animate">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 p-6 rounded-2xl scroll-animate">
                        <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                        <div class="relative">
                            <label class="block mb-1 font-medium text-slate-300">Title</label>
                            <input type="text" name="title" required class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300" placeholder="Enter competition title" />
                        </div>
                        <div class="relative">
                            <label class="block mb-1 font-medium text-slate-300">Type</label>
                            <select name="type" required class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300">
                                <option value="" class="bg-slate-700">Select a Type</option>
                                <option value="Story" class="bg-slate-700">Story</option>
                                <option value="Essay" class="bg-slate-700">Essay</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 relative">
                            <label class="block mb-1 font-medium text-slate-300">Description</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300" placeholder="Enter competition description"></textarea>
                        </div>
                        <div class="relative">
                            <label class="block mb-1 font-medium text-slate-300">Start Date</label>
                            <input type="date" name="startDate" required class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300" />
                        </div>
                        <div class="relative">
                            <label class="block mb-1 font-medium text-slate-300">End Date</label>
                            <input type="date" name="endDate" required class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300" />
                        </div>
                        <div class="relative">
                            <label class="block mb-1 font-medium text-slate-300">Prize</label>
                            <input type="text" name="prize" class="w-full px-3 py-2 bg-slate-700/50 border border-slate-600 rounded-md text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all duration-300" placeholder="Enter prize details" />
                        </div>
                        <div class="md:col-span-2 text-center relative">
                            <input type="submit" value="Add Competition" class="px-6 py-2 bg-emerald-500 text-white rounded-button font-medium hover:bg-emerald-600 transition-all duration-300 cursor-pointer">
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
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
            if (sidebar.classList.contains("collapsed")) {
                mainContent.classList.remove("ml-64");
                mainContent.classList.add("ml-16");
            } else {
                mainContent.classList.remove("ml-16");
                mainContent.classList.add("ml-64");
            }
        });
    </script>
</body>
</html>