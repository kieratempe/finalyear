<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Include database connection
require_once('db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    $status = 'pending'; // Default status

    if (!empty($description)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (description, status) VALUES (?, ?)");
            $stmt->execute([$description, $status]);
            header("Location: tasks.php"); // Redirect to tasks page
            exit;
        } catch (PDOException $e) {
            echo 'Query failed: ' . $e->getMessage();
            exit;
        }
    }
} else {
    header("Location: tasks.php"); // Redirect if not a POST request
    exit;
}
?>
