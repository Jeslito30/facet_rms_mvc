// Calendar state
const currentDate = new Date(2025, 7, 27) // August 27, 2025
let selectedDate = new Date(2025, 7, 27)
let allBookings = [];

const API_ENDPOINT = '/facet-rms/public/booking/list_api';

async function loadBookings() {
  try {
    const response = await fetch(API_ENDPOINT);
    const result = await response.json();
    if (result.success) {
      allBookings = result.data;
      renderCalendar();
      renderBookings();
    } else {
      console.error('Failed to load bookings:', result.message);
    }
  } catch (error) {
    console.error('Error fetching bookings:', error);
  }
}


// Get bookings for a specific date
function getBookingsForDate(date) {
  const dateString = formatDateToString(date)
  return allBookings.filter((booking) => booking.booking_date === dateString)
}

// Format date to YYYY-MM-DD
function formatDateToString(date) {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, "0")
  const day = String(date.getDate()).padStart(2, "0")
  return `${year}-${month}-${day}`
}

// Format date for display
function formatDateForDisplay(date) {
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ]
  const day = date.getDate()
  const suffix = getDaySuffix(day)
  return `${months[date.getMonth()]} ${day}${suffix}, ${date.getFullYear()}`
}

// Get day suffix (st, nd, rd, th)
function getDaySuffix(day) {
  if (day >= 11 && day <= 13) return "th"
  switch (day % 10) {
    case 1:
      return "st"
    case 2:
      return "nd"
    case 3:
      return "rd"
    default:
      return "th"
  }
}

// Render calendar
function renderCalendar() {
  const year = currentDate.getFullYear()
  const month = currentDate.getMonth()

  // Update month display
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ]
  document.getElementById("currentMonth").textContent = `${months[month]} ${year}`

  // Get first day of month and number of days
  const firstDay = new Date(year, month, 1).getDay()
  const daysInMonth = new Date(year, month + 1, 0).getDate()

  // Clear calendar
  const calendarDays = document.getElementById("calendarDays")
  calendarDays.innerHTML = ""

  // Add empty cells for days before month starts
  for (let i = 0; i < firstDay; i++) {
    const emptyDay = document.createElement("div")
    emptyDay.className = "calendar-day empty"
    calendarDays.appendChild(emptyDay)
  }

  // Add days of month
  for (let day = 1; day <= daysInMonth; day++) {
    const dayElement = document.createElement("div")
    dayElement.className = "calendar-day"
    dayElement.textContent = String(day).padStart(2, "0")

    const date = new Date(year, month, day)
    const bookings = getBookingsForDate(date)

    if (bookings.length > 0) {
      dayElement.classList.add("has-bookings")
    }

    if (date.toDateString() === selectedDate.toDateString()) {
      dayElement.classList.add("selected")
    }

    dayElement.addEventListener("click", () => {
      selectedDate = date
      renderCalendar()
      renderBookings()
    })

    calendarDays.appendChild(dayElement)
  }
}

// Render bookings for selected date
function renderBookings() {
  const bookingsContainer = document.getElementById("bookingsContainer")
  const selectedDateElement = document.getElementById("selectedDate")

  selectedDateElement.textContent = formatDateForDisplay(selectedDate)

  const bookings = getBookingsForDate(selectedDate)

  if (bookings.length === 0) {
    bookingsContainer.innerHTML = `
      <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.29 7 12 12 20.71 7"/>
          <line x1="12" x2="12" y1="22" y2="12"/>
        </svg>
        <p>No bookings for this date</p>
      </div>
    `
  } else {
    bookingsContainer.innerHTML = bookings
      .map(
        (booking) => `
      <div class="booking-item">
        <h4>${booking.room_name}</h4>
        <div class="booking-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          <span>${booking.start_time} - ${booking.end_time}</span>
        </div>
        <div class="booking-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          <span>${booking.building}, ${booking.floor}</span>
        </div>
        <div class="booking-detail">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
          </svg>
          <span>${booking.meeting_title}</span>
        </div>
      </div>
    `,
      )
      .join("")
  }
}

// Month navigation
document.getElementById("prevMonth").addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1)
  renderCalendar()
})

document.getElementById("nextMonth").addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1)
  renderCalendar()
})

// Book date button
document.getElementById("bookDateBtn").addEventListener("click", () => {
  alert(`Booking form for ${formatDateForDisplay(selectedDate)} would open here`)
})

// Initialize
loadBookings();
