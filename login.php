<?php
// Include the database connection file
include('db.php');

// Start a session
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user's input from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate that the inputs are not empty
    if (empty($email) || empty($password)) {
        echo "Please enter both email and password.";
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
                echo "Incorrect password.";
            }
        } else {
            echo "No account found with that email.";
        }
    }
}
?>
