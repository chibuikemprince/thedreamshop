<?php

  include_once("../tem/header.php")  ;

?>

<!DOCTYPE html>
<html>
  <head>
     
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Dreams Tuck Shop  - checkout</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/97c02f89cd.js"></script>
	 
	<script type="text/javascript" rel="stylesheet" src="../js/notification.js"></script>




    <link rel="stylesheet" href="style.css" />
    <script src="script.js"></script>

    <style>
      .barcode-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ccc;
      }
      .barcode-item button {
        background-color: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        cursor: pointer;
      }




      
    </style>
  </head>
  <body>

    <h1 class="mt-4 mb-4">Barcode Checkout</h1>
    <input type="text" id="barcode-input" placeholder="Scan or Enter Barcode ID" />
    <button id="add-barcode">Add Barcode</button>
    <button id="clear-barcodes">Clear Barcodes</button>
    <button id="checkout-btn">Checkout</button>

    <ul id="barcode-list"></ul>

    <script>
      const barcodeInput = document.getElementById("barcode-input");
      const addBarcodeBtn = document.getElementById("add-barcode");
      const clearBarcodesBtn = document.getElementById("clear-barcodes");
      const checkoutBtn = document.getElementById("checkout-btn");
      const barcodeList = document.getElementById("barcode-list");

      let cartItems = getUniqueCartItemsFromLocalStorage();

      function getUniqueCartItemsFromLocalStorage() {
        const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
        const uniqueCartItems = [...new Set(cartItems)];
        return uniqueCartItems;
      }

      function checkout() {
        localStorage.setItem(
          "cartItems",
          JSON.stringify([
            ...getUniqueCartItemsFromLocalStorage(),
            ...cartItems,
          ])
        );
        window.location.assign(DOMAIN + "/new_order.php");
      }

      function addBarcodeToList(barcodeId) {
        const barcodeItem = document.createElement("li");
        barcodeItem.classList.add("barcode-item");
        barcodeItem.innerHTML = `
        <span>${barcodeId}</span>
        <button class="remove-barcode">Remove</button>
      `;
        barcodeList.appendChild(barcodeItem);

        const removeBtn = barcodeItem.querySelector(".remove-barcode");
        removeBtn.addEventListener("click", () => {
          barcodeItem.remove();
          cartItems = cartItems.filter((item) => item !== barcodeId);
        });

        cartItems.push(barcodeId);
      }

      addBarcodeBtn.addEventListener("click", () => {
        const barcodeId = barcodeInput.value.trim();
        if (barcodeId) {
          addBarcodeToList(barcodeId);
          barcodeInput.value = "";
        }
      });

      clearBarcodesBtn.addEventListener("click", () => {
        barcodeList.innerHTML = "";
        cartItems = [];
      });

      checkoutBtn.addEventListener("click", checkout);
    </script>
  </body>
</html>


