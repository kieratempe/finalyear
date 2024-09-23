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

// Get user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize a variable for success message
$update_success = "";

// Handle form submission to update user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $position = $_POST['position'];

    try {
        // Update user information in the database (use user_id instead of id)
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ?, position = ? WHERE user_id = ?");
        $stmt->execute([$first_name, $last_name, $email, $phone_number, $position, $user_id]);
        
        // Set a success message
        $update_success = "Account information updated successfully!";
    } catch (PDOException $e) {
        echo 'Update failed: ' . $e->getMessage();
        exit;
    }
}

// Fetch user information from the database again to display updated info
try {
    // Fetch user info based on user_id instead of id
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone_number, ic_number, position FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit;
    }
} catch (PDOException $e) {
    echo 'Query failed: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account - HR HARMONY</title>
    <link rel="stylesheet" href="css/account.css" />
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-links">
            <div class="logo">
                <img src="img/companylogo.svg" />
                <span>HR HARMONY</span>
            </div>

            <div class="sidebar-item">
                <a href="dashboard.php"> <img src="img/icons/dashboard.svg" />Dashboard</a>
            </div>

            <div class="sidebar-item">
                <a href="tasks.php"><img src="img/icons/tasks.svg" /> Task</a>
            </div>

            <div class="sidebar-item">
                <a href="#"><img src="img/icons/appraisal.svg" /> Appraisal</a>
            </div>

            <div class="sidebar-item">
                <a href="#"><img src="img/icons/payment.svg" />Payslip</a>
            </div>

            <div class="sidebar-item">
                <a href="#"><img src="img/icons/training.svg" />Training</a>
            </div>

            <div class="sidebar-item">
                <a href="account.php"><img src="img/icons/account.svg" /> Account</a>
            </div>

            <div class="sidebar-item">
                <a href="logout.php"> <img src="img/icons/exit.svg" />Logout</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="header">
            <h1>HR HARMONY</h1>
        </div>

        <div class="account-content">
            <h2>Account Information</h2>

            <?php if ($update_success): ?>
                <p class="success-message"><?php echo $update_success; ?></p>
            <?php endif; ?>

            <form action="account.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required />
                </div>

                <div class="form-group">
                    <label for="ic_number">IC Number</label>
                    <input type="text" id="ic_number" name="ic_number" value="<?php echo htmlspecialchars($user['ic_number']); ?>" disabled />
                </div>

                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>" required />
                </div>

                <div class="form-group">
                    <button type="submit">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
