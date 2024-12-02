<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = $_GET['id'] ?? null;

if ($cart_id) {
    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

       // Update the cart count
       $count_sql = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
       $count_stmt = $conn->prepare($count_sql);
       $count_stmt->bind_param("i", $user_id);
       $count_stmt->execute();
       $count_result = $count_stmt->get_result();
       $cart_data = $count_result->fetch_assoc();

       // Store the new cart count in the session
    $_SESSION['cart_count'] = $cart_data['cart_count'];
}

header("Location: viewcart.php");
exit;
?>
