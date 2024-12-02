<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.id as cart_id, p.name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="viewcart.css">
</head>
<body>
<header class="logo-header">
    <div class="logo-container">
        <img src="./logo1.png" alt="Logo" class="logo">
        <h1 class="site-name">VogueCart</h1>
    </div>
    <a href="dashboard.php">Back to Dashboard</a>
</header>
<div class="cart-container">
    <?php 
    if ($result->num_rows === 0) {
        echo "<p>Your cart is empty!</p>";
    } else{
        $totalAmount=0;
    while ($row = $result->fetch_assoc()) {
        $itemTotal = $row['price'] * $row['quantity'];
        $totalAmount+=$itemTotal;
         ?>
        <div class="cart-item">
            <h2><?php echo $row['name']; ?></h2>
            
            <p>Quantity: 
                <a href="updatecart.php?action=decrease&id=<?php echo $row['cart_id']; ?>">-</a>
                <?php echo $row['quantity']; ?>
                <a href="updatecart.php?action=increase&id=<?php echo $row['cart_id']; ?>">+</a>
            </p>
            <p>Total: <?php echo number_format($itemTotal, 2); ?> USD</p> <!-- Display total for this item -->
            <a href="removecart.php?id=<?php echo $row['cart_id']; ?>">Remove</a>
        </div>
    <?php }
    echo "<h3>Total Amount: " . number_format($totalAmount, 2) . " USD</h3>";
    } ?>
</div>
</body>
</html>
