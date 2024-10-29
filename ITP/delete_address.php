<?php
// Step 1: Establish database connection
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "carwash";       // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Handle address deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // SQL query to delete the address by ID
    $sql = "DELETE FROM addressbook WHERE address_id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the address book page after deletion
        header("Location: address_book.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
