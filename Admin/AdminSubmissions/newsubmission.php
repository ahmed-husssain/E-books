<?php
include "../Components/checkifadmin.php";
include "../../Config/db.php";

// Fetch competitions and users for dropdowns
$users = $conn->query("SELECT UserID, Username FROM users ORDER BY Username");
$competitions = $conn->query("SELECT CompetitionID, Title FROM competitions ORDER BY Title");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["user_id"];
    $competitionId = $_POST["competition_id"];
    $isWinner = isset($_POST["is_winner"]) ? 1 : 0;
    $timeExceeded = isset($_POST["time_limit_exceeded"]) ? 1 : 0;

    // File upload
    $error = "";
    if (empty($_FILES["document"]["name"]) || $_FILES["document"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Please select a valid document to upload.";
    } else {
        $orig_name = $_FILES["document"]["name"];
        $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
        $tmp_name = $_FILES["document"]["tmp_name"];
        $file_size = $_FILES["document"]["size"];

        $allowed_doc_exts = ['pdf', 'docx', 'txt'];
        $allowed_doc_mimes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'text/plain'
        ];

        if (!in_array($ext, $allowed_doc_exts)) {
            $error = "Invalid document extension. Only PDF, DOCX, and TXT files are allowed.";
        } elseif ($file_size > 25 * 1024 * 1024) {
            $error = "Document exceeds 25MB maximum size.";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp_name);
            finfo_close($finfo);

            if (!in_array($mime, $allowed_doc_mimes)) {
                $error = "Invalid document type. File content does not match allowed document types.";
            } else {
                $uploadDir = "../Uploads/Submissions/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($orig_name, PATHINFO_FILENAME));
                $clean_name = substr($clean_name, 0, 30);
                $safe_fileName = $clean_name . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $uploadPath = $uploadDir . $safe_fileName;

                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $stmt = $conn->prepare("
                        INSERT INTO submissions (UserID, CompetitionID, DocumentPath, SubmissionDate, IsWinner, TimeLimitExceeded)
                        VALUES (?, ?, ?, NOW(), ?, ?)
                    ");
                    $stmt->bind_param("issii", $userId, $competitionId, $uploadPath, $isWinner, $timeExceeded);

                    if ($stmt->execute()) {
                        header("Location: submisions.php?success=1");
                        exit;
                    } else {
                        $error = "Failed to save submission.";
                    }
                } else {
                    $error = "File upload failed to save.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Submission - E - Books</title>
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
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
  </style>
</head>
<body class="bg-slate-950 text-white">
  <!-- Include Navigation -->
  <!-- Include Sidebar -->
  <?php include "../Components/sider.php"; ?>

  <!-- Main Content -->
  <div id="main-content" class="ml-64 transition-all duration-300">
    <section class="py-24 px-6 relative">
      <div class="container mx-auto max-w-2xl">
        <div id="form-container" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group scroll-animate rounded-2xl">
          <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
          <div class="p-10 relative">
            <h2 class="text-4xl font-black mb-8 text-white group-hover:text-emerald-400 transition-colors duration-300">
              Add New <span class="text-emerald-400">Submission</span>
            </h2>

            <?php if (!empty($error)): ?>
              <div class="mb-6 p-4 text-red-400 bg-red-500/10 border border-red-500/30 rounded-xl">
                <?php echo htmlspecialchars($error); ?>
              </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
              <!-- User Dropdown -->
              <div>
                <label class="block mb-2 font-semibold text-slate-300">User</label>
                <select name="user_id" required class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 text-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                  <option value="" class="bg-slate-900">Select User</option>
                  <?php while ($user = $users->fetch_assoc()): ?>
                    <option value="<?= $user['UserID'] ?>" class="bg-slate-900"><?= htmlspecialchars($user['Username']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <!-- Competition Dropdown -->
              <div>
                <label class="block mb-2 font-semibold text-slate-300">Competition</label>
                <select name="competition_id" required class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 text-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                  <option value="" class="bg-slate-900">Select Competition</option>
                  <?php while ($comp = $competitions->fetch_assoc()): ?>
                    <option value="<?= $comp['CompetitionID'] ?>" class="bg-slate-900"><?= htmlspecialchars($comp['Title']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <!-- Document Upload -->
              <div>
                <label class="block mb-2 font-semibold text-slate-300">Document (PDF/DOC)</label>
                <input type="file" name="document" required accept=".pdf,.doc,.docx" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 text-slate-300 rounded-xl file:bg-emerald-500 file:text-white file:border-none file:px-4 file:py-2 file:rounded-xl file:font-semibold hover:file:bg-emerald-600 transition-all duration-300" />
              </div>

              <!-- Checkboxes -->
              <div class="flex items-center gap-6">
                <label class="inline-flex items-center">
                  <input type="checkbox" name="is_winner" class="form-checkbox h-5 w-5 text-emerald-500 bg-slate-900/50 border-slate-700/50 focus:ring-emerald-500 rounded" />
                  <span class="ml-2 text-slate-300 font-semibold">Mark as Winner</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="checkbox" name="time_limit_exceeded" class="form-checkbox h-5 w-5 text-red-500 bg-slate-900/50 border-slate-700/50 focus:ring-red-500 rounded" />
                  <span class="ml-2 text-slate-300 font-semibold">Time Limit Exceeded</span>
                </label>
              </div>

              <!-- Submit Button -->
              <div class="text-center mt-8">
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-4 text-lg font-semibold rounded-xl transition-all duration-300 hover:scale-105">
                  Submit Submission
                </button>
              </div>
            </form>

            <!-- Back Link -->
            <div class="mt-6 text-center">
              <a href="submisions.php" class="text-emerald-400 hover:text-emerald-500 font-semibold transition-colors duration-300 flex items-center justify-center gap-2">
                <i class="ri-arrow-left-line"></i> Back to Submissions
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
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
  </script>
</body>
</html>