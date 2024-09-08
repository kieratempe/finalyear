<?php
session_start();
// Include database connection file
require_once 'db_connection.php';

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = htmlspecialchars($_POST['task']);
    $user_id = $_SESSION['user_id']; // Assuming you store user ID in session

    // Insert task into database
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
    $stmt->execute([$user_id, $task]);
}

// Fetch tasks
$stmt = $pdo->prepare("SELECT task FROM tasks WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Tasks - HR HARMONY</title>
    <link rel="stylesheet" href="css/dashboard.css" />
</head>
<body>
    <div class="sidebar">
        <!-- Include sidebar content here -->
        <div class="sidebar-item">
            <a href="index.php"> <img src="img/icons/dashboard.svg" />Dashboard</a>
        </div>
        <div class="sidebar-item">
            <a href="tasks.php"><img src="img/icons/tasks.svg" /> Task</a>
        </div>
        <!-- Other sidebar items -->
    </div>

    <div class="main">
        <div class="header">
            <h1>Manage Tasks</h1>
        </div>

        <div class="task-management-content">
            <form method="post">
                <input type="text" name="task" placeholder="New Task" required />
                <button type="submit">Add Task</button>
            </form>

            <h2>Tasks</h2>
            <ul>
                <?php foreach ($tasks as $task): ?>
                    <li><?php echo htmlspecialchars($task); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
