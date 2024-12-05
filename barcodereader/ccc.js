var searching = false;
//6.28.1

function searchProductByBarcode(barcodeID) {
  var DOMAIN = "http://localhost/TheDreamShop";

  if (searching == false) {
    searching = true;

    fetch(DOMAIN + "/includes/searchbarcode.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ barcodeID: barcodeID }),
    })
      .then((response) => {
        return response.text();
        searching = false;
      })
      .then((data) => {
        searching = false;

        // console.log(data);
        // window.location.assign(
        //   DOMAIN + "/new_order.php?barcodeid=" + "BIJ15BIJ"
        // );

        localStorage.setItem("barcodeid", barcodeID);

        if (data == "Product not found") {
          alert("Product not found");
          window.location.assign(DOMAIN + "/dashboard.php?newproduct=true");
        }

        if (data == "found") {
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

// Example usage

window.addEventListener("load", function () {
  Quagga.init(
    {
      inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector("#barcode-scanner"),
      },
      decoder: {
        readers: [
          "code_128_reader",
          "ean_reader",
          "ean_5_reader",
          "ean_2_reader",
          "ean_8_reader",
          "code_39_reader",
          "code_39_vin_reader",
          "codabar_reader",
          "upc_reader",
          "upc_e_reader",
          "i2of5_reader",
          "2of5_reader",
          "code_93_reader",
        ],
      },
      area: {
        // defines rectangle of the detection/localization area
        top: "0%", // top offset
        right: "0%", // right offset
        left: "0%", // left offset
        bottom: "0%", // bottom offset
      },
      locate: true,
      constraints: {
        width: { min: 640 },
        height: { min: 480 },
        facingMode: "environment",
        aspectRatio: { min: 1, max: 2 },
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
    searchProductByBarcode(result.codeResult.code);
  });
});
