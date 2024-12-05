$(document).ready(function () {
  var DOMAIN = "http://localhost/TheDreamShop";

  addNewRow();

  $("#add").click(function () {
    addNewRow();
  });

  function addNewRow() {
    $.ajax({
      url: DOMAIN + "/includes/process.php",
      method: "POST",
      data: { getNewOrderItem: 1 },
      success: function (data) {
        $("#invoice_item").append(data);
        var n = 0;
        $(".number").each(function () {
          $(this).html(++n);
        });
      },
    });
  }
  $("#remove").click(function () {
    $("#invoice_item").children("tr:last").remove();
    calculate(0, 0);
  });

  $("#invoice_item").delegate(".pid", "change", function () {
    var pid = $(this).val();
    var tr = $(this).parent().parent();
    $(".overlay").show();
    $.ajax({
      url: DOMAIN + "/includes/process.php",
      method: "POST",
      dataType: "json",
      data: { getPriceAndQty: 1, id: pid },
      success: function (data) {
        tr.find(".tqty").val(data["product_stock"]);

        tr.find(".pro_name").val(data["product_name"]);
        tr.find(".qty").val(1);
        tr.find(".price").val(data["product_price"]);

        tr.find(".amt").html(tr.find(".qty").val() * tr.find(".price").val());
        calculate(0, 0);
      },
    });
  });

  $("#invoice_item").delegate(".qty", "keyup", function () {
    var qty = $(this);
    var tr = $(this).parent().parent();

    if (isNaN(qty.val())) {
      alert("Please Enter a valid quantity");
      qty.val(1);
    } else {
      if (qty.val() - 0 > tr.find(".tqty").val() - 0) {
        alert("Stock overflow");
        qty.val(1);
      } else {
        tr.find(".amt").html(qty.val() * tr.find(".price").val());
        calculate(0, 0);
      }
    }
  });
  function calculate(dis, paid) {
    var sub_total = 0;
    var profit = 0;
    // var stock_price = 0;
    var net_total = 0;
    var discount = dis;
    var paid_amt = paid;
    var due = 0;
    $(".amt").each(function () {
      sub_total = sub_total + $(this).html() * 1;
      net_total = sub_total;
      profit = (net_total * discount) / 100;
      net_total = net_total + profit;
      due = net_total - paid_amt;
    });

    $("#sub_total").val(sub_total);
    $("#t_profit").val(profit);
    $("#net_total").val(net_total);
    $("#due").val(due);
  }
  $("#discount").keyup(function (params) {
    var discount = $(this).val();
    calculate(discount, 0);
  });
  $("#paid").keyup(function (params) {
    var paid = $(this).val();
    var discount = $("#discount").val();
    calculate(discount, paid);
  });

  // order taking

  $("#order_form").click(function () {
    var invoice = $("#get_order_data").serialize();

    if ($("#cust_name").val() === "") {
      alert("Please Enter Customer Name");
    } else if ($("#paid").val() === "") {
      alert("Please Enter the Amount to Pay");
    } else {
      $.ajax({
        url: DOMAIN + "/includes/process.php",
        method: "POST",
        data: $("#get_order_data").serialize(),
        success: function (data) {
          localStorage.setItem("cartItems", JSON.stringify([]));

          if (data == "Order failed due to Insufficient stock") {
            alert("Order Failed due to Insufficient Stock");
            window.location.href = DOMAIN + "../new_order.php";
          } else {
            if (confirm("Print Invoice ?")) {
              printInvoice();
            }
          }

          setTimeout(function () {
            //    $("#get_order_data").trigger("reset");
          }, 500);
        },
      });
    }
  });

  function getSelectedContent(selectHTML) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(selectHTML, "text/html");
    const selectElement = doc.querySelector("select");

    // Get the currently selected option
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    return selectedOption.textContent;
    // Log the selected option's value and text
    //console.log("Selected Value:", selectedOption.value);
    // console.log("Selected Text:", selectedOption.textContent);
  }

  function printInvoice() {
    // Extract form values
    const orderDate = document.getElementById("order_date").value;
    const customerName = document.getElementById("cust_name").value;

    //console.log({ customerName });
    const phone = document.getElementById("phone").value;
    const subTotal = document.getElementById("sub_total").value;
    const discount = document.getElementById("discount").value;
    const netTotal = document.getElementById("net_total").value;
    const paid = document.getElementById("paid").value;
    const profit = document.getElementById("t_profit").value;
    const due = document.getElementById("due").value;
    const paymentType = document.getElementById("payment_type").value;

    // Extract order items from the table
    const invoiceItems = [];
    const rows = document.querySelectorAll("#invoice_item tr");
    rows.forEach((row) => {
      const cells = row.querySelectorAll("td");
      if (cells.length > 0) {
        invoiceItems.push({
          no: cells[0].children[0].innerText,
          itemName:
            cells[1].children[0].options[cells[1].children[0].selectedIndex]
              .innerText,
          totalQuantity: cells[2].children[0].value,
          quantity: cells[3].children[0].value,
          price: cells[4].children[0].value,
          total: cells[5].children[0].innerText,
        });
      }
    });

    // Create a new window for the invoice
    const invoiceWindow = window.open("", "Invoice", "width=800,height=600");

    // Generate the invoice HTML
    const invoiceHTML = `
        <html>
        <head>
            <title>Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .invoice-header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .invoice-header h1 {
                    margin: 0;
                }
                .invoice-details, .invoice-summary {
                    margin-bottom: 20px;
                }
                .invoice-details table, .invoice-summary table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .invoice-details th, .invoice-details td, .invoice-summary th, .invoice-summary td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                .invoice-details th, .invoice-summary th {
                    background-color: #f2f2f2;
                }
                .invoice-items table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .invoice-items th, .invoice-items td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                }
                .invoice-items th {
                    background-color: #f2f2f2;
                }
                .text-right {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <h1>Dreams Tuck Shop</h1>
                <p>Invoice</p>
            </div>
            <div class="invoice-details">
                <table>
                    <tr>
                        <th>Order Date</th>
                        <td>${orderDate}</td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td>${customerName}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>${phone}</td>
                    </tr>
                </table>
            </div>
            <div class="invoice-items">
                <h3>Order Items</h3>
                <table>
                    <thead>
                        <tr>
                             
                            <th>Item Name</th>
                             
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${invoiceItems
                          .map(
                            (item) => `
                            <tr>
                                 
                                <td>${item.itemName}</td>
                                 
                                <td>${item.quantity}</td>
                                <td>${item.price}</td>
                                <td>${item.total}</td>
                            </tr>
                        `
                          )
                          .join("")}
                    </tbody>
                </table>
            </div>
            <div class="invoice-summary">
                <h3>Summary</h3>
                <table>
                    <tr>
                        <th>Sub Total</th>
                        <td class="text-right">${subTotal}</td>
                    </tr>
                    
                    <tr>
                        <th>Net Total</th>
                        <td class="text-right">${netTotal}</td>
                    </tr>
                    <tr>
                        <th>Paid</th>
                        <td class="text-right">${paid}</td>
                    </tr>
                    
                    
                    <tr>
                        <th>Payment Method</th>
                        <td class="text-right">${paymentType}</td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
    `;

    // Write the invoice HTML to the new window and print it
    invoiceWindow.document.write(invoiceHTML);
    invoiceWindow.document.close();
    invoiceWindow.print();
  }
});

