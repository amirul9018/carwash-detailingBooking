<?php
// cancel_booking.php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../ITP/login.php"); // Redirect to login if not authorized
    exit();
}

// Establish the connection to the database
$conn = new mysqli('localhost', 'root', '', 'carwash');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the booking ID from the URL
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Prepare a DELETE statement to remove the booking
    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id); // Assuming booking_id is an integer

    if ($stmt->execute()) {
        // Successfully canceled the booking
        $_SESSION['message'] = "Booking canceled successfully!";
    } else {
        // Failed to cancel the booking
        $_SESSION['message'] = "Error canceling booking: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "Invalid booking ID.";
}

// Close the connection
$conn->close();

// Redirect back to manage_bookings.php
header("Location: manage_bookings.php");
exit();
?>
