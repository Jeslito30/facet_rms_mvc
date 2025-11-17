// API Configuration
const API_ENDPOINT = '../../database/teachers_api.php';

// State
let teachers = [];
let currentView = "grid";
let editingTeacherId = null;
let profilePictureBase64 = null;

// DOM Elements
const teachersContainer = document.getElementById("teachersContainer");
const searchInput = document.getElementById("searchInput");
const statusFilterDropdown = document.getElementById("filterTeachers");
const departmentFilterDropdown = document.getElementById("filterDepartment");
const gridViewBtn = document.getElementById("gridViewBtn");
const listViewBtn = document.getElementById("listViewBtn");
const createEditBtn = document.getElementById("createEditBtn");
const teacherModal = document.getElementById("teacherModal");
const closeModal = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const teacherForm = document.getElementById("teacherForm");
const resultsCount = document.getElementById("resultsCount");
const profilePictureInput = document.getElementById("profilePicture");

// Load teachers from database
async function loadTeachers() {
    try {
        const searchTerm = searchInput.value;
        const status = statusFilterDropdown.value;
        const department = departmentFilterDropdown.value;
        
        const params = new URLSearchParams({
            action: 'list',
            search: searchTerm,
            status: status,
            department: department
        });
        
        const response = await fetch(`${API_ENDPOINT}?${params}`);
        const result = await response.json();
        
        if (result.success) {
            teachers = result.data;
            renderTeachers(teachers);
        } else {
            alert('Error loading users: ' + result.message);
        }
    } catch (error) {
        console.error('Error loading users:', error);
        alert('Failed to load users. Please check your connection.');
    }
}

// Render teachers
function renderTeachers(teachersToRender) {
    teachersContainer.innerHTML = "";
    teachersContainer.className = currentView === "grid" ? "teachers-grid" : "teachers-grid list-view";

    if (teachersToRender.length === 0) {
        teachersContainer.innerHTML = '<p style="text-align: center; padding: 2rem; color: #666;">No users found</p>';
        resultsCount.textContent = 'Found 0 users';
        return;
    }

    teachersToRender.forEach((teacher) => {
        const teacherCard = document.createElement("div");
        teacherCard.className = "teacher-card";
        
        // Build profile picture URL or use default avatar
        const profilePicUrl = teacher.profile_picture_type 
            ? `${API_ENDPOINT}?action=picture&id=${teacher.id}` 
            : null;
        
        teacherCard.innerHTML = `
            <div class="teacher-card-header">
                <div class="teacher-avatar">
                    ${profilePicUrl 
                        ? `<img src="${profilePicUrl}" alt="${teacher.first_name}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                           <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`
                        : `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`
                    }
                </div>
                <div class="teacher-info">
                    <div class="teacher-id-status">
                        <span class="teacher-id">${teacher.id_number}</span>
                        <span class="teacher-status ${teacher.status.toLowerCase()}">${teacher.status}</span>
                    </div>
                    <p class="teacher-name">${teacher.fullname}</p>
                    <p class="teacher-department">Department<br>${teacher.department}</p>
                </div>
            </div>
            <div class="teacher-actions">
                <button class="btn-view" onclick="viewTeacher('${teacher.id}')">View Details</button>
                <button class="btn-edit" onclick="editTeacher('${teacher.id}')">Edit</button>
                <button 
    title="Delete User" 
    onclick="deleteTeacher('${teacher.id}')" 
    class="btn-icon-danger"
  >
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 6h18"/>
      <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
      <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
    </svg>
  </button>
            </div>
        `;
        teachersContainer.appendChild(teacherCard);
    });

    resultsCount.textContent = `Found ${teachersToRender.length} user${teachersToRender.length !== 1 ? "s" : ""}`;
}

// View teacher details
window.viewTeacher = async (id) => {
    try {
        const response = await fetch(`${API_ENDPOINT}?action=get&id=${id}`);
        const result = await response.json();
        
        if (result.success) {
            // Store user data for details page
            localStorage.setItem("viewUserData", JSON.stringify(result.data));
            window.location.href = `teacher-details.php?id=${id}`;
        } else {
            alert('Error loading user details: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load user details');
    }
};

// Edit teacher
window.editTeacher = async (id) => {
    try {
        const response = await fetch(`${API_ENDPOINT}?action=get&id=${id}`);
        const result = await response.json();
        
        console.log('API Response:', result); // Debug log
        
        if (!result.success) {
            alert('Error loading user: ' + result.message);
            return;
        }
        
        const teacher = result.data;
        
        // Check if teacher data exists
        if (!teacher || !teacher.fullname) {
            alert('User data is incomplete or missing');
            console.error('Teacher data:', teacher);
            return;
        }
        
        editingTeacherId = id;
        
        document.getElementById("modalTitle").textContent = "Edit User";

        // Safely parse fullname (format: "LastName, FirstName MiddleName")
        let firstName = '';
        let middleName = '';
        let lastName = '';
        
        if (teacher.fullname.includes(',')) {
            const [lastNamePart, restPart] = teacher.fullname.split(',').map(s => s.trim());
            lastName = lastNamePart;
            
            if (restPart) {
                const nameParts = restPart.split(' ').filter(part => part.trim());
                firstName = nameParts[0] || '';
                middleName = nameParts.slice(1).join(' ') || '';
            }
        } else {
            // Fallback if fullname doesn't have comma
            const nameParts = teacher.fullname.split(' ').filter(part => part.trim());
            firstName = nameParts[0] || '';
            lastName = nameParts[nameParts.length - 1] || '';
            middleName = nameParts.slice(1, -1).join(' ') || '';
        }
        
        // Populate form fields
        document.getElementById("firstName").value = firstName;
        document.getElementById("middleName").value = middleName;
        document.getElementById("lastName").value = lastName;
        document.getElementById("email").value = teacher.email || '';
        document.getElementById("idNumber").value = teacher.id_number || '';
        document.getElementById("contactNumber").value = teacher.contact_number || '';
        document.getElementById("department").value = teacher.department || '';
        document.getElementById("status").value = teacher.status || 'Active';
        
        // Format birthdate for input (YYYY-MM-DD)
        if (teacher.birthdate) {
            const date = new Date(teacher.birthdate);
            const formattedDate = date.toISOString().split('T')[0];
            document.getElementById("birthdate").value = formattedDate;
        }
        
        profilePictureBase64 = null; // Reset profile picture
        teacherModal.classList.add("active");
    } catch (error) {
        console.error('Error in editTeacher:', error);
        alert('Failed to load user data: ' + error.message);
    }
};

// Delete teacher
window.deleteTeacher = async (id, name) => {
    if (!confirm(`Are you sure you want to delete ${name}?`)) {
        return;
    }
    
    try {
        const response = await fetch(`${API_ENDPOINT}?action=delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('User deleted successfully!');
            loadTeachers();
        } else {
            alert('Error deleting user: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete user');
    }
};

