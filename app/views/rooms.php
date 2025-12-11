<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/styles.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/rooms.css" />
  </head>
  <body>
    <?php require_once 'sidebar.php'; ?>

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
        <h1>Room Directory</h1>
        <p>Find and book the perfect place for your needs</p>
      </div>

      <!-- Toolbar -->
      <div class="toolbar">
        <button id="createEditBtn" class="toolbar-btn toolbar-btn-primary">
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
          <span>Create/Edit</span>
        </button>

        <div class="search-box">
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
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
          </svg>
          <input type="text" id="searchInput" placeholder="Search rooms..." />
        </div>

        <select id="sizeFilter" class="filter-select">
          <option value="all">All sizes</option>
          <option value="small">Small (1-20)</option>
          <option value="medium">Medium (21-40)</option>
          <option value="large">Large (41+)</option>
        </select>

        <select id="roomFilter" class="filter-select">
          <option value="all">All rooms</option>
          <option value="available">Available</option>
          <option value="occupied">Occupied</option>
        </select>

        <div class="view-toggle">
          <button id="gridViewBtn" class="view-btn active">
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
              <rect width="7" height="7" x="3" y="3" rx="1" />
              <rect width="7" height="7" x="14" y="3" rx="1" />
              <rect width="7" height="7" x="14" y="14" rx="1" />
              <rect width="7" height="7" x="3" y="14" rx="1" />
            </svg>
            <span>Grid</span>
          </button>
          <button id="listViewBtn" class="view-btn">
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
              <line x1="8" x2="21" y1="6" y2="6" />
              <line x1="8" x2="21" y1="12" y2="12" />
              <line x1="8" x2="21" y1="18" y2="18" />
              <line x1="3" x2="3.01" y1="6" y2="6" />
              <line x1="3" x2="3.01" y1="12" y2="12" />
              <line x1="3" x2="3.01" y1="18" y2="18" />
            </svg>
            <span>List</span>
          </button>
        </div>
      </div>

      <!-- Room Count -->
      <div class="room-count">
        <p>Found <span id="roomCount">0</span> rooms</p>
      </div>

      <!-- Rooms Container -->
      <div id="roomsContainer" class="rooms-grid"></div>
    </main>

    <!-- Create/Edit Room Modal -->
    <div id="roomModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 id="modalTitle">Create New Room</h2>
          <button id="closeModal" class="close-btn">
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
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
        <form id="roomForm" class="modal-form">
          <div class="form-group">
            <label for="roomName">Room Name *</label>
            <input
              type="text"
              id="roomName"
              required
              placeholder="e.g., ComLab 1"
            />
          </div>

          <div class="form-group">
            <label for="roomDescription">Description *</label>
            <textarea
              id="roomDescription"
              required
              placeholder="Large laboratory room perfect for a big class"
            ></textarea>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="roomCapacity">Capacity *</label>
              <input
                type="number"
                id="roomCapacity"
                required
                placeholder="40"
                min="1"
              />
            </div>

            <div class="form-group">
              <label for="roomBuilding">Building *</label>
              <input
                type="text"
                id="roomBuilding"
                required
                placeholder="IT Building"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="roomFloor">Floor *</label>
              <input
                type="text"
                id="roomFloor"
                required
                placeholder="1st Floor"
              />
            </div>

            <div class="form-group">
              <label for="roomStatus">Status *</label>
              <select id="roomStatus" required>
                <option value="Available">Available</option>
                <option value="Occupied">Occupied</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="roomStartTime">Start Time *</label>
              <input type="time" id="roomStartTime" required />
            </div>

            <div class="form-group">
              <label for="roomEndTime">End Time *</label>
              <input type="time" id="roomEndTime" required />
            </div>
          </div>

          <div class="form-group">
            <label>Amenities</label>
            <div class="checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" id="amenityWifi" checked />
                <span>Wifi</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" id="amenityPowerSocket" checked />
                <span>Power Socket</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" id="amenityProjector" checked />
                <span>Projector</span>
              </label>
            </div>
          </div>

          <div class="form-actions">
            <button type="button" id="cancelBtn" class="btn-secondary">
              Cancel
            </button>
            <button type="submit" class="btn-primary">Save Room</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content delete-modal-content">
        <div class="modal-header">
          <h2>Delete Room</h2>
          <button id="closeDeleteModal" class="close-btn">
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
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
        <div class="delete-modal-body">
          <div class="delete-warning-icon">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="48"
              height="48"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M3 6h18" />
              <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
              <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
              <line x1="10" x2="10" y1="11" y2="17" />
              <line x1="14" x2="14" y1="11" y2="17" />
            </svg>
          </div>
          <h3>Are you sure you want to delete this room?</h3>
          <p id="deleteRoomName" class="delete-room-name"></p>
          <p class="delete-warning-text">
            This action cannot be undone. All bookings associated with this room will also be removed.
          </p>
        </div>
        <div class="delete-modal-actions">
          <button id="cancelDeleteBtn" class="btn-secondary">
            Cancel
          </button>
          <button id="confirmDeleteBtn" class="btn-danger">
            Delete Room
          </button>
        </div>
      </div>
    </div>
<div id="bookRoomModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 id="bookModalTitle">Book Room: <span id="roomNameToBook"></span></h2>
          <button id="closeBookModal" class="close-btn">
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
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
        <form id="bookRoomForm" class="modal-form">
          <input type="hidden" id="bookRoomId" name="roomId" required>
          <div class="form-group">
            <label for="bookingTitle">Meeting Title *</label>
            <input
              type="text"
              id="bookingTitle"
              required
              placeholder="e.g., ITMSD Laboratory Session"
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="bookingDate">Date *</label>
              <input type="date" id="bookingDate" required />
            </div>
            <div class="form-group">
              <label for="bookingAttendees">Attendees * (Max: <span id="maxCapacity"></span>)</label>
              <input
                type="number"
                id="bookingAttendees"
                required
                placeholder="20"
                min="1"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="bookingStartTime">Start Time *</label>
              <input type="time" id="bookingStartTime" required />
            </div>

            <div class="form-group">
              <label for="bookingEndTime">End Time *</label>
              <input type="time" id="bookingEndTime" required />
            </div>
          </div>

          <div class="form-group">
            <label for="bookingRecurring">Recurring (e.g., MWF, TTh, None)</label>
            <input type="text" id="bookingRecurring" value="None" />
          </div>

          <div class="form-group">
            <label for="bookingDescription">Description (Optional)</label>
            <textarea
              id="bookingDescription"
              placeholder="Detailed purpose of the meeting or event"
            ></textarea>
          </div>

          <div class="form-actions">
            <button type="button" id="cancelBookBtn" class="btn-secondary">
              Cancel
            </button>
            <button type="submit" class="btn-primary">Submit Request</button>
          </div>
        </form>
      </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/js/script.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/rooms.js"></script>
  </body>
</html>

