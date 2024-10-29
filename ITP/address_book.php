<?php
session_start();

// Step 1: Establish database connection
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "carwash";       

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get the user_id from the session
// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // The logged-in user's ID

// Step 3: Handle the form submission to add a new address
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_address'])) {
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    // Insert the new address into the database, linking to the user_id
    $sql = "INSERT INTO addressbook (address, city, postal_code, country, user_id) VALUES ('$address', '$city', '$postal_code', '$country', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to refresh the page and avoid form resubmission
        header("Location: address_book.php");
        exit(); // Important to exit after the redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Step 4: Handle delete request, making sure only the user can delete their own address
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Ensure that the user can only delete their own address
    $sql_delete = "DELETE FROM addressbook WHERE address_id = $delete_id AND user_id = $user_id";

    if ($conn->query($sql_delete) === TRUE) {
        // Redirect to avoid resubmission after deletion
        header("Location: address_book.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Book</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Header Styles */
        header {
            background-color: #007BFF;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        header h1 {
            margin-bottom: 10px;
        }
        nav ul {
            list-style-type: none;
        }
        nav ul li {
            display: inline;
            margin: 0 10px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        /* Main Section */
        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
        }

        /* Address Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead th {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table tbody td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        table tbody a {
            color: #FF5722;
            text-decoration: none;
            font-weight: bold;
        }

        /* Form Styles */
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
        }
        form label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        form input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 100%;
        }
        form button {
            grid-column: span 2;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }

        /* Footer Styles */
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
        }

        /* Responsive Design */
        @media(max-width: 600px) {
            form {
                grid-template-columns: 1fr;
            }
        }

        /* Button Styles */
        .back-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Address Book</h1>
    </header>

    <main>

        <h2>Address Book</h2>
        <table>
            <thead>
                <tr>
                    <th>Address</th>
                    <th>City</th>
                    <th>Postal Code</th>
                    <th>Country</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP to dynamically populate address data -->
                <?php
                // SQL query to fetch the address data for the logged-in user only
                $sql = "SELECT address_id, address, city, postal_code, country FROM addressbook WHERE user_id = $user_id";
                $result = $conn->query($sql);

                // Display the results in the table
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['address']}</td>
                                <td>{$row['city']}</td>
                                <td>{$row['postal_code']}</td>
                                <td>{$row['country']}</td>
                                <td><a href='address_book.php?delete_id={$row['address_id']}' onclick='return confirm(\"Are you sure you want to delete this address?\");'>Delete</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No addresses found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Add New Address</h3>
        <form action="address_book.php" method="post">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="postal_code">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <button type="submit" name="add_address">Add Address</button>
        </form>
		<a href="checkout.php" class="back-button">Go Back to Checkout</a>
    </main>

    <footer>
        <p>&copy; 2024 Your Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
