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
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$ic_number = $_POST['ic_number'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$position = $_POST['position'];

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Insert data into the database
$sql = "INSERT INTO users (first_name, last_name, email, phone_number, ic_number, password, position) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone_number, $ic_number, $password, $position);

if ($stmt->execute()) {
    // Get the auto-generated user_id
    $user_id = $conn->insert_id;

    // Start the session and store user_id
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['first_name'] = $first_name;

    // Redirect to login page or dashboard
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
