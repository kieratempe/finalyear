<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_harmony";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);

function generateUniqueId($conn) {
    $prefix = "HR";
    $stmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(ID, 3) AS UNSIGNED)) AS max_id FROM users WHERE ID LIKE ?");
    $like_pattern = $prefix . '%';
    $stmt->bind_param("s", $like_pattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'] ? $row['max_id'] : 0;
    $new_id = $prefix . str_pad($max_id + 1, 4, '0', STR_PAD_LEFT);
    $stmt->close();
    return $new_id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $ic_number = $_POST["ic_number"];
    $position = $_POST["position"];
    $password = $_POST["password"];  // Do not hash password

    // Generate a unique ID
    $id = generateUniqueId($conn);

    // Prepared statement with unique ID
    $stmt = $conn->prepare("INSERT INTO users (ID, first_name, last_name, email, phone_number, ic_number, position, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $id, $first_name, $last_name, $email, $phone_number, $ic_number, $position, $password);

    if ($stmt->execute()) {
        echo "Created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
