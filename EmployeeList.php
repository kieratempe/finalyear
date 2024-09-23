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

// Fetch employees from the database
try {
    $stmt = $pdo->query("SELECT user_id, first_name, last_name, email, position FROM users");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Query failed: ' . $e->getMessage();
    exit;
}

// Handle deletion of an employee
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        header("Location: EmployeeList.php?success=Employee deleted successfully.");
        exit;
    } catch (PDOException $e) {
        echo 'Deletion failed: ' . $e->getMessage();
        exit;
    }
}

// Handle the form submission for editing an employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_id'])) {
    $userId = intval($_POST['edit_user_id']);
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $position = htmlspecialchars(trim($_POST['position']));

    try {
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, position = ? WHERE user_id = ?");
        $stmt->execute([$firstName, $lastName, $email, $position, $userId]);
        header("Location: EmployeeList.php?success=Employee updated successfully.");
        exit;
    } catch (PDOException $e) {
        echo 'Update failed: ' . $e->getMessage();
        exit;
    }
}

// Fetch details for editing if edit is requested
$editEmployee = null;
if (isset($_GET['edit'])) {
    $userId = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $editEmployee = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List - HR HARMONY</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/employeeList.css"> 
    <link rel="stylesheet" href="css/account.css"> <!-- Include account.css for styling the popup -->
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-links">
            <div class="logo">
                <img src="img/companylogo.svg" />
                <span>HR HARMONY</span>
            </div>

            <div class="sidebar-item">
                <a href="dashboard.php"><img src="img/icons/dashboard.svg" />Dashboard</a>
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
                <a href="#"><img src="img/icons/account.svg" /> Account</a>
            </div>

            <div class="sidebar-item">
                <a href="logout.php"><img src="img/icons/exit.svg" />Logout</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="header">
            <h1>Employee List</h1>
        </div>

        <div class="dashboard-content">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="6">No employees found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($employee['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($employee['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($employee['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($employee['email']); ?></td>
                                <td><?php echo htmlspecialchars($employee['position']); ?></td>
                                <td>
                                    <a href="?edit=<?php echo htmlspecialchars($employee['user_id']); ?>">Edit</a> | 
                                    <a href="?delete=<?php echo htmlspecialchars($employee['user_id']); ?>" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button id="addEmployeeBtn">Add Employee</button>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Employee</h2>
            <form action="add_employee.php" method="post">
                <input name="first_name" type="text" required placeholder="First Name">
                <input name="last_name" type="text" required placeholder="Last Name">
                <input name="email" type="email" required placeholder="Email">
                <input name="phone_number" type="text" required pattern="[0-9]{10}" placeholder="Phone Number">
                <input name="ic_number" type="text" required placeholder="IC Number">
                <input name="password" type="password" required placeholder="Password">
                <select name="position" required>
                    <option selected disabled hidden value="">Select Position</option>
                    <option>Manager</option>
                    <option>Employee</option>
                    <option>Lecturer</option>
                    <option>Admin</option>
                </select>
                <button type="submit">Add Employee</button>
            </form>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <?php if ($editEmployee): ?>
    <div id="editEmployeeModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editEmployeeModal').style.display='none'">&times;</span>
            <h2>Edit Employee</h2>
            <form action="" method="post">
                <input type="hidden" name="edit_user_id" value="<?php echo htmlspecialchars($editEmployee['user_id']); ?>">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input name="first_name" type="text" value="<?php echo htmlspecialchars($editEmployee['first_name']); ?>" required placeholder="First Name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input name="last_name" type="text" value="<?php echo htmlspecialchars($editEmployee['last_name']); ?>" required placeholder="Last Name">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input name="email" type="email" value="<?php echo htmlspecialchars($editEmployee['email']); ?>" required placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select name="position" required>
                        <option selected value="<?php echo htmlspecialchars($editEmployee['position']); ?>"><?php echo htmlspecialchars($editEmployee['position']); ?></option>
                        <option>Manager</option>
                        <option>Employee</option>
                        <option>Lecturer</option>
                        <option>Admin</option>
                    </select>
                </div>
                <button type="submit">Update Employee</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        var addModal = document.getElementById('addEmployeeModal');
        var editModal = document.getElementById('editEmployeeModal');
        var addBtn = document.getElementById('addEmployeeBtn');
        var closeModal = document.getElementsByClassName('close')[0];

        // Open Add Employee Modal
        addBtn.onclick = function() {
            addModal.style.display = "block";
        }

        // Close modals when clicking 'x' or outside
        closeModal.onclick = function() {
            if (editModal) {
                editModal.style.display = "none";
            }
            if (addModal) {
                addModal.style.display = "none";
            }
        }

        window.onclick = function(event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
