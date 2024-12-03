<?php 
$conn = new mysqli('localhost', 'root', '', 'users');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { 
        $user = $result->fetch_assoc();
    
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Redirect to the main webpage
            header("Location: index.html");
            exit; // Stop further script execution after the redirect
        } else {
            echo "Invalid password.";  
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
}

$conn->close();
?>
