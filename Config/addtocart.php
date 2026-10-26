<?php
session_start();
include "db.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = $_POST['id'];
$title = $_POST['title'];
$format = $_POST['format'];
$qty = $_POST['qty'];

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

$price = $book['Price'];
$cart_key = $id . '_' . $format;

if (isset($_SESSION['cart'][$cart_key])) {
    $new_qty = $_SESSION['cart'][$cart_key]['qty'] + $qty;
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