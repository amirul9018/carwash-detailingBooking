<?php
// approve_booking.php

// Database connection
include 'config.php';

// Get booking ID from URL and cast it to an integer for security
$booking_id = (int)$_GET['id'];

// Prepare an SQL statement to update booking status to 'Approved'
$stmt = $conn->prepare("UPDATE bookings SET status='Approved' WHERE booking_id=?");
$stmt->bind_param("i", $booking_id);

// Execute the query
if ($stmt->execute()) {
    echo "Booking approved successfully.";
    // Redirect back to manage bookings page
    header("Location: manage_bookings.php");
} else {
    echo "Error updating record: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
