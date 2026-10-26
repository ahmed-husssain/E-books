<?php 
session_start();
include "../Components/checkifadmin.php";
include "../../Config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]); 
  if (!empty($name)) {
    $stmt = $conn->prepare("INSERT INTO categories (CategoryName) VALUES (?)");
    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
      $_SESSION['success'] = "Category added successfully!";
      header("Location: newcategory.php");
      exit();
    } else {
      $_SESSION['error'] = "Error: " . $stmt->error;
      header("Location: newcategory.php");
      exit();
    }
    $stmt->close();
  } else {
    $_SESSION['error'] = "Category name is required.";
    header("Location: newcategory.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add New Category | Admin Panel</title>
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
    .bg-blur {
      filter: blur(3rem);
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
            <i class="ri-folder-add-line text-2xl text-emerald-400"></i>
          </div>
          <h1 class="text-4xl font-black text-white">Category Management</h1>
        </div>
        <p class="text-slate-400 text-xl font-light">Add new categories and manage existing ones</p>
      </div>

      <!-- Messages -->
      <?php if (isset($_SESSION['error'])): ?>
      <div class="mb-6 p-4 bg-slate-800/30 border border-red-500/30 rounded-xl scroll-animate">
        <div class="flex items-center">
          <i class="ri-error-warning-line text-red-400 mr-3"></i>
          <p class="text-red-400 font-medium"><?php echo $_SESSION['error']; ?></p>
        </div>
      </div>
      <?php unset($_SESSION['error']); ?>
      <?php elseif (isset($_SESSION['success'])): ?>
      <div class="mb-6 p-4 bg-slate-800/30 border border-emerald-500/30 rounded-xl scroll-animate">
        <div class="flex items-center">
          <i class="ri-check-circle-line text-emerald-400 mr-3"></i>
          <p class="text-emerald-400 font-medium"><?php echo $_SESSION['success']; ?></p>
        </div>
      </div>
      <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <div class="grid lg:grid-cols-2 gap-8 mb-8">
        <!-- Add Form -->
        <div class="card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-8 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
          <div class="flex items-center space-x-3 mb-6">
            <div class="p-2 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
              <i class="ri-add-circle-line text-xl text-emerald-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Add New Category</h2>
          </div>
          
          <form method="POST" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">
                Category Name <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input 
                  type="text" 
                  name="name" 
                  placeholder="Enter category name..."
                  class="w-full px-4 py-3 pl-12 bg-slate-700/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-white placeholder-slate-400"
                />
                <i class="ri-folder-line absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
              </div>
            </div>
            
            <button 
              type="submit" 
              class="w-full px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl shadow-2xl shadow-emerald-500/30 transition-all duration-300 hover:scale-105"
            >
              <i class="ri-add-line mr-2"></i>Add Category
            </button>
          </form>
        </div>

        <!-- Stats -->
        <div class="card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl p-8 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate">
          <div class="flex items-center space-x-3 mb-6">
            <div class="p-2 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
              <i class="ri-bar-chart-box-line text-xl text-emerald-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Stats</h2>
          </div>
          
          <?php
          $count_result = $conn->query("SELECT COUNT(*) as total FROM categories");
          $total_categories = $count_result->fetch_assoc()['total'];
          ?>
          
          <div class="p-4 bg-slate-700/30 rounded-xl border border-slate-600">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="p-2 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
                  <i class="ri-folder-line text-emerald-400"></i>
                </div>
                <span class="font-medium text-slate-300">Total Categories</span>
              </div>
              <span class="text-2xl font-black text-emerald-400"><?php echo $total_categories; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Category List -->
      <div class="card backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-2xl shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 scroll-animate overflow-hidden">
        <div class="p-6 border-b border-slate-700">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="p-2 bg-emerald-500/20 rounded-lg border border-emerald-500/30">
                <i class="ri-list-check-2 text-xl text-emerald-400"></i>
              </div>
              <h2 class="text-2xl font-bold text-white">Categories</h2>
            </div>
            <span class="text-sm text-slate-400"><?php echo $total_categories; ?> total</span>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-slate-900/30">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-slate-400 uppercase">
                  <i class="ri-folder-line mr-2"></i>Name
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-slate-400 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
              <?php
              $result = $conn->query("SELECT * FROM categories ORDER BY CategoryID DESC");
              $count = 0;
              while ($row = $result->fetch_assoc()) {
                $count++;
                echo "<tr class='hover:bg-slate-700/50 transition'>";
                echo "<td class='px-6 py-4'>";
                echo "<div class='flex items-center'>";
                echo "<i class='ri-folder-line text-slate-400 mr-3'></i>";
                echo "<span class='font-medium text-white'>" . htmlspecialchars($row['CategoryName']) . "</span>";
                echo "</div></td>";
                echo "<td class='px-6 py-4'>";
                echo "<div class='flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between'>";
                echo "<span class='px-2 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-medium flex items-center'>";
                echo "<i class='ri-check-circle-line mr-1'></i>Active</span>";
                echo "<div class='flex justify-end gap-2'>";
                echo "<a href='categoryupdate.php?id=" . $row['CategoryID'] . "' class='text-emerald-400 text-sm hover:text-emerald-300 flex items-center gap-1'>";
                echo "<i class='ri-edit-line'></i> Edit</a>";
                echo "<form method='POST' action='categorydelete.php' onsubmit='return confirm(\"Are you sure you want to delete this category?\");' class='inline m-0 p-0'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['CategoryID']) . "'>";
                echo "<input type='hidden' name='csrf_token' value='" . csrf_token() . "'>";
                echo "<button type='submit' class='text-red-400 text-sm hover:text-red-300 flex items-center gap-1 bg-transparent border-0 p-0 cursor-pointer'>";
                echo "<i class='ri-delete-bin-line'></i> Delete</button></form>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
              }
              if ($count == 0) {
                echo "<tr><td colspan='2' class='px-6 py-12 text-center text-slate-400'>";
                echo "<i class='ri-folder-open-line text-4xl text-slate-500 block mb-3'></i>";
                echo "<p class='text-lg font-medium'>No categories found</p>";
                echo "<p class='text-sm'>Create your first category above</p>";
                echo "</td></tr>";
              }
              ?>
            </tbody>
          </table>
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