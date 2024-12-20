<!DOCTYPE html>
<html>
  <head>
    <title>Barcode Checkout</title>
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
    <h1>Barcode Scan</h1>
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
