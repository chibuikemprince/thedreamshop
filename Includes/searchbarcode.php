<?php
include_once("../database/constants.php");
 
// Create connection
$conn = new mysqli("localhost", "root", "", "project_inv");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to search product by barcode ID
function searchProductByBarcodeID($barcodeID) {
    global $conn;

    $sql = "SELECT * FROM products WHERE barcodeid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $barcodeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Check if barcode ID is provided in the request
if (isset($_POST['barcodeID'])) {
    $barcodeID = $_POST['barcodeID'];
    $product = searchProductByBarcodeID($barcodeID);

    if ($product) {
       // echo json_encode($product);
       echo "found";
    } else {
        echo "Product not found.";
    }
} else {
    echo "Barcode ID not provided.";
}

$conn->close();
?>