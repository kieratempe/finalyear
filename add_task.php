<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_harmony";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$description = $_POST['description'];
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Insert task into the database
$sql = "INSERT INTO tasks (description, user_id, status) VALUES (?, ?, 'pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $description, $user_id);

if ($stmt->execute()) {
    header("Location: tasks.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
