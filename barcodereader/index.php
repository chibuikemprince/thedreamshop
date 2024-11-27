<?php

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barcode Scanner</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Barcode Scanner</h1>
    <div id="barcode-scanner"></div>
    <div class="result-container">
      <p>Scanned Barcode: <span id="barcode-result"></span></p>
    </div>
  </div>

  <script src="https://unpkg.com/quagga/dist/quagga.min.js"></script>
  <script src="script.js"></script>
</body>
</html>