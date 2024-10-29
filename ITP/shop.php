<?php
// Include the database connection file
require('config.php'); // Ensure this file contains the proper database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch products from the database
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed; /* Set your wallpaper image path */
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        header {
            background-color: rgba(0, 123, 255, 0.9); /* Add transparency for a more modern look */
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
            border: 1px solid transparent;
        }

        nav ul li a.active,
        nav ul li a:hover {
            background-color: #0056b3;
            border: 1px solid white;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        section h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: calc(25% - 20px); /* Smaller box: Adjust to 25% */
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-5px);
        }

        .product-item img {
    width: 200px; /* Set a fixed width */
    height: 200px; /* Set a fixed height */
    object-fit: cover; /* Ensures the image fills the area without distortion */
    display: block;
    margin: 0 auto; /* Centers the image horizontally */
}


        .product-details {
            padding: 10px;
        }

        .product-details h3 {
            font-size: 18px; /* Adjusted to be slightly smaller */
            color: #007bff;
            margin-bottom: 8px;
        }

        .product-details p {
            font-size: 13px; /* Smaller text for description */
            color: #555;
            margin-bottom: 10px;
        }

        .product-details .price {
            font-size: 16px; /* Smaller font size for price */
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-details .buy-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 12px; /* Smaller button */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none; /* Remove border for a cleaner button */
        }

        .product-details .buy-btn:hover {
            background-color: #218838;
        }

        footer {
            background-color: rgba(0, 123, 255, 0.9);
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

        @media (max-width: 768px) {
            .product-item {
                width: calc(50% - 20px); /* 2 items per row on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .product-item {
                width: 100%; /* 1 item per row on extra small screens */
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Shop</h1>
        <nav>
            <ul>
                <li><a href="../ITP/mainpage.php">Dashboard</a></li>
                <li><a href="../ITP/checkout.php">Checkout</a></li>
                <li><a href="../ITP/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Featured Products</h2>
            <div class="product-list">

                <?php
                // Check if any products are returned from the database
                if ($result->num_rows > 0) {
                    // Loop through the products and display them
                    while ($row = $result->fetch_assoc()) {
                        // Ensure product has a 'product_id' field to display
                        if (!empty($row['product_id'])) {
                        ?>
                        <div class="product-item">
                            <img src="<?php echo !empty($row['image_path']) ? $row['image_path'] : 'default-image.jpg'; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="product-details">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                <div class="price">RM<?php echo number_format($row['price'], 2); ?></div>
                                <a href="../ITP/product_detail.php?product_id=<?php echo urlencode($row['product_id']); ?>" class="buy-btn">Buy Now</a>

                            </div>
                        </div>
                        <?php
                        } else {
                            echo "<p>Product ID missing for {$row['name']}.</p>";
                        }
                    }
                } else {
                    // If no products found
                    echo "<p>No products available at the moment.</p>";
                }
                ?>

            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>
