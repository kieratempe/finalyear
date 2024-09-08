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

// Fetch tasks
try {
    $stmt = $pdo->query("SELECT * FROM tasks");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Tasks - HR HARMONY</title>
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
					<a href="#"><img src="img/icons/account.svg" /> Account</a>
				</div>

				<div class="sidebar-item">
					<a href="logout.php"> <img src="img/icons/exit.svg" />Logout</a>
				</div>
			</div>
		</div>

    <div class="main">
        <div class="header">
            <h1>Tasks</h1>
        </div>

        <div class="dashboard-content">
            <div class="task-form">
                <h2>Add New Task</h2>
                <form action="add_task.php" method="post">
                    <input type="text" name="description" placeholder="Task Description" required />
                    <button type="submit">Add Task</button>
                </form>
            </div>

            <div class="task-list">
                <h2>Task List</h2>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <li>
                            <?php echo htmlspecialchars($task['description']); ?>
                            <span><?php echo htmlspecialchars($task['status']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
