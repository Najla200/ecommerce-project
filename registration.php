<?php
// Include the database connection file
include('db.php');

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email and password are empty
    if (empty($email) || empty($password)) {
        echo "Please enter both email and password.";
    } else {
        // Check if the email already exists in the database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);//These are called prepared statements
        $stmt->bind_param("s", $email);//bind_param is used to bind values to the placeholder in the sql query
        $stmt->execute();
        $result = $stmt->get_result();
        //reason for using bind_param is to prevent sql injection
        //SQL injection is a type of security vulnerability that occurs
        // when an attacker manipulates a database query by injecting 
        //malicious SQL code through user input fields.

        if ($result->num_rows > 0) {
            echo "Email already taken. Please use a different email.";
        } else {
            // Hash the password--why??
            //Before storing the password, we use password_hash to convert it into a safe, scrambled version.
            //uses a hashing algorithm (PASSWORD_DEFAULT)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert the new user into the database
            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Account created successfully.Please login.');
                window.location.href='login.html';</script>";
            } else {
                echo "<script>alert('Error creating account. Please try again.');
                window.history.back();</script>";
            }
        }
    }
}
?>

