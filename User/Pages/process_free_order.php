<?php
session_start();
include "../../Config/db.php";
require_once "../../Config/csrf.php";

require_csrf_token();

if (empty($_SESSION['cart']) || !isset($_SESSION['user_id'])) {
    header("Location: cart.php");
    exit();
}

// Re-verify catalog price from database: every item MUST be a $0.00 free PDF
foreach ($_SESSION['cart'] as $item) {
    $book_id = intval($item['id'] ?? 0);
    $stmt = $conn->prepare("SELECT Price FROM books WHERE BookID = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    $catalogPrice = (float)($row['Price'] ?? 0);
    $format = strtolower(trim($item['format'] ?? ''));

    if ($catalogPrice > 0 || $format !== 'pdf') {
        header("Location: payment.php");
        exit();
    }
}

$conn->begin_transaction();

try {
    foreach ($_SESSION['cart'] as $item) {
        $book_id = $item['id'];
        $qty = $item['qty'];
        $format = ucfirst($item['format']);
        $deliveryAddress = "N/A (PDF)";
        $shipping = 0;
        $amount = 0;

        $sql = "INSERT INTO orders (UserID, BookID, Format, Quantity, ShippingCharge, TotalAmount, DeliveryAddress, PaymentStatus) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Paid')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisidss", $_SESSION['user_id'], $book_id, $format, $qty, $shipping, $amount, $deliveryAddress);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();
    $_SESSION['cart'] = [];

    header("Location: orderhistory.php?success=1");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    die("Error processing free order: " . $e->getMessage());
}
?>
