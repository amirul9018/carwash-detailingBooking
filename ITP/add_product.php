<?php
// Include the database connection file
require('config.php'); // Ensure connection to the database

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Initialize message variables
    $message = "";

    // Image upload handling
    $target_dir = "uploads/";  // Directory to save the image
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 500KB)
    if ($_FILES["image"]["size"] > 500000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if upload is allowed
    if ($uploadOk == 1) {
        // Save the image to the 'uploads/' directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert product details and image path into the 'product' table
            // Ensure the column names match your database schema
            $sql_product = "INSERT INTO product (name, category, price, stock, description, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt_product = $conn->prepare($sql_product);
            if ($stmt_product === false) {
                die("Error preparing product query: " . $conn->error);
            }

            // Bind parameters including the image path
            $stmt_product->bind_param("ssdiss", $name, $category, $price, $stock, $description, $target_file);

            if ($stmt_product->execute()) {
                $message = "New product added successfully.";
                // Redirect after successful insert
                header("Location: manage_product.php");
                exit();
            } else {
                $message = "Error inserting product: " . $stmt_product->error;
            }

            $stmt_product->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
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

        nav ul li a:hover {
            background-color: #003f7f;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            color: #007bff;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
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

            .form-group button {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Add New Product</h1>
        <nav>
            <ul>
                <li><a href="manage_product.php">Back to Product List</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section>
            <h2>Product Details</h2>
            <form action="add_product.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="Cleaning">Cleaning</option>
                        <option value="Accessories">Accessories</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
                <div class="form-group">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" name="description" rows="4" placeholder="Enter product description here" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit">Add Product</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>

    <?php if (isset($message)) { ?>
        <script>
            alert("<?php echo $message; ?>");
        </script>
    <?php } ?>
</body>
</html>
