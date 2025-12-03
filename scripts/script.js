// Sidebar toggle functionality
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const toggleBtn = document.getElementById("toggleSidebar");
const menuIcon = document.getElementById("menuIcon");
const closeIcon = document.getElementById("closeIcon");

let sidebarOpen = true;

toggleBtn.addEventListener("click", () => {
  sidebarOpen = !sidebarOpen;

  if (sidebarOpen) {
    sidebar.classList.remove("hidden");
    mainContent.classList.remove("expanded");
    toggleBtn.classList.remove("sidebar-hidden");
    menuIcon.style.display = "none";
    closeIcon.style.display = "block";
  } else {
    sidebar.classList.add("hidden");
    mainContent.classList.add("expanded");
    toggleBtn.classList.add("sidebar-hidden");
    menuIcon.style.display = "block";
    closeIcon.style.display = "none";
  }
});

// Navigation active state
const navItems = document.querySelectorAll(".nav-item");
const currentPage = window.location.pathname.split("/").pop() || "index.php";

navItems.forEach((item) => {
  const href = item.getAttribute("href");
  if (href === currentPage || (currentPage === "" && href === "index.php")) {
    item.classList.add("active");
  } else {
    item.classList.remove("active");
  }
});

// Action buttons
const actionButtons = document.querySelectorAll(".action-btn");
actionButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    console.log("[v0] Action clicked:", btn.querySelector("span").textContent);
    alert(`Action: ${btn.querySelector("span").textContent}`);
  });
});

// Book buttons
// NOTE: These listeners now need to be attached after the rooms are loaded dynamically.
// I'll create a function to handle this.

function attachBookButtonListeners() {
  const bookButtons = document.querySelectorAll(".book-btn");
  bookButtons.forEach((btn) => {
    // Prevent multiple listeners if called multiple times
    btn.removeEventListener("click", handleBookButtonClick);
    btn.addEventListener("click", handleBookButtonClick);
  });
}

// [NEW FUNCTION]
function handleBookButtonClick() {
  const roomCard = this.closest(".room-card");
  const roomName = roomCard.querySelector("h3").textContent;
  console.log(`[v1] Navigating to rooms page from: ${roomName}`);
  // Redirect to the main rooms page to make a booking
  window.location.href = 'rooms.php'; 
}


/**
 * Fetches rooms from the API, filters for 'Available' status, and displays them.
 */
async function fetchAndDisplayAvailableRooms() {
  const roomGrid = document.getElementById("availableRoomsGrid");
  if (!roomGrid) {
    return; // Exit if the grid isn't on this page
  }
  roomGrid.innerHTML = 'Loading rooms...'; // Loading indicator

  try {
    const response = await fetch('../../database/rooms_api.php?action=get_all');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const result = await response.json();

    if (result.success && result.data) {
      const availableRooms = result.data.filter(room => room.status === 'Available');
      
      if (availableRooms.length === 0) {
        roomGrid.innerHTML = '<p>No rooms are currently available.</p>';
        return;
      }

      roomGrid.innerHTML = availableRooms.map(room => `
        <div class="room-card">
          <div class="room-header">
            <h3>${room.name}</h3>
            <span class="availability-badge">${room.status}</span> 
          </div>
          <div class="room-time">${room.startTime} - ${room.endTime}</div>
          <div class="room-details">
            <div class="room-detail">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
              <span>${room.capacity} people</span>
            </div>
            <div class="room-detail">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
              <span>${room.building}, ${room.floor}</span>
            </div>
          </div>
          <button class="book-btn" data-room-id="${room.id}">Book Now</button>
        </div>
      `).join('');
      
      // Re-attach listeners after content is rendered
      attachBookButtonListeners();
      
    } else {
      roomGrid.innerHTML = `<p>Error fetching rooms: ${result.message || 'Unknown error'}</p>`;
      console.error("API Error:", result.message);
    }
  } catch (error) {
    roomGrid.innerHTML = '<p>Failed to connect to the server or database.</p>';
    console.error("Fetch Error:", error);
  }
}

// Call the function to load rooms when the page loads
document.addEventListener('DOMContentLoaded', fetchAndDisplayAvailableRooms);

// Responsive sidebar for mobile
function handleResize() {
  if (window.innerWidth <= 768) {
    sidebar.classList.add("hidden");
    mainContent.classList.add("expanded");
    toggleBtn.classList.add("sidebar-hidden");
    menuIcon.style.display = "block";
    closeIcon.style.display = "none";
    sidebarOpen = false;
  } else {
    sidebar.classList.remove("hidden");
    mainContent.classList.remove("expanded");
    toggleBtn.classList.remove("sidebar-hidden");
    menuIcon.style.display = "none";
    closeIcon.style.display = "block";
    sidebarOpen = true;
  }
}

window.addEventListener("resize", handleResize);
handleResize();

// Account dropdown toggle
const userProfileBtn = document.getElementById("userProfileBtn");
const accountDropdown = document.getElementById("accountDropdown");

if (userProfileBtn && accountDropdown) {
  userProfileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    userProfileBtn.classList.toggle("open");
    accountDropdown.classList.toggle("open");
  });

  // Close when clicking outside
  document.addEventListener("click", (e) => {
    const clickedInside = userProfileBtn.contains(e.target) || accountDropdown.contains(e.target);
    if (!clickedInside) {
      userProfileBtn.classList.remove("open");
      accountDropdown.classList.remove("open");
    }
  });

  // Close on Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      userProfileBtn.classList.remove("open");
      accountDropdown.classList.remove("open");
    }
  });
}