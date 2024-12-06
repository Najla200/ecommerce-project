<?php
// Include the database connection file
include('db.php');

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Start a session
session_start();
$error_message="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user's input from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate that the inputs are not empty
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.');</script>";
    } else {
        // Query to retrieve the user's details from the database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the user's data
            $user = $result->fetch_assoc();

            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                // Set session variables to keep the user logged in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                 // Display alert and redirect to login page on incorrect password
                 echo "<script>alert('Incorrect password.');
                 window.location.href='login.html';</script>";
            }
        } else {
            // Display alert and redirect to login page on no account found
            echo "<script>alert('No account found with that email.');
            window.location.href='login.html';</script>";
        }
       
    }
}
?>
