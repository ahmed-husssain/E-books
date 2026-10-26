<?php
session_start();
include "../../Config/db.php";

if (!isset($_SESSION['email'])) {
    header("Location: signup.php");
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT UserID FROM users WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$user_id = $user['UserID'];

// Get competition ID from URL
$comp_id = isset($_GET['comp_id']) ? (int)$_GET['comp_id'] : 0;
if ($comp_id <= 0) {
    die("Invalid competition ID.");
}

// Check for existing submission
$stmt = $conn->prepare("SELECT SubmissionID, StartTime FROM submissions WHERE CompetitionID = ? AND UserID = ?");
$stmt->bind_param("ii", $comp_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$submission = $result->fetch_assoc();
$alreadySubmitted = isset($submission['SubmissionID']);
$startTime = $submission['StartTime'] ?? null;

// Initialize startTime if not set and not submitted
if (!$alreadySubmitted && !$startTime) {
    $startTime = date("c"); // ISO 8601 format
    $stmt = $conn->prepare("INSERT INTO submissions (CompetitionID, UserID, StartTime) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $comp_id, $user_id, $startTime);
    $stmt->execute();
}

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadySubmitted) {
    $story = $_POST['story'];
    $wordCount = str_word_count($story);
    $startTimestamp = strtotime($startTime);
    $elapsed = time() - $startTimestamp;
    if (empty($story)) {
        $error = "Please write a story before submitting.";
    } elseif ($wordCount < 50) {
        $error = "Write at least 50 words.";
    } elseif ($wordCount > 400) {
        $error = "Max 400 words allowed (you wrote $wordCount).";
    } elseif ($elapsed > 3 * 3600) { // Check if more than 3 hours have passed
        $error = "Submission time has expired.";
    } else {
        // Update existing submission with story
        $stmt = $conn->prepare("UPDATE submissions SET Submission = ? WHERE CompetitionID = ? AND UserID = ?");
        $stmt->bind_param("sii", $story, $comp_id, $user_id);
        if ($stmt->execute()) {
            $alreadySubmitted = true;
            header("Location: ../Emails/Submissionemail.php");
            exit;
        } else {
            $error = "Submission failed. Please try again.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <title>Competition Entry - E - Books</title>
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
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate {
                opacity: 1 !important;
                transform: none !important;
                transition-duration: 150ms !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
    <!-- Story Submission Interface -->
    <section class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-4xl">
        <div id="story-editor" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl scroll-animate">
                <h2 class="text-4xl font-black text-white mb-8 text-center">Write Your Story</h2>

                <?php if ($error): ?>
                    <div class="bg-red-500/20 text-red-400 p-4 rounded-2xl mb-4 border border-red-500/30 text-center"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-4 border border-emerald-500/30 text-center"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($alreadySubmitted): ?>
                    <div class="bg-emerald-500/20 text-emerald-400 p-4 rounded-2xl text-center border border-emerald-500/30">
                        You've already submitted a story for this competition.<br>
                        <a href="competitions.php" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-2xl shadow-md transition-all duration-300 mt-4">
                            Back to Competitions
                        </a>
                    </div>
                <?php else: ?>
                    <div id="timerDisplay" class="text-emerald-400 text-lg font-semibold mb-4"></div>

                    <form method="POST" onsubmit="return validateForm()">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label for="story" class="text-slate-300 font-semibold text-lg">Your Story</label>
                                <div class="flex items-center space-x-4">
                                    <span id="wordCount" class="text-slate-400 text-sm">0/400 words</span>
                                </div>
                            </div>
                            <textarea name="story" id="story" rows="10" oninput="updateCounter()" class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl px-6 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg transition-all duration-300 resize-none leading-relaxed"></textarea>
                            <div class="mt-3 text-sm text-slate-400">
                            </div>
                            <div class="flex justify-between items-center text-sm mt-4">
                                <span id="wordStatus" class="text-slate-400"></span>
                                <div class="w-2/3 h-2 bg-slate-600/50 rounded-full">
                                    <div id="progress" class="h-full bg-emerald-500 rounded-full transition-all" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="submitBtn" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/20 py-4 text-xl font-bold rounded-2xl transition-all duration-300 hover:scale-105 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Submit Entry
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
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

        // Word count and validation
        function updateCounter() {
            const story = document.getElementById("story").value.trim();
            const words = story === '' ? 0 : story.split(/\s+/).filter(Boolean).length;
            const wordCountDisplay = document.getElementById("wordCount");
            const wordStatus = document.getElementById("wordStatus");
            const progress = document.getElementById("progress");
            const submitBtn = document.getElementById("submitBtn");

            wordCountDisplay.innerText = `${words}/400 words`;

            const progressPercent = Math.min((words / 400) * 100, 100);
            progress.style.width = `${progressPercent}%`;

            submitBtn.disabled = words < 50 || words > 400;

            if (words >= 50 && words <= 400) {
                wordStatus.innerText = 'Good to go!';
                wordStatus.className = 'text-emerald-400 text-sm font-semibold';
                story.classList.remove('border-red-500/50');
                story.classList.add('border-emerald-500/50');
            } else if (words > 400) {
                wordStatus.innerText = `${words - 400} words over limit`;
                wordStatus.className = 'text-red-400 text-sm font-semibold';
                story.classList.remove('border-emerald-500/50');
                story.classList.add('border-red-500/50');
            } else if (words < 50 && words > 0) {
                wordStatus.innerText = `${50 - words} words to go`;
                wordStatus.className = 'text-yellow-400 text-sm font-semibold';
                story.classList.remove('border-red-500/50', 'border-emerald-500/50');
            } else {
                wordStatus.innerText = '';
                story.classList.remove('border-red-500/50', 'border-emerald-500/50');
            }
        }

        function validateForm() {
            const story = document.getElementById("story").value.trim();
            const words = story === '' ? 0 : story.split(/\s+/).filter(Boolean).length;
            if (!story) {
                alert("Please write a story before submitting.");
                return false;
            }
            if (words < 50 || words > 400) {
                alert("Story must be between 50 and 400 words.");
                return false;
            }
            return true;
        }

        window.addEventListener('DOMContentLoaded', updateCounter);

        // Timer logic
        const phpTime = "<?php echo $startTime; ?>";
        const timerDisplay = document.getElementById("timerDisplay");
        const story = document.getElementById("story");
        const submitBtn = document.getElementById("submitBtn");
        const duration = 3 * 60 * 60 * 1000; // 3 hours in milliseconds

        if (phpTime && phpTime !== 'null') {
            const startTime = new Date(phpTime).getTime();
            if (!isNaN(startTime)) {
                const interval = setInterval(() => {
                    const now = Date.now();
                    const elapsed = now - startTime;
                    const remaining = duration - elapsed;

                    if (remaining <= 0) {
                        clearInterval(interval);
                        timerDisplay.innerText = "Time's up! (Refresh to Submit)";
                        if (story) story.disabled = true;
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        const mins = Math.floor(remaining / 60000);
                        const secs = Math.floor((remaining % 60000) / 1000);
                        timerDisplay.innerText = `Time Left: ${mins}:${secs.toString().padStart(2, '0')}`;
                    }
                }, 1000);
            } else {
                timerDisplay.innerText = "Invalid start time.";
            }
        } else {
            timerDisplay.innerText = "Timer not started.";
        }
    </script>
</body>
</html>