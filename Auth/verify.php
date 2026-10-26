<?php
include_once __DIR__ . "/../Config/db.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT Email FROM users WHERE token = ? AND Verified = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $sql1 = "UPDATE users SET Verified = 1, token = NULL WHERE token = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("s", $token);
        
        if ($stmt1->execute()) {
            // Redirect to login
            header("Location: ../User/Pages/home.php");
            exit();
        } else {
            echo "Error verifying account.";
        }

        $stmt1->close();
    } else {
        echo "Invalid or already verified token.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Modern Buffer / Loader</title>
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="container bg-gray-500 h-[100vh] relative">
    <!-- Loader -->
    <div
      id="loader"
      class="fixed inset-0 bg-white bg-opacity-80 backdrop-blur-sm flex flex-col items-center justify-center z-50"
    >
      <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent border-solid rounded-full animate-spin"></div>
      <p class="mt-4 text-blue-600 font-semibold text-lg animate-pulse">Processing...</p>
    </div>
  </div>

  <script>
  function showLoader() {
    document.getElementById("loader").classList.remove("hidden");
    
    // Redirect after 3 seconds (3000 milliseconds)
    setTimeout(function () {
      window.location.href = "../User/Pages/home.php";
    }, 3000);
  }

  window.addEventListener('DOMContentLoaded', showLoader);
</script>

</body>
</html>
