function ShowProductModal(event) {
  // Prevent the default form submission behavior
  event.preventDefault();

  // Get the product ID from the button's data attribute
  const productId = event.target.dataset.productId;

  // Generate a 6-character product code
  const productCode =
    productId.length < 6 ? generateProductCode(productId) : productId;

  // Create the modal element
  const modal = document.createElement("div");
  modal.classList.add("modal", "fade");
  modal.setAttribute("tabindex", "-1");
  modal.setAttribute("role", "dialog");
  modal.setAttribute("aria-labelledby", "modal-title");
  modal.setAttribute("aria-hidden", "true");

  // Create the modal dialog element
  const modalDialog = document.createElement("div");
  modalDialog.classList.add("modal-dialog", "modal-dialog-centered");
  modalDialog.setAttribute("role", "document");

  // Create the modal content element
  const modalContent = document.createElement("div");
  modalContent.classList.add("modal-content");

  // Create the modal header element
  const modalHeader = document.createElement("div");
  modalHeader.classList.add("modal-header");

  // Create the modal title element
  const modalTitle = document.createElement("h5");
  modalTitle.classList.add("modal-title");
  modalTitle.id = "modal-title";
  modalTitle.textContent = "Product QR Code";

  // Create the modal body element
  const modalBody = document.createElement("div");
  modalBody.classList.add("modal-body", "barcode-container");

  // Create the QR code image element
  const qrCodeImg = document.createElement("img");
  const url_barcode = encodeURIComponent(
    `http://localhost/TDS/product.php?pid=${productCode}`
  );
  qrCodeImg.src = `https://barcode.orcascan.com/?type=code128&data=${productCode}`;
  qrCodeImg.alt = "Product  Barcode";

  qrCodeImg.style = "max-width: 200px";

  // Create the product code paragraph
  const productCodeParagraph = document.createElement("p");
  productCodeParagraph.textContent = `  Product Code: ${productCode}`;

  // Append the elements to the modal
  modalBody.appendChild(qrCodeImg);
  modalBody.appendChild(productCodeParagraph);
  modalHeader.appendChild(modalTitle);
  modalContent.appendChild(modalHeader);
  modalContent.appendChild(modalBody);
  modalDialog.appendChild(modalContent);
  modal.appendChild(modalDialog);

  // Add the modal to the document
  document.body.appendChild(modal);

  // Show the modal
  const bootstrapModal = new bootstrap.Modal(modal);
  bootstrapModal.show();
}

function generateProductCode(productId) {
  const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  const randomLetters = Array.from(
    { length: 3 },
    () => letters[Math.floor(Math.random() * letters.length)]
  ).join("");
  return `${randomLetters}${productId}${randomLetters}`;
}
