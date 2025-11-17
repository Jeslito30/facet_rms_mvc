<?php
 require_once __DIR__ . '/../../database/session_manager.php';
     // The path is relative from 'src/pages/' up to root, then down to 'database/'
      require_once __DIR__ . '/../../database/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="../../styles/styles.css" />
    <link rel="stylesheet" href="../../styles/bookings.css" />
  </head>
  <body>
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Toggle Button -->
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

    <!-- Main Content -->
    <main id="mainContent" class="main-content">
      <!-- Header -->
      <div class="page-header">
        <h1>Booking Requests</h1>
        <p>Schedule your space for meetings, study sessions, or events</p>
      </div>

      <!-- Status Cards -->
      <div class="booking-status-grid">
        <div class="booking-status-card pending">
          <div class="status-icon">
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
          <div class="status-content">
            <span class="status-label">Pendings</span>
            <span class="status-count" id="pendingCount">0</span>
          </div>
        </div>

        <div class="booking-status-card approved">
          <div class="status-icon">
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
          <div class="status-content">
            <span class="status-label">Approved</span>
            <span class="status-count" id="approvedCount">0</span>
          </div>
        </div>

        <div class="booking-status-card rejected">
          <div class="status-icon">
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
          <div class="status-content">
            <span class="status-label">Rejected</span>
            <span class="status-count" id="rejectedCount">0</span>
          </div>
        </div>
      </div>

      <!-- Recent Booking Request -->
      <div class="recent-bookings-section">
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
          <h2>Recent Booking Request</h2>
        </div>
        <div id="recentBookingsContainer"></div>
      </div>

      <!-- Booking History -->
      <div class="booking-history-section">
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
          <h2>Booking History</h2>
        </div>
        <div id="bookingHistoryContainer"></div>
      </div>
    </main>

    <script src="../../scripts/script.js"></script>
    <script src="../../scripts/bookings.js"></script>
  </body>
</html>
