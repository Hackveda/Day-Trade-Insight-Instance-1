<?php
include('../db.php'); // Including db.php from the parent directory

date_default_timezone_set('Asia/Kolkata');

// Assuming db.php contains the database connection logic
// $conn is the mysqli connection object

// Set parameters and execute
$name = $_POST['name'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$webinar = "How to Pick a Good Stock for Intraday Trading?";
$date = date('Y-m-d H:i:s'); // Current date and time in IST

// Prepare and bind
$stmt = $con1->prepare("INSERT INTO webinar (Name, Email, Mobile, Webinar, Date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $mobile, $webinar, $date);

if ($stmt->execute()) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}

$stmt->close();
// Assuming db.php will handle the connection closure or it might be reused later.
?>
