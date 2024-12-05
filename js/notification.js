var DOMAIN = "http://localhost/TDS";
var sound = new Audio("media/notification.mp3"); // Path to your notification sound file
let userInteracted = false; // Flag to check if user has interacted
var playedSound = false;
// Function to initialize the notification popup
function initNotificationPopup() {
  // Create the notification popup HTML structure
  const popupHTML = `
        <div id="notificationPopup" class="popup">
            <div class="popup-header">
                <h2 style="font-size: 14px;">Expiring / Low Stock Product Notification
                    <span id="closePopup" style="cursor: pointer; float: right;">&times;</span>
                </h2>
            </div>
            <div id="popupContent" class="popup-content"></div>
        </div>
    `;

  // Append the popup HTML to the body
  document.body.insertAdjacentHTML("beforeend", popupHTML);

  // Add event listener for the close button
  document.getElementById("closePopup").addEventListener("click", function () {
    document.getElementById("notificationPopup").style.display = "none";
  });
}

// Function to fetch notifications from the server
function fetchNotifications() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", DOMAIN + "/includes/notification.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const notifications = JSON.parse(xhr.responseText);
      displayPopup(notifications);
    }
  };
  xhr.send();
}

// Function to determine the expiration status
function getExpirationStatus(expiryDate) {
  const today = new Date();
  const expirationDate = new Date(expiryDate);

  if (expirationDate < today) {
    return "Expired";
  } else if (expirationDate <= today.setDate(today.getDate() + 7)) {
    return "Expiring";
  } else {
    return "Valid";
  }
}

// Function to sort notifications by expiration status
function sortNotifications(notifications) {
  return notifications.sort((a, b) => {
    const statusA = getExpirationStatus(a.expiry_date);
    const statusB = getExpirationStatus(b.expiry_date);

    // Sort "Expiring" before "Expired"
    if (statusA === "Expiring" && statusB !== "Expiring") return -1;
    if (statusA === "Expired" && statusB === "Expired") return 0;
    if (statusA === "Expired" && statusB === "Expiring") return 1;
    return 0; // Keep the order for "Valid" products
  });
}

// Function to display the popup with notifications
function displayPopup(notifications) {
  const popup = document.getElementById("notificationPopup");
  const content = document.getElementById("popupContent");

  // Clear previous content
  content.innerHTML = "";

  if (notifications.length > 0) {
    // Sort notifications before displaying
    const sortedNotifications = sortNotifications(notifications);

    sortedNotifications.forEach((notification) => {
      const status = getExpirationStatus(notification.expiry_date);
      const item = document.createElement("div");
      item.style.fontSize = "12px"; // Reduce font size for content
      item.innerHTML = `<strong>Product:</strong> ${notification.product_name}<br>
                              <strong>Expiry Date:</strong> ${notification.expiry_date}<br>
                              <strong>Status:</strong> ${status}<br>
                              <strong>Category:</strong> ${notification.catagory_name}<br>
                              <strong>Brand:</strong> ${notification.brand_name}<br>
                              <strong>Available Stock:</strong> ${notification.product_stock}<br><hr>`;
      content.appendChild(item);
    });
    popup.style.display = "block"; // Show the popup

    // Play sound only if the user has interacted
    if (userInteracted && playedSound == false) {
      playedSound = true;
      sound.play().catch((error) => {
        console.error("Error playing sound:", error);
      });
    }
  } else {
    popup.style.display = "none"; // Hide the popup if no notifications
  }
}

// Function to start fetching notifications every 5 seconds
function startFetchingNotifications() {
  fetchNotifications(); // Initial fetch
  setInterval(fetchNotifications, 15000); // Fetch every 5 seconds
}

// Event listener for user interaction
document.addEventListener("click", function () {
  userInteracted = true; // Set the flag to true on first interaction
});

function simulateClick() {
  // Create a new MouseEvent
  const event = new MouseEvent("click", {
    bubbles: true, // The event bubbles up through the DOM
    cancelable: true, // The event can be canceled
    view: window, // The view in which the event is being created
  });

  // Dispatch the event on the document
  document.dispatchEvent(event);
}

// Wait for the DOM to be fully loaded before initializing the popup and starting the fetch
document.addEventListener("DOMContentLoaded", function () {
  setTimeout(() => {
    simulateClick();

    initNotificationPopup();
    startFetchingNotifications();
  }, 5000);
});
