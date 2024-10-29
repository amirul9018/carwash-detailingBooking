<?php
// delete_customer.php

include('config.php');

if (isset($_GET['id'])) {
    $customer_id = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM Users WHERE user_id = ? AND role = 'customer'");
    $stmt->bind_param("i", $customer_id); // "i" indicates the parameter is an integer

    if ($stmt->execute()) {
        echo "<script>alert('Customer deleted successfully!'); window.location='manage_cust.php';</script>";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
} else {
    echo "No customer selected.";
}

$conn->close();
?>
