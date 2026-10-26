<?php
// Smart environment detection for Local XAMPP vs InfinityFree Production
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$is_infinityfree = (
    strpos($http_host, 'infinityfree.io') !== false ||
    strpos(__DIR__, 'if0_43270490') !== false ||
    getenv('INFINITYFREE_ENV') !== false
);

if ($is_infinityfree) {
    $server   = getenv('DB_HOST') ?: "sql103.infinityfree.com";
    $username = getenv('DB_USER') ?: "if0_43270490";
    $password = getenv('DB_PASS') ?: "d5TsKPRL68";
    $database = getenv('DB_NAME') ?: "if0_43270490_eboooks";
} else {
    $server   = getenv('DB_HOST') ?: "localhost";
    $username = getenv('DB_USER') ?: "root";
    $password = getenv('DB_PASS') ?: "";
    $database = getenv('DB_NAME') ?: "finalbook1";
}

mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = @new mysqli($server, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error, $conn->connect_errno);
    }
    $conn->set_charset("utf8mb4");
} catch (Throwable $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("<div style='font-family:-apple-system,BlinkMacSystemFont,sans-serif;padding:32px;text-align:center;'>
        <h2 style='color:#ef4444;'>Database Connection Error</h2>
        <p style='color:#6b7280;'>Unable to connect to the database server. Please verify database credentials.</p>
    </div>");
}
?>