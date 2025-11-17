// Variables will be set when DOM loads
let currentBookingId
let bookings
let currentBooking

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

// Account dropdown is handled by script.js

// Populate booking details when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  try {
    console.log("Booking details script loaded successfully!")
    
    // Get current booking ID from localStorage
    currentBookingId = Number.parseInt(localStorage.getItem("currentBookingId"))
    
    // Get bookings from localStorage
    bookings = JSON.parse(localStorage.getItem("bookings")) || []
    
    // Find current booking
    currentBooking = bookings.find((b) => b.id === currentBookingId)
    
    console.log("Current Booking ID:", currentBookingId)
    console.log("Bookings:", bookings)
    console.log("Current Booking:", currentBooking)
    
    if (currentBooking) {
      // Left panel - form inputs
      const buildingInput = document.getElementById("buildingInput")
      const roomInput = document.getElementById("roomInput")
      const dateInput = document.getElementById("dateInput")
      const meetingTitleInput = document.getElementById("meetingTitleInput")
      const startTimeInput = document.getElementById("startTimeInput")
      const endTimeInput = document.getElementById("endTimeInput")
      const attendeesInput = document.getElementById("attendeesInput")
      const recurringInput = document.getElementById("recurringInput")
      const descriptionInput = document.getElementById("descriptionInput")

      if (buildingInput) buildingInput.value = `${currentBooking.building}, ${currentBooking.floor}`
      if (roomInput) roomInput.value = currentBooking.room
      if (dateInput) dateInput.value = formatDate(currentBooking.date)
      if (meetingTitleInput) meetingTitleInput.value = currentBooking.meetingTitle
      if (startTimeInput) startTimeInput.value = formatTime(currentBooking.startTime)
      if (endTimeInput) endTimeInput.value = formatTime(currentBooking.endTime)
      if (attendeesInput) attendeesInput.value = `${currentBooking.attendees} people`
      if (recurringInput) recurringInput.value = currentBooking.recurring
      if (descriptionInput) descriptionInput.value = currentBooking.description

      // Right panel - room details
      const roomImage = document.getElementById("roomImage")
      const roomName = document.getElementById("roomName")
      const roomLocation = document.getElementById("roomLocation")
      const roomMeetingTitle = document.getElementById("roomMeetingTitle")
      const roomDate = document.getElementById("roomDate")
      const roomTime = document.getElementById("roomTime")
      const roomAttendees = document.getElementById("roomAttendees")
      const roomRequestor = document.getElementById("roomRequestor")

      if (roomImage) {
        roomImage.src = currentBooking.imageUrl
        roomImage.alt = currentBooking.room
      }
      if (roomName) roomName.textContent = currentBooking.room
      if (roomLocation) roomLocation.textContent = `${currentBooking.building}, ${currentBooking.floor}`
      if (roomMeetingTitle) roomMeetingTitle.textContent = currentBooking.meetingTitle
      if (roomDate) roomDate.textContent = formatDate(currentBooking.date)
      if (roomTime) roomTime.textContent = `${currentBooking.recurring} ${formatTime(currentBooking.startTime)} - ${formatTime(currentBooking.endTime)}`
      if (roomAttendees) roomAttendees.textContent = `${currentBooking.attendees} people`
      if (roomRequestor) roomRequestor.textContent = currentBooking.requestor
      
      console.log("Booking details populated successfully!")
    } else {
      console.log("No booking found, redirecting back...")
      // Redirect back if no booking found
      window.location.href = "bookings.php"
    }
  } catch (error) {
    console.error("Error in booking details script:", error)
  }
})

// Go back to bookings page
function goBack() {
  window.location.href = "bookings.php"
}

// Approve booking
function approveBooking() {
  // Get fresh data from localStorage
  const currentId = Number.parseInt(localStorage.getItem("currentBookingId"))
  const currentBookings = JSON.parse(localStorage.getItem("bookings")) || []
  const currentBookingData = currentBookings.find((b) => b.id === currentId)
  
  if (currentBookingData) {
    const index = currentBookings.findIndex((b) => b.id === currentId)
    if (index !== -1) {
      currentBookings[index].status = "Approved"
      localStorage.setItem("bookings", JSON.stringify(currentBookings))
      alert("Booking approved successfully!")
      window.location.href = "bookings.php"
    }
  } else {
    alert("Booking not found!")
  }
}

// Reject booking
function rejectBooking() {
  // Get fresh data from localStorage
  const currentId = Number.parseInt(localStorage.getItem("currentBookingId"))
  const currentBookings = JSON.parse(localStorage.getItem("bookings")) || []
  const currentBookingData = currentBookings.find((b) => b.id === currentId)
  
  if (currentBookingData) {
    const index = currentBookings.findIndex((b) => b.id === currentId)
    if (index !== -1) {
      currentBookings[index].status = "Rejected"
      localStorage.setItem("bookings", JSON.stringify(currentBookings))
      alert("Booking rejected.")
      window.location.href = "bookings.php"
    }
  } else {
    alert("Booking not found!")
  }
}

// Global functions for onclick
window.goBack = goBack
window.approveBooking = approveBooking
window.rejectBooking = rejectBooking
