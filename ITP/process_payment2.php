<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request.'); window.location.href='checkout.php';</script>";
    exit();
}

// Retrieve payment details from the POST request
$selected_address = $_POST['address_id'];
$total_amount = $_POST['total_amount'];
$payment_method = $_POST['payment_method'];
$bank_selection = isset($_POST['bank_selection']) ? $_POST['bank_selection'] : null; // For FPX only

// Simple validation
if (empty($selected_address) || empty($total_amount) || empty($payment_method)) {
    echo "<script>alert('Incomplete payment details.'); window.location.href='checkout.php';</script>";
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Process payment based on the payment method
if ($payment_method === 'fpx') {
    // Check if bank selection is empty
    if (empty($bank_selection)) {
        echo "<script>alert('Please select a bank for FPX payment.'); window.location.href='checkout.php';</script>";
        exit();
    }

    // Simulated payment status (replace this with actual payment gateway logic)
    $payment_status = "successful";

    if ($payment_status === "successful") {
        // Insert payment details into the database for FPX
        $stmt_payment = $conn->prepare("INSERT INTO paymentshop (user_id, address_id, amount, payment_method, bank_selection, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        
        // Correct bind_param with five placeholders (iidss for user_id, address_id, amount, payment_method, and bank_selection)
        $stmt_payment->bind_param("iidss", $user_id, $selected_address, $total_amount, $payment_method, $bank_selection);
        
        if ($stmt_payment->execute()) {
            // Fetch the cart items to delete them after successful payment
            $stmt_cart_items = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
            $stmt_cart_items->bind_param("i", $user_id);
            $stmt_cart_items->execute();
            $result_cart_items = $stmt_cart_items->get_result();

            // Delete the purchased items from the cart
            if ($result_cart_items->num_rows > 0) {
                while ($cart_item = $result_cart_items->fetch_assoc()) {
                    $product_id = $cart_item['product_id'];

                    // Delete each cart item after payment
                    $stmt_delete_cart_item = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                    $stmt_delete_cart_item->bind_param("ii", $user_id, $product_id);
                    $stmt_delete_cart_item->execute();
                    $stmt_delete_cart_item->close();
                }
            }

            // Close the statement for fetching cart items
            $stmt_cart_items->close();

            // Redirect to success page after storing payment details and clearing purchased items
            header("Location: payment_success.php");
            exit();
        } else {
            echo "<script>alert('Failed to save payment details. Please try again.'); window.location.href='checkout.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('FPX payment failed. Please try again.'); window.location.href='checkout.php';</script>";
        exit();
    }
} elseif ($payment_method === 'credit_card') {
    // Credit Card Payment Logic (Simulated payment status)
    $payment_status = "successful"; 
    
    if ($payment_status === "successful") {
        // Insert payment details into the database for Credit Card
        $stmt_payment = $conn->prepare("INSERT INTO paymentshop (user_id, address_id, amount, payment_method, created_at) VALUES (?, ?, ?, ?, NOW())");
        
        // Correct bind_param with four placeholders (iids for user_id, address_id, amount, payment_method)
        $stmt_payment->bind_param("iids", $user_id, $selected_address, $total_amount, $payment_method);
        
        if ($stmt_payment->execute()) {
            // Fetch the cart items to delete them after successful payment
            $stmt_cart_items = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
            $stmt_cart_items->bind_param("i", $user_id);
            $stmt_cart_items->execute();
            $result_cart_items = $stmt_cart_items->get_result();

            // Delete the purchased items from the cart
            if ($result_cart_items->num_rows > 0) {
                while ($cart_item = $result_cart_items->fetch_assoc()) {
                    $product_id = $cart_item['product_id'];

                    // Delete each cart item after payment
                    $stmt_delete_cart_item = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                    $stmt_delete_cart_item->bind_param("ii", $user_id, $product_id);
                    $stmt_delete_cart_item->execute();
                    $stmt_delete_cart_item->close();
                }
            }

            // Close the statement for fetching cart items
            $stmt_cart_items->close();

            // Redirect to success page after storing payment details and clearing purchased items
            header("Location: payment_success.php");
            exit();
        } else {
            echo "<script>alert('Failed to save payment details. Please try again.'); window.location.href='checkout.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Credit card payment failed. Please try again.'); window.location.href='checkout.php';</script>";
        exit();
    }
} else {
    // Invalid payment method
    echo "<script>alert('Invalid payment method selected.'); window.location.href='checkout.php';</script>";
    exit();
}
?>