<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Implement logic to update the user's personal information in your database
    // Connect to database and perform the update
    // For example:
    // $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?");
    // $stmt->execute([$name, $email, $phone, $_SESSION['user_id']]);

    // Redirect or show a success message
    header("Location: ../ITP/customer_settings.php?status=success");
    exit;
}
?>
