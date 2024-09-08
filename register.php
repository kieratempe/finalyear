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
    echo "New record created successfully";
    // Redirect to login page or show a success message
    header("Location: login.html");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
