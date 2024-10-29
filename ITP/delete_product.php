<?php
// Include the database connection file
require('config.php'); // Ensure connection to the database

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the product ID from the URL
    $product_id = $_GET['id'];

    // Prepare the SQL statement to delete the product
    $sql_delete = "DELETE FROM product WHERE product_id = ?"; // Change 'id' to 'product_id'

    $stmt_delete = $conn->prepare($sql_delete);
    if ($stmt_delete === false) {
        die("Error preparing delete query: " . $conn->error);
    }

    // Bind the product ID to the statement
    $stmt_delete->bind_param("i", $product_id);

    // Execute the delete query
    if ($stmt_delete->execute()) {
        // Fetch the image path before deletion
        $sql_fetch = "SELECT image_path FROM product WHERE product_id = ?"; // Change 'id' to 'product_id'
        $stmt_fetch = $conn->prepare($sql_fetch);
        $stmt_fetch->bind_param("i", $product_id);
        $stmt_fetch->execute();
        $stmt_fetch->bind_result($image_path);
        $stmt_fetch->fetch();
        $stmt_fetch->close();

        // Now delete the product
        if ($stmt_delete->execute()) {
            // Remove the image file from the server if it exists
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // Redirect after successful deletion
            header("Location: manage_product.php?message=Product deleted successfully.");
            exit();
        } else {
            die("Error deleting product: " . $stmt_delete->error);
        }
    } else {
        die("Error executing delete query: " . $stmt_delete->error);
    }

    // Close the statement
    $stmt_delete->close();
} else {
    // Redirect if no ID is provided
    header("Location: manage_product.php?message=No product ID specified.");
    exit();
}
?>
