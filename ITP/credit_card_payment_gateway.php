<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate credit card payment processing
$success = rand(0, 1); // Randomly determine if the payment is successful

if ($success) {
    // Redirect to payment success page
    header("Location: payment_success.php");
} else {
    // Redirect to payment failure page
    header("Location: payment_failure.php");
}
exit();
?>
