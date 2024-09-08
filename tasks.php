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

// Handle mark as completed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_task_id'])) {
    $task_id = (int)$_POST['complete_task_id'];

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
        $stmt->execute([$task_id]);
        header("Location: tasks.php"); // Redirect to refresh the page
        exit;
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
        exit;
    }
}

// Handle task deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task_id'])) {
    $task_id = (int)$_POST['delete_task_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);
        header("Location: tasks.php"); // Redirect to refresh the page
        exit;
    } catch (PDOException $e) {
        echo 'Query failed: ' . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tasks - HR HARMONY</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <style>


        /* Task Management Specific Styles */
        .task-form {
            background-color: var(--card-bg-color);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .task-form h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .task-form form{
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .task-form input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .task-form button {
            align-self: center;
            width: 50%;
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .task-form button:hover {
            background-color: var(--primary-hover-color);
        }

        .task-list {
            background-color: var(--card-bg-color);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .task-list h2 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .task-list ul {
            list-style-type: none;
        }

        .task-list li {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ddd;
}

/* Flex container for task actions (status and buttons) */
.task-actions {
    display: flex;
    gap: 10px; /* Add space between status and buttons */
}

/* Task description aligned left */
.task-list .task-description {
    flex-grow: 1; /* Takes up remaining space on the left */
}

/* Task status and buttons aligned right */


        .task-list .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .task-list .status.pending {
            background-color: #f8d7da;
            color: #721c24;
        }

        .task-list .status.completed {
            background-color: #d4edda;
            color: #155724;
        }

        .task-list button {
    background-color: #28a745; /* Green color for completed button */
    color: white;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
}

.task-list button:hover {
    background-color: #218838; /* Darker green on hover */
}

.delete-button {
            background-color: #dc3545; /* Red color for delete button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333; /* Darker red on hover */}


    </style>
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
            <span class="task-description"><?php echo htmlspecialchars($task['description']); ?></span>
            <div class="task-actions">
                <span class="status <?php echo htmlspecialchars($task['status']); ?>">
                    <?php echo htmlspecialchars($task['status']); ?>
                </span>
                <?php if ($task['status'] == 'pending'): ?>
                    <form action="tasks.php" method="post" style="display:inline;">
                        <input type="hidden" name="complete_task_id" value="<?php echo $task['id']; ?>" />
                        <button type="submit">Mark as Completed</button>
                    </form>
                <?php endif; ?>
                <form action="tasks.php" method="post" style="display:inline;">
                    <input type="hidden" name="delete_task_id" value="<?php echo $task['id']; ?>" />
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
            </div>
        </div>
    </div>
</body>
</html>