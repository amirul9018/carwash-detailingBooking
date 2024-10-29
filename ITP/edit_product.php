<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the product details from the database when loading the page
$product_id = $_GET['id'] ?? null; // Assume product ID is passed via URL
if ($product_id) {
    $conn = new mysqli('localhost', 'root', '', 'carwash');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch product details from the database
    $result = $conn->query("SELECT * FROM product WHERE product_id='$product_id'");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $name = $product['name'];
        $category = $product['category'];
        $price = $product['price'];
        $stock = $product['stock'];
        $description = $product['description'];
        // Assign a default image if none exists
        $image_path = $product['image_path'] ?? 'images/default_image.jpg';
    } else {
        echo "Product not found.";
        exit;
    }
    $conn->close();
}

if (isset($_POST['submit'])) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'carwash');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input data
    $product_id = $_POST['product_id'];
    $name = $conn->real_escape_string($_POST['product_name']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = $conn->real_escape_string($_POST['price']);
    $stock = intval($_POST['stock']);
    $description = $conn->real_escape_string($_POST['description']);

    // File upload logic
    if (!empty($_FILES['product_image']['name'])) {
        $target_dir = "images/";
        
        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
                exit;
            }

            // Check file size (5MB max)
            if ($_FILES["product_image"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                exit;
            }

            // Upload file
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                // Save the path to the image in the database
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    } else {
        // If no new image uploaded, keep the current image
        $image_path = $_POST['current_image'] ?? "images/default_image.jpg";
    }

    // Update the product in the database
    $sql = "UPDATE product SET name='$name', category='$category', price='$price', stock='$stock', description='$description', image_path='$image_path' WHERE product_id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully!";
        // Optionally redirect to manage products page
        header("Location: manage_product.php");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* Your CSS styling here */
    </style>
</head>
<body>
    <header>
        <h1>Edit Product</h1>
        <!-- Add your navigation here -->
    </header>
    
    <main>
        <section>
            <h2>Edit Product - <?php echo htmlspecialchars($name); ?></h2>
            <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
                <!-- Hidden input for product ID -->
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                <div class="form-group">
                    <label for="product-name">Product Name</label>
                    <input type="text" id="product-name" name="product_name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="form-group">
                    <label for="product-category">Category</label>
                    <select id="product-category" name="category">
                        <option value="Cleaning" <?php echo $category === 'Cleaning' ? 'selected' : ''; ?>>Cleaning</option>
                        <option value="Accessories" <?php echo $category === 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                        <!-- Add more categories here -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="product-price">Price</label>
                    <input type="text" id="product-price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
                </div>

                <div class="form-group">
                    <label for="product-stock">Stock</label>
                    <input type="number" id="product-stock" name="stock" value="<?php echo $stock; ?>" required>
                </div>

                <div class="form-group">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="product-image">Product Image</label>
                    <input type="file" id="product-image" name="product_image">
                    <?php if (!empty($image_path)) : ?>
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Current Image" width="150">
                    <?php endif; ?>
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($image_path); ?>">
                </div>

                <div class="form-group">
                    <button type="submit" name="submit">Update Product</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Shop Management. All rights reserved.</p>
    </footer>
</body>
</html>
