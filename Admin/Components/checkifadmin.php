<?php
session_start();
include "../../Config/db.php";
require_once __DIR__ . "/../../Config/csrf.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../../Auth/login.php");
    exit;
}
$username = $_SESSION['user'];

$stmt = $conn->prepare("SELECT Role FROM users WHERE Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if ($row['Role'] !== 'Admin') {
        echo "Access Denied. Admins Only.";
        exit;
    }
} else {
  echo "User not found.";
  exit;
}
?>