// Handle profile picture upload
profilePictureInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file');
        e.target.value = '';
        return;
    }
    
    // Validate file size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Image size must be less than 2MB');
        e.target.value = '';
        return;
    }
    
    // Convert to base64
    const reader = new FileReader();
    reader.onload = (event) => {
        profilePictureBase64 = event.target.result;
    };
    reader.readAsDataURL(file);
});

// Filter and search
function filterAndSearchTeachers() {
    loadTeachers();
}

searchInput.addEventListener("input", filterAndSearchTeachers);
statusFilterDropdown.addEventListener("change", filterAndSearchTeachers);
departmentFilterDropdown.addEventListener("change", filterAndSearchTeachers);

// View toggle
gridViewBtn.addEventListener("click", () => {
    currentView = "grid";
    gridViewBtn.classList.add("active");
    listViewBtn.classList.remove("active");
    renderTeachers(teachers);
});

listViewBtn.addEventListener("click", () => {
    currentView = "list";
    listViewBtn.classList.add("active");
    gridViewBtn.classList.remove("active");
    renderTeachers(teachers);
});

// Modal controls
// createEditBtn.addEventListener("click", () => {
//     editingTeacherId = null;
//     profilePictureBase64 = null;
//     document.getElementById("modalTitle").textContent = "Create New User";
//     teacherForm.reset();
//     teacherModal.classList.add("active");
// });

closeModal.addEventListener("click", () => {
    teacherModal.classList.remove("active");
});

cancelBtn.addEventListener("click", () => {
    teacherModal.classList.remove("active");
});

// Form submission
teacherForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const submitBtn = teacherForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = editingTeacherId ? 'Updating...' : 'Creating...';
    
    const firstName = document.getElementById("firstName").value;
    const middleName = document.getElementById("middleName").value;
    const lastName = document.getElementById("lastName").value;
    
    const formData = {
        fullname: `${lastName}, ${firstName} ${middleName}`.trim(),
        email: document.getElementById("email").value,
        idNumber: document.getElementById("idNumber").value,
        contactNumber: document.getElementById("contactNumber").value,
        birthdate: document.getElementById("birthdate").value,
        department: document.getElementById("department").value,
        status: document.getElementById("status").value,
    };
    
    // Add profile picture if uploaded
    if (profilePictureBase64) {
        formData.profilePicture = profilePictureBase64;
    }
    
    try {
        const action = editingTeacherId ? 'update' : 'create';
        if (editingTeacherId) {
            formData.id = editingTeacherId;
        }
        
        const response = await fetch(`${API_ENDPOINT}?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(editingTeacherId ? 'User updated successfully!' : 'User created successfully!');
            teacherModal.classList.remove("active");
            teacherForm.reset();
            profilePictureBase64 = null;
            loadTeachers();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to save user. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save User';
    }
});

// Initial load when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadTeachers();
});