const API_ENDPOINT = "/facet-rms/public/booking"; // ADDED

let currentBooking = null; // ADDED

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

// Go back to bookings page
function goBack() {
  window.location.href = "/facet-rms/public/booking/index"
}
async function loadBookingDetails() { // MODIFIED
  const urlParams = new URLSearchParams(window.location.search);
  const bookingId = urlParams.get('id');
  
  if (!bookingId) {
    alert('No booking ID provided. Redirecting.');
    window.location.href = "/facet-rms/public/booking/index";
    return;
  }
  
  try {
    const response = await fetch(`${API_ENDPOINT}/get_api/${bookingId}`);
    const result = await response.json();
    
    if (result.success) {
      currentBooking = result.data;
      populateBookingDetails(currentBooking);
    } else {
      alert('Error loading booking: ' + result.message);
      window.location.href = "/facet-rms/public/booking/index";
    }
  } catch (error) {
    console.error('Error fetching booking details:', error);
    alert('Failed to load booking details.');
    window.location.href = "/facet-rms/public/booking/index";
  }
}

// Renamed/modified original populate function to match API data structure
function populateBookingDetails(booking) {
    const room = booking.room_name;
    const building = booking.building;
    const floor = booking.floor;
    const date = booking.booking_date;
    const startTime = booking.start_time;
    const endTime = booking.end_time;
    const meetingTitle = booking.meeting_title;
    const attendees = booking.attendees;
    const recurring = booking.recurring;
    const description = booking.description;
    const requestor = booking.requestor_name;
    const imageUrl = booking.image_url || "/facet-rms/public/assets/images/computer-laboratory-laboratory-room-with-orange-theme.jpg"; // Use default placeholder

    // Left panel - form inputs
    const buildingInput = document.getElementById("buildingInput");
    const roomInput = document.getElementById("roomInput");
    const dateInput = document.getElementById("dateInput");
    const meetingTitleInput = document.getElementById("meetingTitleInput");
    const startTimeInput = document.getElementById("startTimeInput");
    const endTimeInput = document.getElementById("endTimeInput");
    const attendeesInput = document.getElementById("attendeesInput");
    const recurringInput = document.getElementById("recurringInput");
    const descriptionInput = document.getElementById("descriptionInput");

    if (buildingInput) buildingInput.value = `${building}, ${floor}`;
    if (roomInput) roomInput.value = room;
    if (dateInput) dateInput.value = formatDate(date);
    if (meetingTitleInput) meetingTitleInput.value = meetingTitle;
    if (startTimeInput) startTimeInput.value = formatTime(startTime);
    if (endTimeInput) endTimeInput.value = formatTime(endTime);
    if (attendeesInput) attendeesInput.value = `${attendees} people`;
    if (recurringInput) recurringInput.value = recurring;
    if (descriptionInput) descriptionInput.value = description;

    // Right panel - room details
    const roomImage = document.getElementById("roomImage");
    const roomName = document.getElementById("roomName");
    const roomLocation = document.getElementById("roomLocation");
    const roomMeetingTitle = document.getElementById("roomMeetingTitle");
    const roomDate = document.getElementById("roomDate");
    const roomTime = document.getElementById("roomTime");
    const roomAttendees = document.getElementById("roomAttendees");
    const roomRequestor = document.getElementById("roomRequestor");
    const approveBtn = document.querySelector('.btn-approve');
    const rejectBtn = document.querySelector('.btn-reject');

    if (roomImage) {
        roomImage.src = imageUrl;
        roomImage.alt = room;
    }
    if (roomName) roomName.textContent = room;
    if (roomLocation) roomLocation.textContent = `${building}, ${floor}`;
    if (roomMeetingTitle) roomMeetingTitle.textContent = meetingTitle;
    if (roomDate) roomDate.textContent = formatDate(date);
    if (roomTime) roomTime.textContent = `${formatTime(startTime)} - ${formatTime(endTime)}`;
    if (roomAttendees) roomAttendees.textContent = `${attendees} people`;
    if (roomRequestor) roomRequestor.textContent = requestor;

    if (booking.status !== 'Pending') {
        if (approveBtn) {
            approveBtn.disabled = true;
            approveBtn.textContent = booking.status === 'Approved' ? 'Already Approved' : 'Not Pending';
        }
        if (rejectBtn) {
            rejectBtn.disabled = true;
            rejectBtn.textContent = booking.status === 'Rejected' ? 'Already Rejected' : 'Not Pending';
        }
    }
}

// Approve/Reject booking - Calls the API
async function updateBookingStatus(status) { // NEW/MODIFIED
  if (!currentBooking) {
    alert("Booking data is missing!");
    return;
  }
  
  const action = status.toLowerCase(); // 'approve' or 'reject'
  const confirmText = action === 'approve' ? 
    `Are you sure you want to APPROVE this booking? The room will be marked as 'Occupied'.` :
    `Are you sure you want to REJECT this booking?`;
    
  if (!confirm(confirmText)) { return; }
  
  try {
    const response = await fetch(`${API_ENDPOINT}/${action}_api`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: currentBooking.id })
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert(`Booking ${result.data.status} successfully!`);
      window.location.href = "/facet-rms/public/booking/index";
    } else {
      alert(`Operation Failed: ${result.message}`);
    }
  } catch (error) {
    console.error(`Error ${action}ing booking:`, error);
    alert(`Failed to perform action. Check server connection.`);
  }
}

function approveBooking() { updateBookingStatus('Approve'); } // MODIFIED
function rejectBooking() { updateBookingStatus('Reject'); } // MODIFIED

// Initialize when page loads (Modified)
document.addEventListener('DOMContentLoaded', () => {
  loadBookingDetails();
});

// Global functions for onclick
window.goBack = goBack;
window.approveBooking = approveBooking;
window.rejectBooking = rejectBooking;
