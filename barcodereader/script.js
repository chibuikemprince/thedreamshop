var searching = false;
var cartItems = [];
var DOMAIN = "http://localhost/TheDreamShop";

function searchProductByBarcode(barcodeID) {
  if (searching === false) {
    searching = true;

    fetch(DOMAIN + "/includes/searchbarcode.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ barcodeID: barcodeID }),
    })
      .then((response) => response.text())
      .then((data) => {
        searching = false;

        if (data === "Product not found") {
          alert("Product not found");
          window.location.assign(DOMAIN + "/dashboard.php?newproduct=true");
        } else if (data === "found") {
          alert("Product found");
          window.location.assign(
            DOMAIN + "/new_order.php?barcodeID=" + barcodeID
          );
        }
      })
      .catch((error) => {
        searching = false;
        console.error("Error searching product:", error);
      });
  }
}

window.addEventListener("load", function () {
  Quagga.init(
    {
      inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector("#barcode-scanner"),
        constraints: {
          width: 640,
          height: 480,
          facingMode: "environment",
        },

        singleChannel: false, // true: only the red color-channel is read
      },

      decoder: {
        readers: [
          "code_128_reader",
          "ean_reader",
          // "ean_5_reader",
          // "ean_2_reader",
          // "ean_8_reader",
          // "code_39_reader",
          // "code_39_vin_reader",
          // "codabar_reader",
          // "upc_reader",
          // "upc_e_reader",
          // "i2of5_reader",
          // "2of5_reader",
          // "code_93_reader",
        ],
        multiple: false,
      },
    },
    function (err) {
      if (err) {
        console.log(err);
        return;
      }
      console.log("Initialization finished. Ready to start");
      Quagga.start();
    }
  );

  Quagga.onDetected(function (result) {
    document.getElementById("barcode-result").textContent =
      result.codeResult.code;
    console.log({ result: result.codeResult.code });
    // searchProductByBarcode(result.codeResult.code);
  });

  // Add event listeners for the buttons
  document.getElementById("add-to-cart").addEventListener("click", addToCart);
  document.getElementById("checkout").addEventListener("click", checkout);
});

function addToCart() {
  var barcodeValue = document.getElementById("barcode-result").textContent;
  cartItems.push(barcodeValue);
  console.log("Added to cart:", cartItems);
}

function getUniqueCartItemsFromLocalStorage() {
  const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
  const uniqueCartItems = [...new Set(cartItems)];
  return uniqueCartItems;
}
function checkout() {
  localStorage.setItem(
    "cartItems",
    JSON.stringify([...getUniqueCartItemsFromLocalStorage(), ...cartItems])
  );
  // window.location.assign("order.html");
  window.location.assign(DOMAIN + "/new_order.php");
}
