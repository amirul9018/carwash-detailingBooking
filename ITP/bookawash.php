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

$user_id = $_SESSION['user_id'];

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_wash'])) {
    // Fetch the submitted form data
    $car_id = $_POST['car_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service_id = $_POST['service_id']; // Get the service package ID from the form
    $customer_request = isset($_POST['customer_request']) ? $_POST['customer_request'] : ''; // Capture the customer request

    // Double-check that car_id exists
    if (empty($car_id)) {
        echo "<script>alert('Please select a car.'); window.location.href='bookawash.php';</script>";
        exit();
    }

    // Check if the selected date and time slot is already booked
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE date = ? AND time = ?");
    $check_stmt->bind_param("ss", $date, $time);
    $check_stmt->execute();
    $check_stmt->bind_result($booking_count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($booking_count > 0) {
        echo "<script>alert('The selected date and time slot is not available. Please choose a different slot.'); window.location.href='bookawash.php';</script>";
        exit();
    }

    // Store the booking details in the session
    $_SESSION['booking'] = [
        'car_id' => $car_id,
        'date' => $date,
        'time' => $time,
        'service_id' => $service_id,
        'customer_request' => $customer_request // Store customer request
    ];

    // Redirect to the payment page
    header("Location: bookpay.php");
    exit();
}

// Fetch registered cars for the logged-in user
$stmt = $conn->prepare("SELECT car_id, make, model, year FROM cars WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch service packages along with price
$service_stmt = $conn->prepare("SELECT service_id, name, price FROM service_packages");
$service_stmt->execute();
$service_result = $service_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car Wash</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS for organization -->
    <style>
        /* Global Styles */
        header {
            background-color: rgba(0, 123, 255, 0.9); /* Slight transparency */
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: 'Arial', sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            color: #333;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .booking-container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .booking-container img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .booking-container h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 26px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .input-group select,
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .input-group select:focus,
        .input-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 500px) {
            .booking-container {
                padding: 20px;
            }

            .booking-container h2 {
                font-size: 22px;
            }

            button {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="booking-container">
            <img src="images/logo.png" alt="Logo">
            <h2>Book a Car Wash</h2>
			<a href="mainpage.php" class="car-registration-btn">Home</a>
            <form action="bookawash.php" method="POST">
                <div class="input-group">
                    <label for="car">Select Your Car:</label>
                    <select id="car" name="car_id" required>
                        <?php while ($car = $result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($car['car_id']) ?>">
                                <?= htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']) . ' (' . htmlspecialchars($car['year']) . ')' ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <a href="car_registration.php" class="car-registration-btn">Register a New Car</a>
                <div class="input-group">
                    <label for="date">Select Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="input-group">
                    <label for="time">Select Time</label>
                    <select id="time" name="time" required>
                        <?php
                        $start = new DateTime('10:00');
                        $end = new DateTime('18:00');
                        $interval = new DateInterval('PT30M');
                        $period = new DatePeriod($start, $interval, $end);
                        
                        foreach ($period as $time) {
                            $timeFormatted = $time->format('H:i');
                            echo "<option value=\"$timeFormatted\">$timeFormatted</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="service">Select Service</label>
                    <select id="service" name="service_id" required>
                        <?php while ($service = $service_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($service['service_id']) ?>">
                                <?= htmlspecialchars($service['name']) . ' - RM' . htmlspecialchars($service['price']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="customer_request">Additional Request</label>
                    <textarea id="customer_request" name="customer_request" rows="4" placeholder="Enter any special requests or notes here..."></textarea>
                </div>
                <button type="submit" name="book_wash">Book Wash</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connections
$stmt->close();
$service_stmt->close();
$conn->close();
?>