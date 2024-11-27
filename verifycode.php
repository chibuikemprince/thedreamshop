<!DOCTYPE html>
<html>
<head>
    <title>Enter Product Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type=text] {
            width: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Product Code</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="product_code" placeholder="Enter Product Code" required>
            <br><br>
            <button type="submit">Go to Product</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $product_code = $_POST["product_code"];

        // Extract the product ID from the product code
        $product_id = extractProductId($product_code);

        if ($product_id !== null) {
            // Redirect to the product.php page with the product ID
            header("Location: product.php?pid=" . $product_id);
            exit;
        } else {
            echo "<p style='color:red;'>Invalid product code. Please try again.</p>";
        }
    }

    function extractProductId($product_code) {
        $pattern = "/^[A-Z]{3}(\d+)[A-Z]{3}$/";
        if (preg_match($pattern, $product_code, $matches)) {
            return $matches[1];
        }
        return null;
    }
    ?>
</body>
</html>