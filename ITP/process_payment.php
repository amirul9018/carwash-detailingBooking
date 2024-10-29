<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; // Assuming you have a config file for database connection

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request.'); window.location.href='bookawash.php';</script>";
    exit();
}

// Retrieve payment details from the POST request
$car_id = $_POST['car_id'];
$date = $_POST['date'];
$time = $_POST['time'];
$service_id = $_POST['service_id'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'];
$customer_request = !empty($_POST['customer_request']) ? $_POST['customer_request'] : '';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Insert booking into the database
$stmt_booking = $conn->prepare("INSERT INTO bookings (user_id, car_id, service_id, date, time, customer_request, created_at, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Pending')");
$stmt_booking->bind_param("iiisss", $user_id, $car_id, $service_id, $date, $time, $customer_request);

// Check if the booking is successful
if ($stmt_booking->execute()) {
    // Mock payment processing (replace with actual payment gateway integration)
    $payment_successful = processPayment($amount, $payment_method); // Example function to check payment status

    // Redirect based on payment status
    if ($payment_successful) {
        header("Location: payment_success.php");
        exit();
    } else {
        header("Location: payment_failure.php");
        exit();
    }
} else {
    echo "Error: " . $stmt_booking->error;
}

$stmt_booking->close();
$conn->close();

/**
 * Example function to mock payment processing
 * Replace this function with actual payment gateway integration
 */
function processPayment($amount, $payment_method) {
    // Simulate payment success or failure (for demonstration purposes)
    // Return true for success, false for failure
    return rand(0, 1) === 1; // Random success or failure
}
?>
