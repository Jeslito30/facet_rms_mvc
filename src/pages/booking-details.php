<?php
     // The path is relative from 'src/pages/' up to root, then down to 'database/'
      require_once __DIR__ . '/../../database/auth_check.php';
      require_once __DIR__ . '/../../database/session_manager.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="../../styles/styles.css">
    <link rel="stylesheet" href="../../styles/bookings.css">
</head>
<body>
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Toggle Button -->
    <button id="toggleSidebar" class="toggle-btn">
        <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" x2="21" y1="6" y2="6"/>
            <line x1="3" x2="21" y1="12" y2="12"/>
            <line x1="3" x2="21" y1="18" y2="18"/>
        </svg>
        <svg id="closeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
        </svg>
    </button>

    <!-- Main Content -->
    <main id="mainContent" class="main-content">
        <!-- Header -->
        <div class="page-header">
            <h1>Booking Requests</h1>
            <p>Schedule your space for meetings, study sessions, or events</p>
        </div>

        <!-- Booking Details Grid -->
        <div class="booking-details-grid">
            <!-- Left Panel - Booking Form -->
            <div class="booking-form-panel">
                <div class="form-group-row">
                    <div class="form-group">
                        <label>Select Building</label>
                        <input type="text" id="buildingInput" readonly>
                    </div>
                    <div class="form-group">
                        <label>Select Room</label>
                        <input type="text" id="roomInput" readonly>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" id="dateInput" readonly>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group full-width">
                        <label>Meeting Title</label>
                        <input type="text" id="meetingTitleInput" readonly>
                    </div>
                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="text" id="startTimeInput" readonly>
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="text" id="endTimeInput" readonly>
                    </div>
                </div>

                <div class="form-group-row">
                    <div class="form-group">
                        <label>Expected Attendees</label>
                        <input type="text" id="attendeesInput" readonly>
                    </div>
                    <div class="form-group">
                        <label>Make this a recurring booking</label>
                        <input type="text" id="recurringInput" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description (Optional)</label>
                    <textarea id="descriptionInput" rows="4" readonly></textarea>
                </div>

                <div class="form-actions">
                    <button class="btn-back" onclick="goBack()">Back</button>
                    <button class="btn-reject" onclick="rejectBooking()">Reject</button>
                </div>
            </div>

            <!-- Right Panel - Room Details -->
            <div class="room-details-panel">
                <div class="room-image">
                    <img id="roomImage" src="../assets/images/placeholder.svg" alt="Room Image">
                </div>
                <div class="room-details-content">
                    <h2 id="roomName"></h2>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span id="roomLocation"></span>
                    </div>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 4h3a2 2 0 0 1 2 2v14"/>
                            <path d="M2 20h3"/>
                            <path d="M13 20h9"/>
                            <path d="M10 12v.01"/>
                            <path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/>
                        </svg>
                        <span id="roomMeetingTitle"></span>
                    </div>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 2v4"/>
                            <path d="M16 2v4"/>
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <path d="M3 10h18"/>
                        </svg>
                        <span id="roomDate"></span>
                    </div>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span id="roomTime"></span>
                    </div>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span id="roomAttendees"></span>
                    </div>
                    <div class="room-detail-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="roomRequestor"></span>
                    </div>
                </div>
                <button class="btn-approve" onclick="approveBooking()">Approve</button>
            </div>
        </div>
    </main>

    <script src="../../scripts/script.js"></script>
    <script src="../../scripts/booking-details.js"></script>
</body>
</html>
