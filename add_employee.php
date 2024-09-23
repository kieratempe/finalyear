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

// Initialize variables
$firstName = $lastName = $email = $phoneNumber = $icNumber = $password = $position = "";
$errors = [];

// Process the form when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["first_name"])) {
        $errors[] = "First name is required.";
    } else {
        $firstName = htmlspecialchars(trim($_POST["first_name"]));
    }

    if (empty($_POST["last_name"])) {
        $errors[] = "Last name is required.";
    } else {
        $lastName = htmlspecialchars(trim($_POST["last_name"]));
    }

    if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    if (empty($_POST["phone_number"]) || !preg_match("/^[0-9]{10}$/", $_POST["phone_number"])) {
        $errors[] = "A valid phone number is required.";
    } else {
        $phoneNumber = htmlspecialchars(trim($_POST["phone_number"]));
    }

    if (empty($_POST["ic_number"])) {
        $errors[] = "IC number is required.";
    } else {
        $icNumber = htmlspecialchars(trim($_POST["ic_number"]));
    }

    if (empty($_POST["password"])) {
        $errors[] = "Password is required.";
    } else {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); // Hash the password
    }

    if (empty($_POST["position"])) {
        $errors[] = "Position is required.";
    } else {
        $position = htmlspecialchars(trim($_POST["position"]));
    }

    // If there are no errors, proceed with insertion
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone_number, ic_number, password, position) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $icNumber, $password, $position]);
            
            // Redirect back to EmployeeList.php with a success message
            header("Location: EmployeeList.php?success=Employee added successfully.");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Error adding employee: " . $e->getMessage();
        }
    }
}

// If there were errors, store them in the session and redirect back
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: EmployeeList.php");
    exit;
}