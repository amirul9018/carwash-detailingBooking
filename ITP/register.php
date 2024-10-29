<?php
session_start();

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];  // Get phone number
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'customer';  // Automatically set the role to 'customer'

    // Validate user input
    if (empty($name)) {
        $error = 'Name is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (empty($phone)) {
        $error = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = 'Phone number must be between 10 and 15 digits';
    } elseif (empty($password) || empty($confirm_password)) {
        $error = 'Password and Confirm Password are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Prepare SQL query to insert a new user
        $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $phone, $password, $role);
            // Execute statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page after successful registration
                header('Location: index.php');
                exit();
            } else {
                $error = 'Error occurred while registering. Please try again.';
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Database query failed';
        }
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Add logo styles */
        .register-container img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        .input-group label {
            font-weight: 600;
            font-size: 14px;
            color: #777;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 55%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .link {
            margin-top: 15px;
            color: #555;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 30px;
                max-width: 100%;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Add logo at the top -->
        <img src="images/logo.png" alt="Logo">

        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required pattern="\d{10,15}" title="Phone number must be between 10 to 15 digits">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i id="togglePassword" class="fas fa-eye toggle-password"></i>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <i id="toggleConfirmPassword" class="fas fa-eye toggle-password"></i>
            </div>
            <button type="submit" name="register" class="register-btn">Register</button>
        </form>
        <div class="link">
            <p>Already have an account? <a href="index.php">Login</a></p>
        </div>
    </div>

    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirm_password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
