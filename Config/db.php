<?php
$server   = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$database = getenv('DB_NAME') ?: "finalbook1";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}
?>