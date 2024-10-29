<?php
// Include database configuration
include 'config.php';

// Handle form submission to update the service package
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST["service_id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // Update query
    $sql = "UPDATE service_packages SET name=?, description=?, price=? WHERE service_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $name, $description, $price, $service_id);

    if ($stmt->execute()) {
        $message = "Service package updated successfully.";
    } else {
        $message = "Error updating package: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all packages for the dropdown list
$packages = [];
$result = $conn->query("SELECT service_id, name FROM service_packages");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Service Package</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #218838;
        }
        .message {
            margin-bottom: 15px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Service Package</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="service_id">Select Service Package</label>
                <select name="service_id" id="service_id" required>
                    <option value="">Select a package</option>
                    <?php foreach ($packages as $package): ?>
                        <option value="<?php echo $package['service_id']; ?>">
                            <?php echo $package['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Package Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" name="price" id="price" required>
            </div>
            <div class="form-group">
                <button type="submit">Update Package</button>
            </div>
			
			<a href="..//ITP/add_package.php">Go Back</a>
			<a href="..//ITP/dashboard.php">Home</a>
        </form>
    </div>
    <script>
        // JavaScript to fetch package details and populate form fields
        document.getElementById("service_id").addEventListener("change", function() {
            const serviceId = this.value;
            if (serviceId) {
                fetch(`update_service_package.php?fetch_package=true&service_id=${serviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("name").value = data.name;
                        document.getElementById("description").value = data.description;
                        document.getElementById("price").value = data.price;
                    })
                    .catch(error => console.error("Error fetching package details:", error));
            } else {
                // Clear the form fields if no package is selected
                document.getElementById("name").value = "";
                document.getElementById("description").value = "";
                document.getElementById("price").value = "";
            }
        });
    </script>
</body>
</html>

<?php
// PHP code to handle AJAX request for fetching package details
if (isset($_GET["fetch_package"]) && $_GET["fetch_package"] === "true" && isset($_GET["service_id"])) {
    $service_id = $_GET["service_id"];
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT name, description, price FROM service_packages WHERE service_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    header("Content-Type: application/json");
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Package not found"]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
