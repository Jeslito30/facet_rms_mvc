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
    <link rel="stylesheet" href="../../styles/teachers.css" />
    <link rel="stylesheet" href="../../styles/teacher-details.css" />
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
        <h1>Users Management</h1>
        <p>Add and edit your users here</p>
      </div>

      <div class="details-container">
        <div class="details-form-panel">
          <button
            class="back-btn"
            onclick="window.location.href='teachers.php'"
          >
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

          <div class="form-section-header">
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
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <h2>Personal Information</h2>
          </div>

          <form id="teacherDetailsForm" class="details-form">
            <div class="form-row">
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" id="lastName" readonly />
              </div>
              <div class="form-group">
                <label>First Name</label>
                <input type="text" id="firstName" readonly />
              </div>
              <div class="form-group">
                <label>Middle Name</label>
                <input type="text" id="middleName" readonly />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" readonly />
              </div>
              <div class="form-group">
                <label>ID Number</label>
                <input type="text" id="idNumber" readonly />
              </div>
              <div class="form-group">
                <label>Role</label>
                <input type="text" value="Teacher" readonly />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Contact Number</label>
                <input type="tel" id="contactNumber" readonly />
              </div>
              <div class="form-group">
                <label>Birthdate</label>
                <input type="text" id="birthdate" readonly />
              </div>
              <div class="form-group">
                <label>Password (8-16)</label>
                <input type="password" value="****************" readonly />
              </div>
            </div>

            <div class="form-group">
              <label>Profile Picture</label>
              <div class="file-upload-display">
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
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                  <polyline points="17 8 12 3 7 8" />
                  <line x1="12" x2="12" y1="3" y2="15" />
                </svg>
                <p>Click to upload or drag and drop</p>
                <p class="file-hint">SVG, PNG, or JPG (max. 100×400px)</p>
              </div>
            </div>
          </form>
        </div>

        <div class="details-preview-panel">
          <div class="preview-card">
            <div class="preview-image" id="previewImage">
              <img src="../assets/images/placeholder.svg?height=200&width=300" alt="User" />
            </div>

            <div class="preview-info">
              <h3 id="previewId">2023-0222</h3>
              <div class="preview-detail">
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
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                <span id="previewName">Geverola, Jeslito Galagar</span>
              </div>
              <div class="preview-detail">
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
                  <rect width="18" height="18" x="3" y="4" rx="2" />
                  <path d="M16 2v4" />
                  <path d="M8 2v4" />
                  <path d="M3 10h18" />
                </svg>
                <span id="previewBirthdate">01-30-2005</span>
              </div>
              <div class="preview-detail">
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
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                  />
                </svg>
                <span id="previewContact">09983036136</span>
              </div>
              <div class="preview-detail">
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
                  <rect width="20" height="16" x="2" y="4" rx="2" />
                  <path d="m6 8 6 4 6-4" />
                </svg>
                <span id="previewEmail">jeslito.geverola@dorsu.edu.ph</span>
              </div>
              <div class="preview-detail">
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
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                <span>User</span>
              </div>
              <div class="preview-department" id="previewDepartment">
                BS - Information Technology
              </div>
            </div>

            <button
              class="btn-edit-large"
              onclick="window.location.href='teachers.php'"
            >
              Edit
            </button>
          </div>
        </div>
      </div>
    </main>

    <script src="../../scripts/script.js"></script>
    <script src="../../scripts/teacher-details.js"></script>
  </body>
</html>
