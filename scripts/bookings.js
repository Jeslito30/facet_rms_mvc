// Sample booking data
const sampleBookings = [
  {
    id: 1,
    meetingTitle: "FaCET Miting de Avance",
    room: "ComLab 1",
    building: "FaCET Extension Building",
    floor: "1st Floor",
    date: "2025-09-15",
    requestDate: "2025-08-27",
    startTime: "09:00",
    endTime: "12:00",
    attendees: 38,
    recurring: "MWF",
    description: "No additional details of the booking...",
    requestor: "Geverola, Jeslito Galagar",
    status: "Pending",
    imageUrl: "../../assets/images/computer-laboratory-room-with-orange-theme.jpg",
  },
  {
    id: 2,
    meetingTitle: "ITMSD 1 Laboratory",
    room: "ComLab 2",
    building: "FaCET Extension Building",
    floor: "1st Floor",
    date: "2025-09-15",
    requestDate: "2025-08-18",
    startTime: "09:00",
    endTime: "12:00",
    attendees: 40,
    recurring: "TTh",
    description: "Laboratory session for ITMSD 1",
    requestor: "Smith, John",
    status: "Approved",
    imageUrl: "../../assets/images/modern-computer-lab.jpg",
  },
  {
    id: 3,
    meetingTitle: "ITP 132 Laboratory",
    room: "ComLab 3",
    building: "FaCET Extension Building",
    floor: "1st Floor",
    date: "2025-09-15",
    requestDate: "2025-08-18",
    startTime: "09:00",
    endTime: "12:00",
    attendees: 35,
    recurring: "MWF",
    description: "Programming laboratory session",
    requestor: "Doe, Jane",
    status: "Approved",
    imageUrl: "../../assets/images/programming-lab-with-computers.jpg",
  },
]

// Initialize bookings from localStorage or use sample data
const bookings = JSON.parse(localStorage.getItem("bookings")) || sampleBookings

// Save bookings to localStorage
function saveBookings() {
  localStorage.setItem("bookings", JSON.stringify(bookings))
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
    <div class="recent-booking-card" onclick="viewBookingDetails(${recent.id})">
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
      <button class="view-details-btn">
        <span>View Details</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"/>
        </svg>
      </button>
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
  console.log("Setting currentBookingId to:", bookingId)
  localStorage.setItem("currentBookingId", bookingId)
  console.log("Current bookings in localStorage:", localStorage.getItem("bookings"))
  window.location.href = "booking-details.php"
}

// Global function for onclick
window.viewBookingDetails = viewBookingDetails

// Initialize page
if (document.getElementById("recentBookingsContainer")) {
  // Save bookings to localStorage to ensure they're available
  saveBookings()
  updateStatusCounts()
  renderRecentBookings()
  renderBookingHistory()
}
