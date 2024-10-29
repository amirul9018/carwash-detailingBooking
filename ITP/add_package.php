<?php
// Include database configuration
include 'config.php';

// Handle form submission to add a new service package
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // Insert query
    $sql = "INSERT INTO service_packages (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $name, $description, $price); // 'ssd' means string, string, double

    if ($stmt->execute()) {
        $message = "Service package added successfully.";
    } else {
        $message = "Error adding package: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service Package</title>
    <style>
		body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }
		header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .top-bar {
            width: 100%;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: left;
            position: absolute;
            top: 0;
            left: 0;
        }
        .top-bar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #0056b3;
            transition: background-color 0.3s ease;
            float: right;
        }
        
        .form-container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-top: 70px; /* To account for the top bar */
        }
        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        .link-container {
            margin-top: 20px;
            text-align: center;
        }
        .link-container a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .link-container a:hover {
            color: #0056b3;
        }
		footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
	
    <div class="top-bar">
        
        
    </div>
    <div class="form-container">
		<a href="dashboard.php">Go to Dashboard</a>
        <h2>Add New Service Package</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Package Name</label>
                <input type="text" name="name" id="name" placeholder="Enter package name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Enter package description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price (RM)</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="Enter price" required>
            </div>
            <div class="form-group">
                <button type="submit">Add Package</button>
            </div>
        </form>
        <div class="link-container">
            <a href="../ITP/update_package.php">Update Service</a>
        </div>
    </div>
</body>
	<footer>
        <p>&copy; 2024 Online Car Wash Booking System. All rights reserved.</p>
    </footer>
</html>
