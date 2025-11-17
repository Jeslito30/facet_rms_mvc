// rooms.js - FULLY MODIFIED TO USE ASYNCHRONOUS FETCH API FOR DATABASE OPERATIONS

// The global rooms array will now be populated by the database on load
let rooms = []

// --- API Interaction Functions (REPLACING localStorage) ---
// Path adjusted for your file structure: /src/pages/rooms.php -> /database/rooms_api.php
// This assumes the script is run from an HTML file in 'src/pages'
const API_ENDPOINT = '/facet-rms/database/rooms_api.php'
/**
 * Fetches all rooms from the PHP API endpoint. (READ operation)
 */
async function loadRoomsFromDB() {
  try {
    console.log('Loading rooms from API:', `${API_ENDPOINT}?action=get_all`);
    const response = await fetch(`${API_ENDPOINT}?action=get_all`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const text = await response.text();
    console.log('API Response:', text);
    
    let result;
    try {
      result = JSON.parse(text);
    } catch (parseError) {
      console.error('JSON Parse Error:', parseError);
      console.error('Response text:', text);
      throw new Error('Invalid JSON response from server');
    }
    
    if (result.success && Array.isArray(result.data)) {
      rooms = result.data;
      console.log(`Loaded ${rooms.length} rooms from database:`, rooms);
      renderRooms(); 
    } else {
      console.error('API Error:', result.message || 'Unknown error');
      showNotification(`Failed to load rooms: ${result.message || 'Unknown error'}`, 'error');
    }
  } catch (error) {
    console.error('Fetch Error:', error);
    showNotification(`Could not connect to the server or fetch data. Check if ${API_ENDPOINT} is running.`, 'error');
    // Render empty state
    if (roomsContainer) {
      roomsContainer.innerHTML = '<p>Failed to load rooms. Please refresh the page.</p>'
    }
  }
}

/**
 * Saves a single room (CREATE or UPDATE) to the PHP API endpoint.
 */
async function saveRoomToDB(roomData) {
  try {
    const response = await fetch(`${API_ENDPOINT}?action=save_room`, {
      method: 'POST', // Use POST for create/update
      headers: {
        'Content-Type': 'application/json', // Tell the server we are sending JSON
      },
      body: JSON.stringify(roomData), // Send the room object as a JSON string
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (result.success) {
      // Reload all rooms to sync the local 'rooms' array and UI (gets new 'id' on creation)
      await loadRoomsFromDB(); 
      showNotification(`Room ${roomData.id ? 'updated' : 'created'} successfully.`, 'success');
      return result.id; 
    } else {
      throw new Error(result.message);
    }
  } catch (error) {
    console.error('Save Error:', error);
    showNotification(`Failed to save room: ${error.message}`, 'error');
    return null;
  }
}

/**
 * Deletes a single room by ID from the PHP API endpoint. (DELETE operation)
 */
async function deleteRoomFromDB(roomId, roomName) {
  try {
    const response = await fetch(`${API_ENDPOINT}?action=delete_room`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id: roomId }), // Send only the ID needed for deletion
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (result.success) {
      // Reload all rooms to update the UI
      await loadRoomsFromDB();
      showNotification(`Room "${roomName}" has been deleted successfully.`, "success");
      return true;
    } else {
      throw new Error(result.message);
    }
  } catch (error) {
    console.error('Delete Error:', error);
    showNotification(`Failed to delete room: ${error.message}`, 'error');
    return false;
  }
}


// ----------------------------------------------------------------------
// --- Synchronization and Cleanup ---

// REMOVE/COMMENT OUT the original local storage-based initialization
// const sampleRooms = [...] // KEEP for reference if needed, but not used for initialization
// const rooms = JSON.parse(localStorage.getItem("rooms")) || sampleRooms 
// REMOVE: function saveRooms() { localStorage.setItem("rooms", JSON.stringify(rooms)) }

let currentView = "grid"
let editingRoomId = null

// DOM Elements (kept as is)
const roomsContainer = document.getElementById("roomsContainer")
const roomCount = document.getElementById("roomCount")
const searchInput = document.getElementById("searchInput")
const sizeFilter = document.getElementById("sizeFilter")
const roomFilter = document.getElementById("roomFilter")
const gridViewBtn = document.getElementById("gridViewBtn")
const listViewBtn = document.getElementById("listViewBtn")
const createEditBtn = document.getElementById("createEditBtn")
const roomModal = document.getElementById("roomModal")
const closeModal = document.getElementById("closeModal")
const cancelBtn = document.getElementById("cancelBtn")
const roomForm = document.getElementById("roomForm")
const modalTitle = document.getElementById("modalTitle")

// Delete modal elements
const deleteModal = document.getElementById("deleteModal")
const closeDeleteModal = document.getElementById("closeDeleteModal")
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn")
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn")
const deleteRoomName = document.getElementById("deleteRoomName")
let roomToDelete = null

// --- Helper Functions ---

/**
 * Format time from HH:MM:SS or HH:MM to HH:MM AM/PM
 */
function formatTime(timeString) {
  if (!timeString) return ''
  // Handle both HH:MM:SS and HH:MM formats
  const timeParts = timeString.split(':')
  const hours = timeParts[0]
  const minutes = timeParts[1] || '00'
  const hour = parseInt(hours, 10)
  const ampm = hour >= 12 ? 'PM' : 'AM'
  const displayHour = hour % 12 || 12
  return `${displayHour}:${minutes} ${ampm}`
}

/**
 * Create a room card HTML element
 */
function createRoomCard(room) {
  const amenities = room.amenities || {}
  const statusClass = room.status?.toLowerCase() === 'available' ? 'available' : 'occupied'
  const statusText = room.status || 'Unknown'
  
  const amenitiesHTML = [
    amenities.wifi ? '<div class="amenity-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg><span>Wifi</span></div>' : '',
    amenities.powerSocket ? '<div class="amenity-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span>Power Socket</span></div>' : '',
    amenities.projector ? '<div class="amenity-item"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg><span>Projector</span></div>' : ''
  ].filter(Boolean).join('')

  const startTime = formatTime(room.startTime || room.start_time || '')
  const endTime = formatTime(room.endTime || room.end_time || '')
  
  return `
    <div class="room-card-detailed">
      <div class="room-card-header">
        <div class="room-card-title">
          <h3>${room.name || 'Unnamed Room'}</h3>
          <div class="room-card-time">(${startTime} - ${endTime})</div>
        </div>
        <span class="room-status-badge ${statusClass}">${statusText}</span>
      </div>
      <div class="room-card-description">${room.description || 'No description'}</div>
      <div class="room-card-info">
        <div class="room-info-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>${room.capacity || 0} people</span>
        </div>
        <div class="room-info-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          <span>${room.building || ''}, ${room.floor || ''}</span>
        </div>
      </div>
      ${amenitiesHTML ? `<div class="room-amenities">
        <div class="room-amenities-label">Amenities</div>
        <div class="amenities-list">${amenitiesHTML}</div>
      </div>` : ''}
      <div class="room-card-actions">
        <button class="btn-outline" onclick="editRoom(${room.id})">View Details</button>
        <button class="btn-orange" onclick="window.bookRoom && window.bookRoom(${room.id})">Book Now</button>
        <button class="btn-outline" onclick="deleteRoom(${room.id})" style="padding: 12px; min-width: auto;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 6h18"/>
            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
          </svg>
        </button>
      </div>
    </div>
  `
}

/**
 * Filter rooms based on search and filters
 */
function filterRooms() {
  const searchTerm = searchInput.value.toLowerCase()
  const sizeFilterValue = sizeFilter.value
  const roomFilterValue = roomFilter.value

  return rooms.filter(room => {
    // Search filter
    const matchesSearch = !searchTerm || 
      room.name?.toLowerCase().includes(searchTerm) ||
      room.description?.toLowerCase().includes(searchTerm) ||
      room.building?.toLowerCase().includes(searchTerm) ||
      room.floor?.toLowerCase().includes(searchTerm)

    // Size filter
    const capacity = parseInt(room.capacity) || 0
    let matchesSize = true
    if (sizeFilterValue === 'small') matchesSize = capacity >= 1 && capacity <= 20
    else if (sizeFilterValue === 'medium') matchesSize = capacity >= 21 && capacity <= 40
    else if (sizeFilterValue === 'large') matchesSize = capacity >= 41

    // Status filter
    const matchesStatus = roomFilterValue === 'all' || 
      room.status?.toLowerCase() === roomFilterValue.toLowerCase()

    return matchesSearch && matchesSize && matchesStatus
  })
}

/**
 * Render all rooms to the UI
 */
function renderRooms() {
  if (!roomsContainer) {
    console.error('roomsContainer not found')
    return
  }

  const filteredRooms = filterRooms()
  
  if (roomCount) {
    roomCount.textContent = filteredRooms.length
  }

  if (currentView === 'grid') {
    roomsContainer.className = 'rooms-grid'
    roomsContainer.innerHTML = filteredRooms.map(room => createRoomCard(room)).join('')
  } else {
    roomsContainer.className = 'rooms-list'
    roomsContainer.innerHTML = filteredRooms.map(room => createRoomCard(room)).join('')
  }
}

/**
 * Open modal for creating or editing a room
 */
function openModal(isEdit = false, roomId = null) {
  editingRoomId = roomId
  modalTitle.textContent = isEdit ? 'Edit Room' : 'Create New Room'
  
  if (isEdit && roomId) {
    const room = rooms.find(r => r.id == roomId)
    if (room) {
      document.getElementById('roomName').value = room.name || ''
      document.getElementById('roomDescription').value = room.description || ''
      document.getElementById('roomCapacity').value = room.capacity || ''
      document.getElementById('roomBuilding').value = room.building || ''
      document.getElementById('roomFloor').value = room.floor || ''
      document.getElementById('roomStatus').value = room.status || 'Available'
      document.getElementById('roomStartTime').value = room.startTime || room.start_time || ''
      document.getElementById('roomEndTime').value = room.endTime || room.end_time || ''
      
      const amenities = room.amenities || {}
      document.getElementById('amenityWifi').checked = amenities.wifi || false
      document.getElementById('amenityPowerSocket').checked = amenities.powerSocket || false
      document.getElementById('amenityProjector').checked = amenities.projector || false
    }
  } else {
    roomForm.reset()
    editingRoomId = null
  }
  
  roomModal.classList.add('active')
}

/**
 * Close the create/edit modal
 */
function closeModalFunc() {
  roomModal.classList.remove('active')
  roomForm.reset()
  editingRoomId = null
}

/**
 * Open delete confirmation modal
 */
function openDeleteModal(roomId) {
  const room = rooms.find(r => r.id === roomId)
  if (room) {
    roomToDelete = room
    deleteRoomName.textContent = room.name
    deleteModal.classList.add('active')
  }
}

/**
 * Close delete confirmation modal
 */
function closeDeleteModalFunc() {
  deleteModal.classList.remove('active')
  roomToDelete = null
}

/**
 * Show notification to user
 */
function showNotification(message, type = 'info') {
  // Create notification element
  const notification = document.createElement('div')
  notification.className = `notification notification-${type}`
  notification.textContent = message
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
  `
  
  document.body.appendChild(notification)
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease'
    setTimeout(() => notification.remove(), 300)
  }, 3000)
}

// --- EVENT LISTENERS ---

// Form submission is now async and uses saveRoomToDB
if (roomForm) {
  roomForm.addEventListener("submit", async (e) => {
    e.preventDefault()

    // 1. Prepare data
    const roomData = {
      // Only include ID if editing an existing room (which has a real DB ID)
      ...(editingRoomId && Number(editingRoomId) > 0 && { id: editingRoomId }), 
      name: document.getElementById("roomName").value,
      description: document.getElementById("roomDescription").value,
      capacity: Number.parseInt(document.getElementById("roomCapacity").value),
      building: document.getElementById("roomBuilding").value,
      floor: document.getElementById("roomFloor").value,
      status: document.getElementById("roomStatus").value,
      startTime: document.getElementById("roomStartTime").value,
      endTime: document.getElementById("roomEndTime").value,
      amenities: {
        wifi: document.getElementById("amenityWifi").checked,
        powerSocket: document.getElementById("amenityPowerSocket").checked,
        projector: document.getElementById("amenityProjector").checked,
      },
    }

    // 2. Save data to the database
    const savedId = await saveRoomToDB(roomData);

    // 3. Close modal only if successful (saveRoomToDB handles reload and notification)
    if (savedId) {
      closeModalFunc()
    }
  })
}

// Create/Edit button
if (createEditBtn) {
  createEditBtn.addEventListener("click", () => openModal(false))
}

// Close modal buttons
if (closeModal) {
  closeModal.addEventListener("click", closeModalFunc)
}
if (cancelBtn) {
  cancelBtn.addEventListener("click", closeModalFunc)
}

// Delete modal buttons
if (closeDeleteModal) {
  closeDeleteModal.addEventListener("click", closeDeleteModalFunc)
}
if (cancelDeleteBtn) {
  cancelDeleteBtn.addEventListener("click", closeDeleteModalFunc)
}

// Confirm delete (now async and uses deleteRoomFromDB)
if (confirmDeleteBtn) {
  confirmDeleteBtn.addEventListener("click", async () => {
    if (roomToDelete) {
      // Disable button to prevent multiple submissions while waiting for API
      confirmDeleteBtn.disabled = true;

      // Use the new asynchronous delete function
      await deleteRoomFromDB(roomToDelete.id, roomToDelete.name);
      
      // Re-enable button and close modal
      confirmDeleteBtn.disabled = false;
      closeDeleteModalFunc()
    }
  })
}

// Search and filter event listeners
if (searchInput) {
  searchInput.addEventListener("input", renderRooms)
}
if (sizeFilter) {
  sizeFilter.addEventListener("change", renderRooms)
}
if (roomFilter) {
  roomFilter.addEventListener("change", renderRooms)
}

// View toggle buttons
if (gridViewBtn) {
  gridViewBtn.addEventListener("click", () => {
    currentView = "grid"
    gridViewBtn.classList.add("active")
    listViewBtn.classList.remove("active")
    renderRooms()
  })
}
if (listViewBtn) {
  listViewBtn.addEventListener("click", () => {
    currentView = "list"
    listViewBtn.classList.add("active")
    gridViewBtn.classList.remove("active")
    renderRooms()
  })
}

// Global functions for onclick handlers
window.editRoom = (roomId) => {
  openModal(true, roomId)
}

window.deleteRoom = (roomId) => {
  const room = rooms.find((r) => r.id == roomId)
  if (room) {
    roomToDelete = room
    deleteRoomName.textContent = room.name
    deleteModal.classList.add("active")
  }
}


// --- Initialization ---

// Wait for DOM to be fully loaded before initializing
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing rooms...');
    loadRoomsFromDB();
  });
} else {
  // DOM is already loaded
  console.log('DOM already loaded, initializing rooms...');
  loadRoomsFromDB();
}