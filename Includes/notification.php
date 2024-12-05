<?php
 
 
$con = mysqli_connect("localhost", "root", "", "project_inv");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get current date and calculate the threshold date
$currentDate = new DateTime();
$thresholdDate = $currentDate->modify('+3 days')->format('Y-m-d');

// Prepare the SQL statement
$sql = "SELECT p.product_name, p.expiry_date, c.catagory_name, b.brand_name, p.product_stock 
        FROM products p 
        JOIN catagories c ON p.cid = c.cid 
        JOIN brands b ON p.bid = b.bid 
        WHERE p.expiry_date < ? OR p.product_stock < ?";

$stmt = $con->prepare($sql);
$stockThreshold = 4;
$stmt->bind_param("si", $thresholdDate, $stockThreshold);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$con->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($notifications);
?>