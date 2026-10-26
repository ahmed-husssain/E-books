<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";

$title = $description = $type = $prize = "";
$startDate = $endDate = null;
$error = "";
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $type = $_POST['type'];
    $prize = trim($_POST['prize']);
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    if (!in_array($type, ['Story', 'Essay'])) {
        $error = "Invalid Type. Please select 'Story' or 'Essay'.";
    } elseif (empty($title) || empty($description) || empty($prize) || empty($startDate) || empty($endDate)) {
        $error = "All fields are required.";
    } else {
        $sql = "UPDATE competitions SET Title = ?, Description = ?, Type = ?, Prize = ?, StartDate = ?, EndDate = ? WHERE CompetitionID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $description, $type, $prize, $startDate, $endDate, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Competition updated successfully!";
            header("Location: admin.competitions.php");
            exit();
        } else {
            $error = "Error updating competition: " . $stmt->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM competitions WHERE CompetitionID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $title = $row['Title'];
    $description = $row['Description'];
    $type = $row['Type'];
    $prize = $row['Prize'];
    $startDate = $row['StartDate'];
    $endDate = $row['EndDate'];
} else {
    die("Competition not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Competition | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
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
        };
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
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #34d399;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    <div class="flex min-h-screen">
        <?php include "../Components/sider.php"; ?>

        <main id="main-content" class="main-content ml-64 min-h-screen w-full p-8">

            <!-- Header -->
            <div class="mt-10 mb-8 scroll-animate">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
                        <i class="ri-edit-line text-2xl text-emerald-400"></i>
                    </div>
                    <h1 class="text-4xl font-black text-white">Update Competition</h1>
                </div>
                <p class="text-slate-400 text-xl font-light">Edit competition details below</p>
            </div>

            <!-- Messages -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-slate-800/30 border border-red-500/30 rounded-xl scroll-animate">
                    <div class="flex items-center">
                        <i class="ri-error-warning-line text-red-400 mr-3"></i>
                        <p class="text-red-400 font-medium"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-6 p-4 bg-slate-800/30 border border-emerald-500/30 rounded-xl scroll-animate">
                    <div class="flex items-center">
                        <i class="ri-check-circle-line text-emerald-400 mr-3"></i>
                        <p class="text-emerald-400 font-medium"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Form -->
            <div class="card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 w-full max-w-lg p-8 scroll-animate mx-auto">
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Title</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="title" 
                                class="form-input w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                value="<?php echo htmlspecialchars($title); ?>" 
                                required
                            >
                            <i class="ri-text absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                        <div class="relative">
                            <textarea 
                                name="description" 
                                class="form-input w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                required
                            ><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Type</label>
                        <div class="relative">
                            <select 
                                name="type" 
                                class="form-input w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                required
                            >
                                <option value="Story" <?php echo $type === 'Story' ? 'selected' : ''; ?>>Story</option>
                                <option value="Essay" <?php echo $type === 'Essay' ? 'selected' : ''; ?>>Essay</option>
                            </select>
                            <i class="ri-list-check-2 absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Prize</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="prize" 
                                class="form-input w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                value="<?php echo htmlspecialchars($prize); ?>" 
                                required
                            >
                            <i class="ri-gift-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Start Date</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                name="startDate" 
                                class="form-input w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                value="<?php echo htmlspecialchars($startDate); ?>" 
                                required
                            >
                            <i class="ri-calendar-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">End Date</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                name="endDate" 
                                class="form-input w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" 
                                value="<?php echo htmlspecialchars($endDate); ?>" 
                                required
                            >
                            <i class="ri-calendar-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl shadow-2xl shadow-emerald-500/30 transition-all duration-300 hover:scale-105"
                    >
                        <i class="ri-save-line mr-2"></i>Save Changes
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="admin.competitions.php" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium flex items-center justify-center gap-1">
                        <i class="ri-arrow-left-line"></i>Cancel and return to dashboard
                    </a>
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
    </script>
</body>
</html>