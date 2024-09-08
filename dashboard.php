<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.html");
    exit;
}

// Get user information from the session
$user_first_name = $_SESSION['first_name'];
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
					<a href="#"><img src="img/icons/tasks.svg" /> Task</a>
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
					<a href="#"><img src="img/icons/account.svg" /> Account</a>
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
						<h2>120</h2>
						<!-- Replace with dynamic data -->
						<img src="img/icons/.svg" alt="Employees Icon" />
					</div>

					<!-- Pending Tasks Card -->
					<div class="card">
						<span>Pending Tasks</span>
						<h2>8</h2>
						<!-- Replace with dynamic data -->
						<img src="img/icons/tasks.svg" alt="Tasks Icon" />
					</div>

					<!-- Upcoming Training Sessions Card -->
					<div class="card">
						<span>Training Sessions</span>
						<h2>3</h2>
						<!-- Replace with dynamic data -->
						<img src="img/icons/training.svg" alt="Training Icon" />
					</div>

					<!-- Payroll Status Card -->
					<div class="card">
						<span>Payroll Processed</span>
						<h2>95%</h2>
						<!-- Replace with dynamic data -->
						<img src="img/icons/payment.svg" alt="Payroll Icon" />
					</div>
				</div>

				<div class="monthly-overview">
					<!-- You can add charts, graphs, or additional stats here -->
					<p>Monthly Overview</p>
					<!-- Example chart placeholder -->
					<img src="img/icons/chart-placeholder.svg" alt="Chart" />
				</div>
			</div>
		</div>
	</body>
</html>
