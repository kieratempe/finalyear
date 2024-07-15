<?php
session_start();

// Database connection details (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_harmony";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['user_id'] = $row['id']; // Store user ID in session
            header("Location: dashboard.php"); // Redirect to dashboard
            exit;
        } else {
            // Incorrect password
            $error = "Incorrect password";
        }
    } else {
        // User not found
        $error = "User not found";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="./css/login.css" />
</head>
<body>
    <div class="container">
        <div>
            <form class="login-section" action="login.php" method="post">
                <div class="logo-area">
                    <span> HR HARMONY</span>
                    <img src="./img/logo.svg" />
                </div>

                <div class="input-area">
                    <div class="inputbox">
                        <input name="email" type="text" required="required" />
                        <label class="placeholder">Email</label>
                    </div>

                    <div class="inputbox">
                        <input name="password" type="password" required="required" />
                        <label class="placeholder">Password</label>
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                <div class="error-message">
                    <p><?php echo $error; ?></p>
                </div>
                <?php endif; ?>

                <div class="login-area">
                    <button>LOGIN</button>
                </div>

                <div class="signup-area">
                    <span>Don't have an account? <a href="./signup.html">Sign up</a></span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>