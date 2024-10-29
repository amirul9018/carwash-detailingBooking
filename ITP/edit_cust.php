<?php
// Include your database connection file
include('config.php');

// Check if customer ID is provided
if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];
    
    // Fetch customer data based on the provided ID and ensure the user is a customer
    $query = "SELECT * FROM users WHERE user_id = $customer_id AND role = 'customer'";
    $result = mysqli_query($conn, $query);
    
    // Check if the customer exists
    if (mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);

        // Check if the form is submitted for updating
        if (isset($_POST['update'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            
            // Update query to modify customer details in the database
            $update_query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE user_id = $customer_id";
            
            // Execute the update query
            if (mysqli_query($conn, $update_query)) {
                // Redirect back to the manage customers page with success message
                echo "<script>alert('Customer updated successfully!'); window.location='manage_cust.php';</script>";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Customer not found.";
    }
} else {
    echo "No customer ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/wallpaper.png') no-repeat center center fixed;
            background-size: cover;           
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Customer</h1>
        <form method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>">

            <button type="submit" name="update">Update Customer</button>
        </form>
    </div>
</body>
</html>
