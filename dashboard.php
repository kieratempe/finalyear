<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Get user information from the session
$user_first_name = $_SESSION['first_name'];

// Include database connection
require_once('db_connection.php');

// Fetch dynamic data
try {
    // Fetch total number of employees
    $stmt = $pdo->query("SELECT COUNT(*) FROM employees");
    $total_employees = $stmt->fetchColumn();

    // Fetch pending tasks count
    $stmt = $pdo->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'");
    $pending_tasks = $stmt->fetchColumn();

    // Fetch upcoming training sessions count
    $stmt = $pdo->query("SELECT COUNT(*) FROM training_sessions");
    $upcoming_training_sessions = $stmt->fetchColumn();

    // Fetch payroll processed percentage
    $stmt = $pdo->query("SELECT processed_percentage FROM payroll ORDER BY id DESC LIMIT 1");
    $payroll_processed = $stmt->fetchColumn();
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
		<title>Dashboard - HR HARMONY</title>
		<link rel="stylesheet" href="css/dashboard.css" />
	</head>
	<body>
		<div class="sidebar">
			<div class="sidebar-links">
				<div class="logo">
					<img src="img/companylogo.svg" />
					<span>HR HARMONY</span>
				</div>

				<div class="sidebar-item">
					<a href="#"> <img src="img/icons/dashboard.svg" />Dashboard</a>
				</div>

				<div class="sidebar-item">
    <a href="tasks.php"><img src="img/icons/tasks.svg" /> Task</a>
</div>


				<div class="sidebar-item">
					<a href="#"><img src="img/icons/appraisal.svg" /> Appraisal</a>
				</div>

				<div class="sidebar-item">
					<a href="#"> <img src="img/icons/payment.svg" />Payslip</a>
				</div>

				<div class="sidebar-item">
					<a href="#"> <img src="img/icons/training.svg" />Training</a>
				</div>

                <div class="sidebar-item">
    <a href="profile.php"> <img src="img/icons/account.svg" />Profile</a>
</div>


				<div class="sidebar-item">
					<a href="logout.php"> <img src="img/icons/exit.svg" />Logout</a>
				</div>
			</div>
		</div>

		<div class="main">
        <div class="header">
            <h1>HR HARMONY</h1>
            <div class="searchbar">
                <input type="search" placeholder="Search..." />
                <img src="img/icons/search.svg" alt="Search Icon" />
            </div>
            <img src="img/icons/notification.svg" alt="Notification Icon" />
            <img src="img/icons/account.svg" alt="Account Icon" />
        </div>

        <div class="dashboard-content">
            <div class="title">
                <h1>Welcome, <?php echo htmlspecialchars($user_first_name); ?>!</h1>
            </div>

            <div class="card-section">
                <!-- Total Employees Card -->
                <div class="card">
                    <span>Total Employees</span>
                    <h2><?php echo htmlspecialchars($total_employees); ?></h2>
                    <img src="img/icons/employees.svg" alt="Employees Icon" />
                </div>

                <!-- Pending Tasks Card -->
                <div class="card">
                    <span>Pending Tasks</span>
                    <h2><?php echo htmlspecialchars($pending_tasks); ?></h2>
                    <img src="img/icons/tasks.svg" alt="Tasks Icon" />
                </div>

                <!-- Upcoming Training Sessions Card -->
                <div class="card">
                    <span>Training Sessions</span>
                    <h2><?php echo htmlspecialchars($upcoming_training_sessions); ?></h2>
                    <img src="img/icons/training.svg" alt="Training Icon" />
                </div>

                <!-- Payroll Status Card -->
                <div class="card">
                    <span>Payroll Processed</span>
                    <h2><?php echo htmlspecialchars($payroll_processed); ?>%</h2>
                    <img src="img/icons/payment.svg" alt="Payroll Icon" />
                </div>
            </div>

            <div class="monthly-overview">
                <p>Monthly Overview</p>
                <img src="img/icons/chart-placeholder.svg" alt="Chart" />
            </div>
        </div>
    </div>
</body>
</html>
