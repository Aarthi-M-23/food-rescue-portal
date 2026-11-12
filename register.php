<?php
session_start();
include('config/db.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password securely
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $message = "<div style='color: red;'>Email is already registered!</div>";
    } else {
        $sql = "INSERT INTO users (name, email, password, role, phone, address) 
                VALUES ('$name', '$email', '$password', '$role', '$phone', '$address')";

        if ($conn->query($sql) === TRUE) {
            $message = "<div style='color: green;'>Registration successful! <a href='index.php'>Login here</a></div>";
        } else {
            $message = "<div style='color: red;'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Food Rescue Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="card-auth">
        <h2>Create an Account</h2>
        <?php echo $message; ?>
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name or Organization" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <label for="role">Select Your Role:</label>
            <select name="role" id="role" required>
                <option value="donor">Food Donor (Restaurant/Mess/Household)</option>
                <option value="receiver">Receiver (Shelter/Orphanage/Individual)</option>
                <option value="volunteer">Volunteer (Delivery Partner)</option>
            </select>

            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="address" placeholder="Full Pick-up / Delivery Address" rows="3" required></textarea>

            <button type="submit" class="btn">Sign Up</button>
        </form>
        <a class="footer-link" href="index.php">Already have an account? Login here</a>
    </div>
</div>

</body>
</html>