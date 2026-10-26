<?php
session_start();
include "../../Config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch paid PDF books
$sql = "SELECT b.BookID, b.Title, b.Author, b.PDFPath, c.CategoryName, b.image 
        FROM orders o 
        JOIN books b ON o.BookID = b.BookID 
        JOIN categories c ON b.Category = c.CategoryID 
        WHERE o.UserID = ? AND LOWER(o.Format) = 'pdf' AND LOWER(o.PaymentStatus) = 'paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>My Books - Story Shelf</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
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
    };
  </script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: visible;
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
      opacity: 1; /* Force visibility initially */
      transform: translateY(0);
      transition: all 1s ease-out;
    }
    .scroll-animate.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body class="bg-slate-950 text-white min-h-screen px-4 py-12">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12 sm:mb-20 scroll-animate">
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6">
        Your <span class="text-emerald-400">Books</span>
      </h1>
      <p class="text-slate-400 text-base sm:text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed">
        Access your purchased PDF books to read or download anytime.
      </p>
         <a href="books.php" class="mt-6 inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-600 text-white shadow shadow-slate-700/30 py-2 px-5 text-sm sm:text-base font-medium rounded-lg transition-all duration-300 hover:scale-105">
    <i class="ri-arrow-left-line text-lg"></i>
    Back to All Books
    </a>

    </div>
    </div>

    <div class="relative z-10">
      <!-- Background Elements -->
      <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-blue-500/5 to-purple-500/5 rounded-3xl blur-3xl animate-pulse z-0"></div>

      <?php if ($result->num_rows > 0): ?>
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
          <?php while ($row = $result->fetch_assoc()): ?>
            <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-700 hover:-translate-y-6 group overflow-visible relative rounded-2xl scroll-animate z-10">
              <div class="absolute inset-0 bg-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              <div class="p-0 relative">
                <div class="relative mb-8 overflow-hidden rounded-t-2xl">
                    <img src="../../Uploads/Images/<?php echo htmlspecialchars($row['image']); ?>" 
                    alt="<?php echo htmlspecialchars($row['Title'] ?? 'Unknown Title'); ?>" 
                    class="w-full h-64 object-cover transition-transform duration-700 transform group-hover:scale-110" />
                  <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent"></div>
                  <span class="absolute top-6 right-6 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-4 py-2 font-semibold rounded-full backdrop-blur-xl">
                    <?php echo htmlspecialchars($row['CategoryName'] ?? 'Uncategorized'); ?>
                  </span>
                </div>
                <div class="px-4 pb-4">
                  <h2 class="font-bold text-xl mb-2 text-white group-hover:text-emerald-400 transition-colors duration-300">
                    <?php echo htmlspecialchars($row['Title'] ?? 'Unknown Title'); ?>
                  </h2>
                  <p class="text-slate-400 mb-4 text-base">by <?php echo htmlspecialchars($row['Author'] ?? 'Unknown Author'); ?></p>
                  <div class="flex items-center justify-between mb-8">
                    <div class="flex space-x-2">
                      <a href="download.php?book_id=<?php echo htmlspecialchars($row['BookID']); ?>" target="_blank" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                        <i class="ri-eye-line"></i>
                      </a>
                      <a href="download.php?book_id=<?php echo htmlspecialchars($row['BookID']); ?>&download=1" class="bg-slate-500 hover:bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300">
                        <i class="ri-download-line"></i>
                      </a>
                    </div>
                  </div>
                  <a href="book-details.php?id=<?php echo htmlspecialchars($row['BookID']); ?>" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/30 py-2 text-base font-semibold rounded-xl transition-all duration-300 hover:scale-105 block text-center">
                    View Details
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="text-center scroll-animate">
          <div class="bg-slate-800 p-6 rounded-xl shadow text-center text-slate-400 z-10 relative">
            <i class="ri-book-line text-4xl text-slate-600 mb-2"></i>
            <p class="text-lg sm:text-xl">No purchased PDF books found.</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>

<?php 
$stmt->close(); 
$conn->close(); 
?>