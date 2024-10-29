<?php
session_start();  // Start session to track logged-in users

$servername = "localhost";  // Update this to your database server
$username = "root";         // Update this to your database username
$password = "";             // Update this to your database password
$dbname = "carwash";        // Update this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in by checking session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Get user ID from session

// Include any necessary PHP code for form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs to prevent SQL injection
    function sanitize_input($data, $conn) {
        return mysqli_real_escape_string($conn, trim($data));
    }

    // Update personal information
    if (isset($_POST['update_info'])) {
        $name = sanitize_input($_POST['name'], $conn);
        $email = sanitize_input($_POST['email'], $conn);
        $phone = sanitize_input($_POST['phone'], $conn);

        // Update the user info in the database
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE user_id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Personal information updated successfully.');</script>";
        } else {
            echo "Error updating information: " . $conn->error;
        }
    }

    // Change password
    if (isset($_POST['change_password'])) {
        $current_password = sanitize_input($_POST['current_password'], $conn);
        $new_password = sanitize_input($_POST['new_password'], $conn);
        $confirm_password = sanitize_input($_POST['confirm_password'], $conn);

        // Fetch the current password from the database
        $result = $conn->query("SELECT password FROM users WHERE user_id='$user_id'");
        $row = $result->fetch_assoc();

        // Validate the current password (passwords should be hashed and compared)
        if (password_verify($current_password, $row['password'])) {
            if ($new_password === $confirm_password) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password='$hashed_password' WHERE user_id='$user_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Password changed successfully.');</script>";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "<script>alert('New passwords do not match.');</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
        }
        header h1 {
            text-align: center;
            margin: 0;
            font-size: 26px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            text-align: center;
        }
        nav ul li {
            display: inline-block;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        nav ul li a:hover {
            text-decoration: underline;
        }
        main {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        section {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        section h2 {
            font-size: 22px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
        }
        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Account Settings</h1>
        <nav>
            <ul>
                <li><a href="../ITP/mainpage.php" class="active">Dashboard</a></li>
                <li><a href="../ITP/bookawash.php">Book a Wash</a></li>
                <li><a href="../ITP/my_bookings.php">My Bookings</a></li>
                <li><a href="../ITP/customer_settings.php">Account Settings</a></li>
                <li><a href="../ITP/shop.php">Online Shop</a></li>
                <li><a href="../ITP/support.php">Support</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Update Personal Information</h2>
            <form action="" method="post">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <button type="submit" name="update_info">Update Information</button>
            </form>
        </section>

        <section>
            <h2>Change Password</h2>
            <form action="" method="post">
                <div class="input-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="input-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
