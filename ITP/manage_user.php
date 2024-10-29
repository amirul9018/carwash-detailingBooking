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

// Fetch users from the database
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 15px 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #0056b3;
            transition: background-color 0.3s;
        }

        nav ul li a.active {
            background-color: #003f7f;
        }

        nav ul li a:hover {
            background-color: #003f7f;
        }

        main {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        section h2 {
            color: #007bff;
            font-size: 22px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            color: #007bff;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .edit-btn, .delete-btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

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

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
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
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 24px;
            }

            nav ul li a {
                font-size: 14px;
                padding: 8px 16px;
            }

            main {
                padding: 15px;
            }

            section {
                padding: 15px;
            }

            section h2 {
                font-size: 20px;
            }

            table th, table td {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 20px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav ul li {
                margin-bottom: 10px;
            }

            nav ul li a {
                font-size: 14px;
                padding: 10px 20px;
            }

            .input-group input, .input-group select, button {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Users</h1>
        <nav>
            <ul>
                <li><a href="../ITP/dashboard.php">Dashboard</a></li>
                <li><a href="../ITP/manage_user.php" class="active">Manage Users</a></li>
                <li><a href="../ITP/manage_bookings.php">Manage Bookings</a></li>
                <li><a href="../ITP/view_reports.php">Customer Reports</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Users List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']); ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['user_id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete_user.php?id=<?= $user['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Add New User</h2>
            <form action="add_user.php" method="post">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="Customer">Customer</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit">Add User</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
