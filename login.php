<?php
// Start session to track user login status
session_start();

// Connect to the database
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Leave empty for XAMPP
$dbname = "hr_harmony"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Check if user exists in the database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['email'] = $user['email'];
        
        // Redirect to a dashboard or home page after successful login
        header("Location: dashboard.php");
    } else {
        // Wrong password
        echo "<p class='error-message'>Invalid password. Please try again.</p>";
    }
} else {
    // No user found with that email
    echo "<p class='error-message'>No account found with that email.</p>";
}

$stmt->close();
$conn->close();
?>
