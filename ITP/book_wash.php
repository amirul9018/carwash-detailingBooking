<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car Wash</title>
    <style>

        
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .booking-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .booking-container h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #007bff;
            outline: none;
        }

        .car-registration-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .car-registration-btn:hover {
            background-color: #218838;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 500px) {
            .booking-container {
                padding: 20px;
            }

            .booking-container h2 {
                font-size: 20px;
            }

            button {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Book a Car Wash</h2>
        <form action="booking.php" method="POST">
            <!-- Display Registered Cars -->
            <?php
            session_start();
            require 'config.php';

            // Check if the user is logged in
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit();
            }

            // Fetch registered cars for the logged-in user
            $user_id = $_SESSION['user_id'];
            $result = $conn->query("SELECT id, make, model, year FROM cars WHERE user_id = $user_id");

            echo '<div class="input-group">';
            echo '<label for="car">Select Your Car:</label>';
            echo '<select id="car" name="car_id" required>';
            while ($car = $result->fetch_assoc()) {
                echo '<option value="' . $car['id'] . '">' . $car['make'] . ' ' . $car['model'] . ' (' . $car['year'] . ')</option>';
            }
            echo '</select>';
            echo '</div>';
            ?>
            
            <!-- Button for registering a new car -->
            <a href="car_registration.html" class="car-registration-btn">Register a New Car</a>

            <div class="input-group">
                <label for="date">Select Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="input-group">
                <label for="time">Select Time</label>
                <input type="time" id="time" name="time" required>
            </div>
            <div class="input-group">
                <label for="service">Select Service</label>
                <select id="service" name="service" required>
                    <option value="basic">Basic Wash</option>
                    <option value="premium">Premium Wash</option>
                    <option value="deluxe">Deluxe Wash</option>
                </select>
            </div>
            <button type="submit" name="book_wash">Book Wash</button>
        </form>
    </div>
</body>
</html>
