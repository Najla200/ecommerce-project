<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? null;
$cart_id = $_GET['id'] ?? null;

if ($action && $cart_id) {
    if ($action === 'increase') {
        // Increase the quantity by 1
        $sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?";
    } else if ($action === 'decrease') {
        // Decrease the quantity by 1, but not below 1
        $sql = "UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ? AND quantity > 1";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
    }
}

// Redirect back to the cart page after updating the quantity
header("Location: viewcart.php");
exit;
?>
