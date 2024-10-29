<?php
// Connect to the database
$servername = "localhost"; // Replace with your database server details
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "carwash"; // Replace with your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to get customer data from the database
$sql = "SELECT user_id, name, email, phone FROM users"; // Replace 'users' with your table name
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Action Links */
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .action-links a {
            margin-right: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            table, th, td {
                font-size: 14px;
            }

            h1 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 20px;
            }

            th, td {
                font-size: 12px;
                padding: 8px 10px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Customers</h1>
		<a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($customer = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $customer['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            <td class="action-links">
                                <a href="edit_cust.php?id=<?php echo $customer['user_id']; ?>">Edit</a>
                                <a href="delete_cust.php?id=<?php echo $customer['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No customers found.</p>
        <?php } ?>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
