<?php
// require_once __DIR__ . '/../../database/session_manager.php';
//      // The path is relative from 'src/pages/' up to root, then down to 'database/'
//       require_once __DIR__ . '/../../database/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/styles.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/calendar.css" />
  </head>
  <body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>

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
        <h1>Calendar View</h1>
        <p>View all room bookings and schedules</p>
      </div>

      <div class="calendar-container">
        <div class="calendar-card">
          <div class="calendar-header">
            <button id="prevMonth" class="month-nav-btn">
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
                <path d="m15 18-6-6 6-6" />
              </svg>
            </button>
            <h2 id="currentMonth">August 2025</h2>
            <button id="nextMonth" class="month-nav-btn">
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

          <div class="calendar-grid">
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
            <div id="calendarDays"></div>
          </div>
        </div>

        <div class="details-panel">
          <div class="details-header">
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
              <path d="M8 2v4" />
              <path d="M16 2v4" />
              <rect width="18" height="18" x="3" y="4" rx="2" />
              <path d="M3 10h18" />
            </svg>
            <h3 id="selectedDate">August 27th, 2025</h3>
          </div>

          <div id="bookingsContainer" class="bookings-container">
            Bookings will be dynamically inserted here
          </div>

          <button id="bookDateBtn" class="book-date-btn">
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
              <path d="M5 12h14" />
              <path d="M12 5v14" />
            </svg>
            Book for this date
          </button>
        </div>
      </div>
    </main>

    <script src="<?php echo BASE_URL; ?>/js/script.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/calendar.js"></script>
  </body>
</html>
