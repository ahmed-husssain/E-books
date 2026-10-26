<?php
/**
 * Authorized E-Book PDF Streaming Controller
 * Verifies purchase authorization in the database before streaming the PDF file.
 */

session_start();
include "../../Config/db.php";

// Enforce authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../Auth/login.php");
    exit();
}

$bookId = intval($_GET['book_id'] ?? 0);
if ($bookId <= 0) {
    http_response_code(400);
    die("Invalid book ID.");
}

$userId = intval($_SESSION['user_id']);

// Verify book existence and user purchase authorization
$sql = "SELECT b.BookID, b.Title, b.PDFPath, b.Price,
               (SELECT COUNT(*) FROM orders o 
                WHERE o.BookID = b.BookID 
                  AND o.UserID = ? 
                  AND LOWER(o.PaymentStatus) = 'paid') AS has_paid
        FROM books b
        WHERE b.BookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $bookId);
$stmt->execute();
$res = $stmt->get_result();
$book = $res->fetch_assoc();
$stmt->close();

if (!$book || empty($book['PDFPath'])) {
    http_response_code(404);
    die("Book or digital edition not found.");
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
$isFree = (float)$book['Price'] <= 0.00;
$hasPaid = (int)$book['has_paid'] > 0;

if (!$isAdmin && !$isFree && !$hasPaid) {
    http_response_code(403);
    die("Access Denied: You have not purchased this book.");
}

// Resolve real filesystem path safely
$filename = basename($book['PDFPath']);
$filepath = __DIR__ . '/../../uploads/PDFs/' . $filename;

if (!is_file($filepath) || !is_readable($filepath)) {
    http_response_code(404);
    die("File not found on server.");
}

// Clear any output buffer to ensure clean binary stream
if (ob_get_level()) {
    ob_end_clean();
}

$isDownload = isset($_GET['download']) && $_GET['download'] === '1';
$disposition = $isDownload ? 'attachment' : 'inline';
$cleanTitle = preg_replace('/[^a-zA-Z0-9_\-\. ]/', '', $book['Title']) ?: 'ebook';
$downloadName = rtrim($cleanTitle) . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: ' . $disposition . '; filename="' . addslashes($downloadName) . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

readfile($filepath);
exit();
