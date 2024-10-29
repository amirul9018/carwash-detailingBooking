<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking data is available in the session
if (!isset($_SESSION['booking'])) {
    echo "<script>alert('No booking information found. Please try booking again.'); window.location.href='bookawash.php';</script>";
    exit();
}

// Retrieve booking details from the session
$booking = $_SESSION['booking'];
$car_id = $booking['car_id'];
$date = $booking['date'];
$time = $booking['time'];
$service_id = $booking['service_id'];
$customer_request = $booking['customer_request'];

// Fetch the service details from the database
$service_stmt = $conn->prepare("SELECT name, price FROM service_packages WHERE service_id = ?");
$service_stmt->bind_param("i", $service_id);
$service_stmt->execute();
$service_stmt->bind_result($service_name, $service_price);
$service_stmt->fetch();
$service_stmt->close();

// Calculate the total amount
$amount = $service_price;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay for Booking - 918 Garage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            color: #333;
        }
        p {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #333;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .hidden {
            display: none;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Complete Your Payment</h3>
        <p>Service: <?= htmlspecialchars($service_name) ?></p>
        <p>Date: <?= htmlspecialchars($date) ?></p>
        <p>Time: <?= htmlspecialchars($time) ?></p>
        <p>Amount: RM <?= number_format($amount, 2) ?></p>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car_id) ?>">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
            <input type="hidden" name="time" value="<?= htmlspecialchars($time) ?>">
            <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
			<input type="hidden" name="customer_request" value="<?= htmlspecialchars($customer_request) ?>">
            
            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="payment_method" required>
                <option value="">Choose</option>
                <option value="fpx">Online Banking (FPX)</option>
                <option value="credit_card">Credit/Debit Card</option>
            </select>
            
            <div id="bankOptions" class="hidden">
                <label for="bankSelection">Select Your Bank:</label>
                <select id="bankSelection" name="bank_selection">
                    <option value="">Choose Bank</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB</option>
                    <option value="rhb">RHB</option>
                    <option value="bank_islam">Bank Islam</option>
                </select>
            </div>
            
            <button type="submit">Proceed to Pay</button>
        </form>
    </div>
    <script>
        document.getElementById("paymentMethod").addEventListener("change", function () {
            const bankOptions = document.getElementById("bankOptions");
            if (this.value === "fpx") {
                bankOptions.classList.remove("hidden");
            } else {
                bankOptions.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
