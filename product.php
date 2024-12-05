<?php
$conn = mysqli_connect("localhost", "root", "", "project_inv");

  include_once("./tem/header.php")  ;
  
include_once("./database/constants.php");

if (!isset($_SESSION["userid"])) {
	header("location:" . DOMAIN . "/");
}

function generateProductCode($productId) {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomLetters = '';
    for ($i = 0; $i < 3; $i++) {
        $randomLetters .= $letters[array_rand(str_split($letters))];
    }
    return $randomLetters . $productId . $randomLetters;
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL parameter
$pid = isset($_GET['pid']) ? $_GET['pid'] : null;

if ($pid === null) {
    echo "<h1>No Product ID Provided</h1>";
    echo "<p>Please provide a valid product ID in the URL.</p>";
    exit;
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("SELECT * FROM products WHERE pid = ?");
$stmt->bind_param("i", $pid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Check if the product has expired
    $currentDate = date("Y-m-d");
    $expiryDate = $row['expiry_date'];
    $isExpired = ($expiryDate < $currentDate) ? true : false;

    $code_bar = $row['barcodeid'] ? $row['barcodeid']   :generateProductCode($pid);
    // Display the product details
    echo "<div class='product-container'>";
    echo "<div class='product-image'>";
   //  $url = urlencode("http://localhost/TheDreamShop/product.php?pid=" . $pid );
   echo "<img src='https://barcode.orcascan.com/?type=code128&data=".$code_bar ."' alt='" . $row['product_name'] . "'>";
    echo "</div>";
    echo "<div class='product-info'>";
    echo "<h1>" . $row['product_name'] . "</h1>";
    echo "<p>Price: $" . $row['product_price'] . "</p>";
    echo "<p>Stock: " . $row['product_stock'] . "</p>";
    echo "<p>Added Date: " . $row['added_date'] . "</p>";
    echo "<p>Status: " . ($row['p_status'] == '1' ? 'Active' : 'Inactive') . "</p>";
    echo "<p>Stock Price: $" . $row['stock_price'] . "</p>";
    echo "<p>Expiry Date: " . $row['expiry_date'] . "</p>";

    if ($isExpired) {
        echo "<p><strong>This product has expired.</strong></p>";
    }

    // Add the "Return to Manage Products" button
    echo "<a href='manage_product.php' class='btn'>Return to Manage Products</a>";
    echo "<!-- <a href='javascript:window.print()' class='btn'>Print</a> -->";
    echo "</div>";
    echo "</div>";
} else {
    echo "<h1>Product Not Found</h1>";
    echo "<p>The requested product does not exist.</p>";
}

$stmt->close();
$conn->close();
?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Dreams Tuck Shop</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/97c02f89cd.js"></script>
	<script type="text/javascript" rel="stylesheet" src="./js/notification.js"></script>
	 
<style>
.product-container {
    display: flex;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: 0 auto;
}

.product-image {
    flex: 1;
    text-align: right;
    margin-right: 120px;
}

.product-image img {
    max-width: 100%;
    height: auto;
}

.product-info {
    flex: 1;
}

h1 {
    color: #333;
    margin-top: 0;
}

p {
    color: #666;
    margin-bottom: 10px;
}

strong {
    color: #ff0000;
}

.btn {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
    margin-right: 10px;
}

.btn:hover {
    background-color: #0056b3;
}
</style>

</head>
