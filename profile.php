<?php
// Start session to track user login status
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty for XAMPP
$dbname = "hr_harmony"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No profile found!";
    exit;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - HR HARMONY</title>
    <!-- Include your CSS file -->
    <link rel="stylesheet" href="css/profile.css">
    <style>
        /* Profile Styles */
        .profile-container {
            width: 60%;
            margin: 40px auto;
            background-color: #f4f4f4;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-container h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .profile-container table th, .profile-container table td {
            padding: 10px;
            text-align: left;
        }

        .profile-container table th {
            width: 30%;
            background-color: #eaeaea;
        }

        .profile-container form {
            display: flex;
            flex-direction: column;
        }

        .profile-container input {
            padding: 10px;
            margin: 5px 0 15px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .profile-container button {
            width: 50%;
            align-self: center;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .profile-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Your Profile</h1>

        <table>
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
            </tr>
            <tr>
                <th>Position</th>
                <td><?php echo htmlspecialchars($user['position']); ?></td>
            </tr>
        </table>

        <h2>Update Your Profile</h2>
        <form action="update_profile.php" method="POST">
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>