<?php
session_start();
include('config/db.php');

// Security Check: Only logged-in Volunteers can accept delivery tasks
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $listing_id   = intval($_GET['id']);
    $volunteer_id = $_SESSION['user_id'];

    // 1. Update status in food_listings table to 'picked_up'
    $update_listing = "UPDATE food_listings SET status = 'picked_up' WHERE id = '$listing_id'";

    if ($conn->query($update_listing) === TRUE) {
        // 2. Assign volunteer in transactions table and set status to 'in_transit'
        $update_trans = "UPDATE transactions SET volunteer_id = '$volunteer_id', status = 'in_transit' WHERE listing_id = '$listing_id'";
        $conn->query($update_trans);

        header("Location: dashboard.php?msg=delivery_accepted");
        exit();
    } else {
        echo "<script>alert('Error accepting task.'); window.location.href='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>