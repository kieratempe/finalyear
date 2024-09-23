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
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* Profile Styles */
        .profile-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
	border-radius: 10px;
	border: 2px solid #d870e1;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	background-color: var(--card-bg-color);      
  }

        .profile-container h1, .profile-container h2 {
    color: var(--primary-text-color);
    font-size: 2em;
    text-align: center;
}


.profile-container table {
    margin-bottom: 20px;
    border-collapse: collapse;

}




.profile-container table th, .profile-container table td {
    padding: 15px;
    border: 1px solid #ddd;
}

.profile-container table th {
    background-color: #eaeaea;
    color: var(--primary-text-color);
    font-weight: 600;
    width: 15%;
    padding: 12px;
    text-align: left;
}

.profile-container input {
            width: 100%;
            padding: 5px;
            border: none;
            background-color: transparent;
            font-size: 1em;
        }

.profile-container form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    flex: 1;
    justify-content: space-around;
}


.profile-container input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    font-size: 1.1em;
}

.profile-container input:focus {
    border-color: var(--primary-color); /* Match this with the dashboard's theme */
    outline: none;
    background-color: #fff;
}


.profile-container button {
    width: 50%;
    align-self: center;
    background-color: #007bff; /* You can adjust this to match your dashboard’s main color */
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color .3s ease;
}

.profile-container button:hover {
    background-color: #0056b3; /* Match this with your accent color */
    transform: scale(1.02);
}


    </style>



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
                <h1> Profile </h1>
            </div>





            <div class="profile-container">

        <form id="profileForm" action="update_profile.php" method="POST">
        <h1>Profile</h1>
            <table>
                <tr>
                    <th>First Name</th>
                    <td>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>
                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Position</th>
                    <td>
                        <input type="text" name="position" value="<?php echo htmlspecialchars($user['position']); ?>" />
                    </td>
                </tr>
            </table>
            <button class="save-btn" type="submit">Save Changes</button>
        </form>
    </div>

    <script>
        // Optional: You can use AJAX to handle the form submission without reloading
        document.getElementById("profileForm").addEventListener("submit", function (e) {
            e.preventDefault();
            var formData = new FormData(this);

            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert('Profile updated successfully');
                console.log(data);
            })
            .catch(error => console.error('Error:', error));
        });
    </script>

    

</body>
</html>