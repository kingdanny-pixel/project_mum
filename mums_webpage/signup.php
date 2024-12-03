<?php
$servername = "localhost";
$username = "root";
$password = ""; // Default password for WAMP
$dbname = "users"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required!";
    } else {
        // Check if email or username already exists
        $check_sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            echo "Error: Username or email already exists!";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                // Redirect to login.html
                header("Location: login.html");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>
