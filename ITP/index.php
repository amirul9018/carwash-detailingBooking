<?php
session_start();

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email';
    } elseif (empty($password)) {
        $error = 'Password is required';
    } else {
        // Prepare SQL query
        $sql = "SELECT user_id, email, password, role FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind email parameter
            mysqli_stmt_bind_param($stmt, 's', $email);
            // Execute statement
            mysqli_stmt_execute($stmt);
            // Get result
            $result = mysqli_stmt_get_result($stmt);

            // Check if user exists
            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Verify password (without hashing)
                if ($password === $user['password']) {
                    // Start session and store user data
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header('Location: dashboard.php');
                    } else {
                        header('Location: mainpage.php');
                    }
                    exit();
                } else {
                    $error = 'Invalid password';
                }
            } else {
                $error = 'Invalid email or password';
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
    <title>Login Page</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333333;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #000000;
            user-select: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            margin-top: 15px;
            color: #555555;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Add logo at the top -->
        <img src="images/logo.png" alt="Logo">

        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="index.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i id="togglePassword" class="fas fa-eye toggle-password"></i> <!-- Eye Icon -->
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <p><a href="reset_password.php">Forget Password?</a></p>
    </div>
    <script>
        // JavaScript to toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
