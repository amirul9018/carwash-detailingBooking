<?php
// Database connection
$host = 'localhost'; // Your database host
$dbname = 'carwash'; // Your database name
$username = 'root'; // Your database username (default is usually 'root')
$password = ''; // Your database password (default is usually empty for 'root')

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("INSERT INTO support_requests (subject, message) VALUES (:subject, :message)");
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            // JavaScript for pop-up notification and redirection
            echo "<script>
                    alert('Support request submitted successfully.');
                    window.location.href = 'mainpage.php';
                  </script>";
        } else {
            echo "<script>alert('Error submitting support request.');</script>";
        }
    }
} catch (PDOException $e) {
    // Display a user-friendly error message
    echo "Connection failed: " . $e->getMessage();
}
?>
