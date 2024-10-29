<?php
session_start();
require 'config.php'; // Assuming you have a config file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = $_GET['id'];

// Fetch the booking details
$stmt = $conn->prepare("
    SELECT b.booking_id, b.date, b.time, sp.name AS service_name 
    FROM bookings b 
    JOIN service_packages sp ON b.service_id = sp.service_id 
    WHERE b.booking_id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

// Close the statement
$stmt->close();

// If booking not found, redirect to my bookings page
if (!$booking) {
    header("Location: my_bookings.php");
    exit();
}

// Update booking details if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_date = $_POST['date'];
    $new_time = $_POST['time'];

    // Update the booking in the database
    $update_stmt = $conn->prepare("UPDATE bookings SET date = ?, time = ? WHERE booking_id = ? AND user_id = ?");
    $update_stmt->bind_param("ssii", $new_date, $new_time, $booking_id, $user_id);

    if ($update_stmt->execute()) {
        // Redirect to my bookings page after successful update
        header("Location: my_bookings.php");
        exit();
    } else {
        $error_message = "Failed to update the booking. Please try again.";
    }

    // Close the update statement
    $update_stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <style>
        /* Include styles for the page */
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        main {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input[type="date"],
        input[type="time"],
        button {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Booking</h1>
    </header>

    <main>
        <h2>Edit your booking details for "<?php echo htmlspecialchars($booking['service_name']); ?>"</h2>
        <form action="" method="POST">
            <label for="date">New Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($booking['date']); ?>" required>

            <label for="time">New Time:</label>
            <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>" required>

            <button type="submit">Update Booking</button>
        </form>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <p><a href="my_bookings.php">Go Back to My Bookings</a></p>
    </main>
</body>
</html>
