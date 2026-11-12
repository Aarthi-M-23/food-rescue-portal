<?php
session_start();
include('config/db.php');

// Security Check: Only logged-in Receivers can claim food
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'receiver') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $listing_id  = intval($_GET['id']);
    $receiver_id = $_SESSION['user_id'];

    // 1. Update the status of the listing in food_listings table to 'claimed'
    $update_sql = "UPDATE food_listings SET status = 'claimed' WHERE id = '$listing_id' AND status = 'available'";

    if ($conn->query($update_sql) === TRUE && $conn->affected_rows > 0) {
        // 2. Create a record in the transactions table
        $trans_sql = "INSERT INTO transactions (listing_id, receiver_id, status) VALUES ('$listing_id', '$receiver_id', 'claimed')";
        $conn->query($trans_sql);

        header("Location: dashboard.php?msg=claimed_success");
        exit();
    } else {
        echo "<script>alert('Sorry! This item has already been claimed or is no longer available.'); window.location.href='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>