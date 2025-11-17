<?php
 require_once __DIR__ . '/../../database/session_manager.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="../../styles/styles.css" />
  </head>
  <body>
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <button id="toggleSidebar" class="toggle-btn">
      <svg
        id="menuIcon"
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <line x1="3" x2="21" y1="6" y2="6" />
        <line x1="3" x2="21" y1="12" y2="12" />
        <line x1="3" x2="21" y1="18" y2="18" />
      </svg>
      <svg
        id="closeIcon"
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        style="display: none"
      >
        <path d="M18 6 6 18" />
        <path d="m6 6 12 12" />
      </svg>
    </button>

    <main id="mainContent" class="main-content">
      <div class="page-header">
        <h1>Dashboard</h1>
        <p>Welcome back! Here's what's happening today</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card stat-card-primary">
          <div class="stat-header">
            <h3>Pending Requests</h3>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <circle cx="12" cy="12" r="10" />
              <line x1="12" x2="12" y1="8" y2="12" />
              <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
          </div>
          <div class="stat-value">1</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <h3>Approved</h3>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
              <path d="m9 11 3 3L22 4" />
            </svg>
          </div>
          <div class="stat-value">0</div>
        </div>

        <div class="stat-card stat-card-primary">
          <div class="stat-header">
            <h3>Rejected</h3>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <circle cx="12" cy="12" r="10" />
              <path d="m15 9-6 6" />
              <path d="m9 9 6 6" />
            </svg>
          </div>
          <div class="stat-value">0</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <h3>Total Requests</h3>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M3 3v18h18" />
              <path d="m19 9-5 5-4-4-3 3" />
            </svg>
          </div>
          <div class="stat-value">1</div>
        </div>
      </div>

      <div class="content-grid">
        <div class="quick-actions">
          <div class="section-header">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <circle cx="12" cy="12" r="10" />
              <path d="M12 16v-4" />
              <path d="M12 8h.01" />
            </svg>
            <h2>Quick Actions</h2>
          </div>
          <div class="action-buttons">
            <button class="action-btn" id="bookARoomBtn">
              <span>Book a Room</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="m9 18 6-6-6-6" />
              </svg>
            </button>
            <button class="action-btn" id="browseRoomBtn">
              <span>Browse Rooms</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="m9 18 6-6-6-6" />
              </svg>
            </button>
            <button class="action-btn" id="viewCalendarBtn">
              <span>View Calendar</span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="m9 18 6-6-6-6" />
              </svg>
            </button>
          </div>
        </div>

        <div class="requests-section">
          <div class="section-header-row">
            <div class="section-header">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
              <h2>Today's Requests</h2>
            </div>
            <a href="bookings.php" class="view-all">View All</a>
          </div>
          <div class="request-card">
            <div class="request-header">
              <h3>FaCET Meeting de Advance</h3>
              <span class="status-badge status-pending">Pending</span>
            </div>
            <div class="request-details">
              <div class="request-detail">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
                <span>August 27, 2025</span>
              </div>
              <div class="request-detail">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M8 2v4" />
                  <path d="M16 2v4" />
                  <rect width="18" height="18" x="3" y="4" rx="2" />
                  <path d="M3 10h18" />
                </svg>
                <span>09:00 AM - 12:00 PM</span>
              </div>
              <div class="request-detail">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>FaCET Extension Building</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="rooms-section">
        <div class="section-header-row">
          <div class="section-header">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect width="7" height="9" x="3" y="3" rx="1" />
              <rect width="7" height="5" x="14" y="3" rx="1" />
              <rect width="7" height="9" x="14" y="12" rx="1" />
              <rect width="7" height="5" x="3" y="16" rx="1" />
            </svg>
            <h2>Available Rooms</h2>
          </div>
          <a href="rooms.php" class="view-all">View All</a>
        </div>

        <div class="rooms-grid" id="availableRoomsGrid">
          </div>
      </div>
    </main>

    <script src="../../scripts/script.js"></script>
    <script>
    // Get the button element by its ID
    const bookButton = document.getElementById('bookARoomBtn');
    const browseRoom = document.getElementById('browseRoomBtn');
    const viewCalendar = document.getElementById('viewCalendarBtn');

    // Add an event listener to listen for a click
    bookButton.addEventListener('click', function() {
        // Navigate to the 
        window.location.href = 'rooms.php';
    });

    browseRoom.addEventListener('click', function() {
        // Navigate to the 
        window.location.href = 'rooms.php';
    });

    viewCalendar.addEventListener('click', function() {
        // Navigate to the 
        window.location.href = 'calendar.php';
    });




</script>
  </body>
</html>
