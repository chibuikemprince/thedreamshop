<?php
 
include_once("./database/constants.php");
 
$conn = mysqli_connect("localhost", "root", "", "project_inv");

  include_once("./tem/header.php")  ;

if (!isset($_SESSION["userid"])) {
	header("location:" . DOMAIN . "/");
}
function fetchProductsByBid($conn) {
    // Get the bid from the URL query
    $bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;

    // Prepare the SQL query
    $sql = "
        SELECT p.product_name, p.product_price, p.product_stock, 
               b.brand_name AS batch, c.catagory_name AS catagory
        FROM products p
        JOIN brands b ON p.bid = b.bid
        JOIN catagories c ON p.cid = c.cid
        WHERE p.bid = ?
    ";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $bid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch and display the results
        if ($result->num_rows > 0) {
            echo '<div class="product-list">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<h3>' . htmlspecialchars($row['product_name']) . '</h3>';
                echo '<p><strong>Price:</strong> $' . htmlspecialchars($row['product_price']) . '</p>';
                echo '<p><strong>Stock:</strong> ' . htmlspecialchars($row['product_stock']) . '</p>';
                echo '<p><strong>Batch:</strong> ' . htmlspecialchars($row['batch']) . '</p>';
                echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['catagory']) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>No products found for this bid.</p>';
        }

        $stmt->close();
    } else {
        echo '<p>Error preparing the SQL statement.</p>';
    }
}
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
.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product-item {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 15px;
    width: calc(33.333% - 20px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.product-item h3 {
    margin: 0 0 10px;
}

.product-item p {
    margin: 5px 0;
}
</style>
</head>


<?php
fetchProductsByBid($conn);
?>