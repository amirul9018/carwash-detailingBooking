<?php
// Start the session
session_start();

// Database connection
$servername = "localhost"; // Change this to your server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "carwash"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session (assuming user is logged in and user_id is stored in session)
$user_id = $_SESSION['user_id']; // Ensure you set this during user login

// Initialize total variable
$total = 0;

// Handle adding products to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = $_POST['product_id'];
    // Check if product already exists in the cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists, increase quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
    } else {
        // Product not found, add new item to cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $quantity = 1; // Default quantity
        $stmt->bind_param('iii', $user_id, $product_id, $quantity);
        $stmt->execute();
    }
    $stmt->close();
}

// Handle removal of products from the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = $_POST['product_id'];
    // Remove product from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Prepare cart items for display
$result_cart = $conn->query("SELECT c.*, p.name, p.price FROM cart c JOIN product p ON c.product_id = p.product_id WHERE c.user_id = $user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        header {
            background-color: #007BFF;
            color: white;
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
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        /* Main Section */
        main {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
        }

        /* Cart Table Styles */
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
            text-align: center;
        }
        table tbody tr:hover {
            background-color: #f9f9f9;
        }
        table tbody button {
            background-color: #FF5722;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        table tbody button:hover {
            background-color: #e64a19;
        }

        /* Total and Checkout Button Styles */
        h3 {
            text-align: right;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .checkout-btn {
            display: inline-block;
            text-align: center;
            padding: 12px 20px;
            background-color: #28A745;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }

        /* Footer Styles */
        footer {
            text-align: center;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            table thead {
                display: none;
            }
            table tbody tr {
                display: block;
                margin-bottom: 10px;
            }
            table tbody td {
                display: block;
                text-align: right;
                padding: 10px;
                position: relative;
            }
            table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                text-align: left;
                font-weight: bold;
            }
            h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>My Cart</h1>
        <nav>
            <ul>
                <li><a href="..//ITP/mainpage.php">Dashboard</a></li>
                <li><a href="..//ITP/address_book.php">Address Book</a></li>
                <li><a href="..//ITP/checkout.php">Checkout</a></li>
                <li><a href="..//ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Shopping Cart</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP to dynamically populate cart items -->
                <?php
                if ($result_cart && $result_cart->num_rows > 0) {
                    while ($row = $result_cart->fetch_assoc()) {
                        $subtotal = $row['quantity'] * $row['price'];
                        $total += $subtotal;
                        echo "<tr>
                                <td data-label='Product Name'>{$row['name']}</td>
                                <td data-label='Quantity'>{$row['quantity']}</td>
                                <td data-label='Price'>RM{$row['price']}</td>
                                <td data-label='Total'>RM" . number_format($subtotal, 2) . "</td>
                                <td data-label='Actions'>
                                    <form action='cart.php' method='post'>
                                        <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                        <button type='submit' name='action' value='remove'>Remove</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Total: RM<?php echo number_format($total, 2); ?></h3>
        <a href="..//ITP/checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </main>

    <footer>
        <p>&copy; 2024 Your Online Store. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
