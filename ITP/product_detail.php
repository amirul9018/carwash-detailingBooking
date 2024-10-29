<?php
// Include the database connection file
require('config.php'); // Ensure this file contains the proper database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Check if product_id is set in the URL
if (isset($_GET['product_id'])) {
    // Decode the URL-encoded product_id and assign it to $product_id
    $product_id = urldecode($_GET['product_id']);
    echo "Product ID: " . htmlspecialchars($product_id); // For debugging purposes

    // Prepare and execute the query to get product details
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
    $stmt->bind_param('s', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a product was found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Product ID not provided.";
    exit;
}

// Handle Add to Cart submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    // Check if user_id is set in the session
    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $product_price = $_POST['product_price']; // Get the product price from the form

    // Prepare and execute the query to insert the product into the cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, price) VALUES (?, ?, ?)");
    $stmt->bind_param('ssd', $user_id, $product_id, $product_price);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added to cart successfully.');</script>";
    } else {
        echo "<script>alert('Error adding product to cart: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        header h1 {
            margin: 0;
            font-size: 32px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 15px 0 0 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .product-detail {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .product-image {
            flex: 1;
            max-width: 500px;
        }
        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .product-info {
            flex: 2;
        }
        .product-info h2 {
            font-size: 32px;
            color: #007bff;
        }
        .product-info p {
            font-size: 16px;
            color: #555;
        }
        .product-info .price {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .product-info .buy-btn,
        .product-info .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none;
            display: inline-block;
            margin-right: 10px;
        }
        .product-info .buy-btn:hover,
        .product-info .add-to-cart-btn:hover {
            background-color: #218838;
        }
        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.2);
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Product Detail</h1>
        <nav>
            <ul>
                <li><a href="../ITP/mainpage.php">Dashboard</a></li>
                <li><a href="../ITP/address_book.php">Address Book</a></li>
                <li><a href="../ITP/checkout.php">Checkout</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="product-detail">
            <div class="product-image">
                <img src="<?php echo !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'default-image.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="price">RM<?php echo number_format($product['price'], 2); ?></div>

                <!-- Add to Cart Button -->
                <form action="" method="post" style="display: inline;">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                </form>

                <!-- Buy Now Button -->
                <a href="shop.php" class="buy-btn">More Product</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Shop. All rights reserved.</p>
    </footer>
</body>
</html>
