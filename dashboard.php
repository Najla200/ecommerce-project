<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<header class="logo-header">
    <div class="logo-container">
        <img src="./logo1.png" alt="Logo" class="logo">
        <h1 class="site-name">VogueCart</h1>
    </div>
    <a href="logout.php">Logout</a>
    <a href="viewcart.php" class="cart-icon">Cart (<?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>)</a>
</header>
<div class="product-container">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="product">
            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
            <h2><?php echo $row['name']; ?></h2>
            <p><?php echo $row['price']; ?> USD</p>
            <form method="POST" action="addtocart.php">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php } ?>
</div>
</body>
</html>
