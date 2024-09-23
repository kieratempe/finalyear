<?php
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

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Prepare SQL statement
$sql = "SELECT user_id, first_name, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

$stmt->execute();
$stmt->store_result();

// Check if user exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $first_name, $hashed_password);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Start the session and store user data
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $first_name;

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid password";
    }
} else {
    echo "No user found with this email";
}

$stmt->close();
$conn->close();
?>
