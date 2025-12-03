const API_ENDPOINT = "/facet-rms/public/booking"; // ADDED
let bookings = []; // ADDED

// New function to load bookings from the API
async function loadBookings() { // MODIFIED
  try {
    const response = await fetch(`${API_ENDPOINT}/list_api`);
    const result = await response.json();
    
    if (result.success) {
      // Map API response fields to the structure expected by the rendering functions
      bookings = result.data.map(b => ({
        id: b.id,
        meetingTitle: b.meeting_title,
        room: b.room_name,
        building: b.building,
        floor: b.floor,
        date: b.booking_date, 
        requestDate: b.request_date.split(' ')[0], 
        startTime: b.start_time,
        endTime: b.end_time,
        attendees: b.attendees,
        recurring: b.recurring,
        description: b.description,
        requestor: b.requestor_name,
        status: b.status,
        imageUrl: b.image_url || `/facet-rms/public/assets/images/computer-laboratory-laboratory-room-with-orange-theme.jpg`,
      }));
      
      updateStatusCounts();
      renderRecentBookings();
      renderBookingHistory();
    } else {
      console.error("Error loading bookings:", result.message);
      // Fallback display
    }
  } catch (error) {
    console.error("Fetch error:", error);
    // Fallback display
  }
}

// Format date
function formatDate(dateString) {
  const date = new Date(dateString)
  const options = { year: "numeric", month: "long", day: "numeric" }
  return date.toLocaleDateString("en-US", options)
}

// Format time to 12-hour format
function formatTime(time) {
  const [hours, minutes] = time.split(":")
  const hour = Number.parseInt(hours)
  const ampm = hour >= 12 ? "PM" : "AM"
  const displayHour = hour % 12 || 12
  return `${displayHour}:${minutes} ${ampm}`
}

// Update status counts
function updateStatusCounts() {
  const pending = bookings.filter((b) => b.status === "Pending").length
  const approved = bookings.filter((b) => b.status === "Approved").length
  const rejected = bookings.filter((b) => b.status === "Rejected").length

  document.getElementById("pendingCount").textContent = pending
  document.getElementById("approvedCount").textContent = approved
  document.getElementById("rejectedCount").textContent = rejected
}

// Render recent bookings
function renderRecentBookings() {
  const recentContainer = document.getElementById("recentBookingsContainer")
  const pendingBookings = bookings.filter((b) => b.status === "Pending")

  if (pendingBookings.length === 0) {
    recentContainer.innerHTML = `
      <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="18" height="18" x="3" y="3" rx="2"/>
          <path d="M3 9h18"/>
        </svg>
        <p>No pending booking requests</p>
      </div>
    `
    return
  }

  const recent = pendingBookings[0]
  recentContainer.innerHTML = `
    <div class="recent-booking-card">
      <div class="recent-booking-info">
        <div class="recent-booking-title">${recent.meetingTitle}</div>
        <div class="recent-booking-details">
          <div class="recent-booking-detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>${formatDate(recent.requestDate)}</span>
          </div>
          <div class="recent-booking-detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8 2v4"/>
              <path d="M16 2v4"/>
              <rect width="18" height="18" x="3" y="4" rx="2"/>
              <path d="M3 10h18"/>
            </svg>
            <span>${formatDate(recent.date)} • ${formatTime(recent.startTime)} - ${formatTime(recent.endTime)}</span>
          </div>
          <div class="recent-booking-detail">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            <span>${recent.building}</span>
          </div>
        </div>
      </div>
      <div class="recent-booking-actions">
        <button class="btn-approve" onclick="approveBooking(${recent.id})">Approve</button>
        <button class="btn-reject" onclick="rejectBooking(${recent.id})">Reject</button>
        <button class="view-details-btn" onclick="viewBookingDetails(${recent.id})">
    <span>View Details</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6"/>
          </svg>
        </button>
      </div>
    </div>
  `
}

// Render booking history
function renderBookingHistory() {
  const historyContainer = document.getElementById("bookingHistoryContainer")

  if (bookings.length === 0) {
    historyContainer.innerHTML = `
      <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect width="18" height="18" x="3" y="3" rx="2"/>
          <path d="M3 9h18"/>
        </svg>
        <p>No booking history</p>
      </div>
    `
    return
  }

  historyContainer.innerHTML = `
    <div class="booking-history-list">
      ${bookings
        .map(
          (booking) => `
        <div class="booking-history-card" onclick="viewBookingDetails(${booking.id})">
          <div class="booking-history-info">
            <div class="booking-history-title">${booking.meetingTitle}</div>
            <div class="booking-history-details">
              <div class="booking-history-detail">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>${formatDate(booking.requestDate)}</span>
              </div>
              <div class="booking-history-detail">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M8 2v4"/>
                  <path d="M16 2v4"/>
                  <rect width="18" height="18" x="3" y="4" rx="2"/>
                  <path d="M3 10h18"/>
                </svg>
                <span>${formatDate(booking.date)} • ${formatTime(booking.startTime)} - ${formatTime(booking.endTime)}</span>
              </div>
              <div class="booking-history-detail">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                <span>${booking.building}</span>
              </div>
            </div>
          </div>
          <div class="booking-history-actions">
            <span class="booking-history-status ${booking.status.toLowerCase()}">${booking.status}</span>
            ${booking.status === 'Pending' ? `
              <button class="btn-approve" onclick="approveBooking(${booking.id})">Approve</button>
              <button class="btn-reject" onclick="rejectBooking(${booking.id})">Reject</button>
            ` : ''}
            <button class="view-details-btn-small" onclick="event.stopPropagation(); viewBookingDetails(${booking.id})">
              <span>View Details</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"/>
              </svg>
            </button>
          </div>
        </div>
      `,
        )
        .join("")}
    </div>
  `
}

// View booking details
function viewBookingDetails(bookingId) {
  window.location.href = `/facet-rms/public/booking/details?id=${bookingId}`; // Use URL parameter
}

// Global function for onclick
window.viewBookingDetails = viewBookingDetails

// --- API Action Functions ---
async function updateBookingStatus(bookingId, action) {
  try {
    const response = await fetch(`${API_ENDPOINT}/${action}_api`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: bookingId })
    });
    const result = await response.json();

    if (result.success) {
      showNotification(`Booking ${action}ed successfully!`, 'success');
      loadBookings(); // Reload bookings to update UI
    } else {
      showNotification(`Error ${action}ing booking: ${result.message}`, 'error');
    }
  } catch (error) {
    console.error(`Error ${action}ing booking:`, error);
    showNotification(`Failed to ${action} booking. Please check server connection.`, 'error');
  }
}

function approveBooking(bookingId) {
  updateBookingStatus(bookingId, 'approve');
}

function rejectBooking(bookingId) {
  updateBookingStatus(bookingId, 'reject');
}

// --- Helper for Notifications ---
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
    color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    animation: slideIn 0.3s ease;
  `;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Global functions for onclick
window.viewBookingDetails = viewBookingDetails;
window.approveBooking = approveBooking; // Make global for onclick
window.rejectBooking = rejectBooking;   // Make global for onclick

// Initialize page
if (document.getElementById("recentBookingsContainer")) {
  loadBookings(); // Call the new async loader
}
