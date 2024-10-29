<?php
session_start();
require 'config.php'; // Ensure this file contains your database connection info

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['register_car'])) {
    // Retrieve user inputs
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $license_plate = $_POST['license_plate'];
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in session

    // Handle file upload
    $car_image = $_FILES['car_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($car_image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image file type (optional security measure)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Check if the image is uploaded
    if (move_uploaded_file($_FILES['car_image']['tmp_name'], $target_file)) {
        // Insert data into the cars table
        $stmt = $conn->prepare("INSERT INTO cars (user_id, make, model, year, color, license_plate, car_photo, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("issssss", $user_id, $make, $model, $year, $color, $license_plate, $target_file);

        if ($stmt->execute()) {
            echo "Car registered successfully!";
            header("Location: bookawash.php"); // Redirect to bookawash page
            exit(); // Ensure no further code is executed after redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading the image.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Registration</title>
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

        .car-registration-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .car-registration-container h2 {
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
            .car-registration-container {
                padding: 20px;
            }

            .car-registration-container h2 {
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
    <div class="car-registration-container">
        <h2>Register Your Car</h2>
        <form action="../ITP/car_registration.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="make">Car Make</label>
                <input type="text" id="make" name="make" required>
            </div>
            <div class="input-group">
                <label for="model">Car Model</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div class="input-group">
                <label for="year">Year</label>
                <input type="number" id="year" name="year" required min="1900" max="2024">
            </div>
            <div class="input-group">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" required>
            </div>
            <div class="input-group">
                <label for="license_plate">Plate Number</label>
                <input type="text" id="license_plate" name="license_plate" required>
            </div>
            <div class="input-group">
                <label for="car_image">Upload Car Picture</label>
                <input type="file" id="car_image" name="car_image" accept="image/*" required>
            </div>
            <button type="submit" name="register_car">Register Car</button>
        </form>
    </div>
</body>
</html>
