<?php
// require_once __DIR__ . '/../../database/session_manager.php';
//      // The path is relative from 'src/pages/' up to root, then down to 'database/'
//       require_once __DIR__ . '/../../database/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/teachers.css">
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>

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

    <main id="mainContent" class="main-content">
        <div class="page-header">
            <h1>Users Management</h1>
            <p>Add and edit your users here</p>
        </div>

        <div class="toolbar">
            <!-- <button class="toolbar-btn" id="createEditBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span>Create/Edit</span>
            </button> -->

            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search users...">
            </div>

            <select id="filterTeachers" class="filter-select">
                <option value="all">All users</option>
                <option value="active">Active</option>
                <option value="offline">Offline</option>
            </select>

            <select id="filterDepartment" class="filter-select">
                <option value="all">All Departments</option>
                <option value="BSIT">BS-Information Technology</option>
                <option value="BSCS">BS-Computer Science</option>
                <option value="BSCE">BS-Computer Engineering</option>
            </select>

            <button class="view-toggle active" id="gridViewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span>Grid</span>
            </button>

            <button class="view-toggle" id="listViewBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" x2="21" y1="6" y2="6"/>
                    <line x1="8" x2="21" y1="12" y2="12"/>
                    <line x1="8" x2="21" y1="18" y2="18"/>
                    <line x1="3" x2="3.01" y1="6" y2="6"/>
                    <line x1="3" x2="3.01" y1="12" y2="12"/>
                    <line x1="3" x2="3.01" y1="18" y2="18"/>
                </svg>
                <span>List</span>
            </button>
        </div>

        <p class="results-count" id="resultsCount">Found users</p>

        <div class="teachers-grid" id="teachersContainer">
             Teachers will be dynamically loaded here 
        </div>
    </main>

 
    <div class="modal" id="teacherModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Create New User</h2>
                <button class="close-btn" id="closeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            <form id="teacherForm" class="teacher-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="firstName" required>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" id="middleName">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="lastName" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label>ID Number</label>
                        <input type="text" id="idNumber" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" id="contactNumber" required>
                    </div>
                    <div class="form-group">
                        <label>Birthdate</label>
                        <input type="date" id="birthdate" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Department</label>
                        <select id="department" required>
                            <option value="">Select Department</option>
                            <option value="BS-Information Technology">BS-Information Technology</option>
                            <option value="BS-Computer Science">BS-Computer Science</option>
                            <option value="BS-Computer Engineering">BS-Computer Engineering</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" required>
                            <option value="Active">Active</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Profile Picture</label>
                    <div class="file-upload">
                        <input type="file" id="profilePicture" accept="image/*">
                        <div class="file-upload-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            <p>Click to upload or drag and drop</p>
                            <p class="file-hint">SVG, PNG, or JPG (max. 100×400px)</p>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/js/script.js"></script>
    <script src="<?php echo BASE_URL; ?>/js/teachers.js"></script>
</body>
</html>
