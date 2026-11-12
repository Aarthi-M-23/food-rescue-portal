<?php
session_start();
include('config/db.php');

// Security Check: Only logged-in users with the 'donor' role can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_SESSION['user_id'];
    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $qty      = intval($_POST['quantity']);
    $expiry   = $_POST['expiry'];
    $address  = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "INSERT INTO food_listings (donor_id, food_title, description, quantity_servings, expiry_time, pickup_address, status) 
            VALUES ('$donor_id', '$title', '$desc', '$qty', '$expiry', '$address', 'available')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to dashboard after successfully adding food
        header("Location: dashboard.php?status=posted");
        exit();
    } else {
        $message = "<div style='color: red;'>Error posting listing: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Surplus Food - Food Rescue Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="card-auth" style="max-width: 500px;">
        <h2>Post Surplus Food</h2>
        <?php echo $message; ?>

        <form action="post-food.php" method="POST">
            <label>Food Item Title:</label>
            <input type="text" name="title" placeholder="e.g., Veg Biryani & Curd Rice" required>

            <label>Description & Notes:</label>
            <textarea name="description" rows="3" placeholder="e.g., Prepared 2 hours ago..." required></textarea>

            <label>Quantity (Approximate Servings):</label>
            <input type="number" name="quantity" placeholder="e.g., 25" min="1" required>

            <label>Expiry / Pick-up Cutoff Time:</label>
            <input type="datetime-local" name="expiry" required>

            <label>Pick-up Location Address:</label>
            <textarea name="address" rows="2" placeholder="Full street address and landmark" required></textarea>

            <button type="submit" class="btn">Publish Food Listing</button>
        </form>
        
        <a href="dashboard.php" class="footer-link">← Cancel and Back to Dashboard</a>
    </div>
</div>

</body>
</html>