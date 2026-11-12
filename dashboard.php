<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$user_role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Food Rescue Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/app.js"></script>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>Food Rescue Portal</h1>
        <div>
            <span>Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong></span> | 
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">

        <div class="welcome-badge">
            <h2>Dashboard</h2>
            <p>You are logged in as a <span class="role-tag"><?php echo strtoupper($user_role); ?></span>.</p>
        </div>

        <?php if ($user_role === 'donor'): ?>
            <!-- DONOR VIEW -->
            <h3>Donor Panel</h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Have surplus food from a party, mess, or restaurant? Share it here!</p>
            
            <a href="post-food.php" class="btn" style="max-width: 220px; margin-bottom: 2rem;">+ Post Surplus Food</a>

            <h3 style="margin-top: 2rem;">Your Active Food Listings</h3>
            <div class="card-grid">
                <?php
                $result = $conn->query("SELECT * FROM food_listings WHERE donor_id = '$user_id' ORDER BY id DESC");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<h4>" . htmlspecialchars($row['food_title']) . "</h4>";
                        echo "<p><strong>Quantity:</strong> " . $row['quantity_servings'] . " servings</p>";
                        echo "<p><strong>Expires in:</strong> <span class='expiry-timer' data-expiry='" . $row['expiry_time'] . "'>Calculating...</span></p>";
                        echo "<p><strong>Status:</strong> <span style='font-weight: 700; color: #2563eb;'>" . strtoupper($row['status']) . "</span></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: #64748b;'>You haven't posted any food listings yet.</p>";
                }
                ?>
            </div>

        <?php elseif ($user_role === 'receiver'): ?>
            <!-- RECEIVER VIEW -->
            <h3>Available Food Feed</h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Below is available food near you that can be claimed for shelters, orphanages, or distribution.</p>

            <div class="card-grid">
                <?php
                $result = $conn->query("SELECT fl.*, u.name as donor_name FROM food_listings fl JOIN users u ON fl.donor_id = u.id WHERE fl.status = 'available' ORDER BY fl.id DESC");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<h4>" . htmlspecialchars($row['food_title']) . "</h4>";
                        echo "<p><strong>Donor:</strong> " . htmlspecialchars($row['donor_name']) . "</p>";
                        echo "<p><strong>Quantity:</strong> " . $row['quantity_servings'] . " servings</p>";
                        echo "<p><strong>Pickup Address:</strong> " . htmlspecialchars($row['pickup_address']) . "</p>";
                        echo "<p><strong>Expires in:</strong> <span class='expiry-timer' data-expiry='" . $row['expiry_time'] . "'>Calculating...</span></p>";
                        echo "<a href='claim-food.php?id=" . $row['id'] . "' class='btn'>Claim Food</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: #64748b;'>No available food listings at the moment. Please check back later!</p>";
                }
                ?>
            </div>

        <?php elseif ($user_role === 'volunteer'): ?>
            <!-- VOLUNTEER VIEW -->
            <h3>Volunteer Logistics Board</h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Help transport claimed food from donors to receivers.</p>

            <div class="card-grid">
                <?php
                $result = $conn->query("SELECT fl.*, u.name as donor_name FROM food_listings fl JOIN users u ON fl.donor_id = u.id WHERE fl.status = 'claimed' ORDER BY fl.id DESC");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='card'>";
                        echo "<h4>" . htmlspecialchars($row['food_title']) . "</h4>";
                        echo "<p><strong>Donor:</strong> " . htmlspecialchars($row['donor_name']) . "</p>";
                        echo "<p><strong>Quantity:</strong> " . $row['quantity_servings'] . " servings</p>";
                        echo "<p><strong>Pickup Address:</strong> " . htmlspecialchars($row['pickup_address']) . "</p>";
                        echo "<p><strong>Expires in:</strong> <span class='expiry-timer' data-expiry='" . $row['expiry_time'] . "'>Calculating...</span></p>";
                        echo "<a href='accept-delivery.php?id=" . $row['id'] . "' class='btn btn-orange'>Accept Delivery Task</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='color: #64748b;'>No pending deliveries that require volunteers right now.</p>";
                }
                ?>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>