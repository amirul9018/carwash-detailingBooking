<?php
// Database configuration
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "carwash"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if service_id is set in POST request
if (isset($_POST['service_id'])) {
    $service_id = intval($_POST['service_id']); // Ensure it's an integer

    // Prepare a DELETE statement
    $stmt = $conn->prepare("DELETE FROM service_packages WHERE service_id = ?");
    $stmt->bind_param("i", $service_id); // "i" indicates the type is integer

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the service packages page with a success message
        header("Location: dashboard.php?message=Package deleted successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: dashboard.php?error=Error deleting package");
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    // If service_id is not set, redirect back with an error
    header("Location: your_service_packages_page.php?error=Invalid request");
    exit();
}

// Close the connection
$conn->close();
?>
