<?php
session_start();
include "db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$format = strtolower(trim($_POST['format'] ?? 'pdf'));
$isPdf = $format === 'pdf';
$rawQty = intval($_POST['qty'] ?? 1);
$qty = $isPdf ? 1 : max(1, min(5, $rawQty));

$sql = "SELECT Stock, HasHardCopy, HasCD, PDFPath, Price FROM books WHERE BookID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Book not found.");
}
$book = $result->fetch_assoc();
$stmt->close();

$price = (float)$book['Price'];
$cart_key = $id . '_' . $format;

if (isset($_SESSION['cart'][$cart_key])) {
    $new_qty = $isPdf ? 1 : min(5, max(1, $_SESSION['cart'][$cart_key]['qty'] + $qty));
    $_SESSION['cart'][$cart_key]['qty'] = $new_qty;
} else {
    $_SESSION['cart'][$cart_key] = [
        'id' => $id,
        'title' => $title,
        'format' => $format,
        'price' => $price,
        'qty' => $qty
    ];
}

header("Location: ../User/Pages/cart.php");
exit();
?>