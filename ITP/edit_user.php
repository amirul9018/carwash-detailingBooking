<?php
// Database connection (adjust your connection details)
$host = 'localhost';
$db = 'carwash'; // Change to your actual database name
$user = 'root'; // Change to your actual DB username
$pass = ''; // Change to your actual DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Initialize variables
$userId = $_GET['user_id'] ?? null;
$userData = [];
$errorMessage = ''; // For error handling
$successMessage = ''; // For success message

// Fetch user data if user ID is provided
if ($userId) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
    $userData = $stmt->fetch();

    // Check if user exists
    if (!$userData) {
        die('User not found!');
    }
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Validate input
    if (empty($name) || empty($email) || empty($role)) {
        $errorMessage = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Invalid email format.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?');
            $stmt->execute([$name, $email, $role, $userId]);
            $successMessage = 'User updated successfully!';
            // Redirect after successful update
            header('Location: manage_user.php');
            exit;
        } catch (Exception $e) {
            $errorMessage = 'Failed to update user: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        /* Add your styles here, or you can include the same styles from manage_users.php */
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }
        /* Additional styles for input groups and messages */
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input, .input-group select {
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit User</h1>
        <nav>
            <ul>
                <li><a href="../ITP/dashboard.php">Dashboard</a></li>
                <li><a href="../ITP/manage_users.php">Manage Users</a></li>
                <li><a href="../ITP/manage_bookings.php">Manage Bookings</a></li>
                <li><a href="../ITP/settings.php">Settings</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Edit User</h2>
            <?php if ($errorMessage): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <div class="success-message"><?= htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($userData['name']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="Customer" <?= $userData['role'] === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="Admin" <?= $userData['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit">Update User</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
