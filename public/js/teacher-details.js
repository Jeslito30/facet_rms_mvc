// API Configuration
const API_ENDPOINT = '/facet-rms/public/teacher';

// Get teacher ID from URL
const urlParams = new URLSearchParams(window.location.search);
const teacherId = urlParams.get('id');

let teacher = null;

// Load teacher data from database
async function loadTeacherDetails() {
    if (!teacherId) {
        alert('No user ID provided. Redirecting to users list.');
        window.location.href = "/facet-rms/public/teacher/index";
        return;
    }

    try {
        const response = await fetch(`${API_ENDPOINT}/get_api/${teacherId}`);
        const result = await response.json();
        
        if (!result.success) {
            alert('Error loading user: ' + result.message);
            window.location.href = "/facet-rms/public/teacher/index";
            return;
        }
        
        teacher = result.data;
        populateTeacherDetails(teacher);
        
    } catch (error) {
        console.error('Error loading user details:', error);
        alert('Failed to load user details. Redirecting to users list.');
        window.location.href = "/facet-rms/public/teacher/index";
    }
}

// Populate teacher details
function populateTeacherDetails(teacher) {
    // Parse fullname (format: "LastName, FirstName MiddleName")
    let firstName = '';
    let middleName = '';
    let lastName = '';
    
    if (teacher.fullname && teacher.fullname.includes(',')) {
        const [lastNamePart, restPart] = teacher.fullname.split(',').map(s => s.trim());
        lastName = lastNamePart;
        
        if (restPart) {
            const nameParts = restPart.split(' ').filter(part => part.trim());
            firstName = nameParts[0] || '';
            middleName = nameParts.slice(1).join(' ') || '';
        }
    } else if (teacher.fullname) {
        const nameParts = teacher.fullname.split(' ').filter(part => part.trim());
        firstName = nameParts[0] || '';
        lastName = nameParts[nameParts.length - 1] || '';
        middleName = nameParts.slice(1, -1).join(' ') || '';
    }

    // Populate form fields
    document.getElementById("firstName").value = firstName;
    document.getElementById("middleName").value = middleName;
    document.getElementById("lastName").value = lastName;
    document.getElementById("email").value = teacher.email || "";
    document.getElementById("idNumber").value = teacher.id_number || "";
    document.getElementById("contactNumber").value = teacher.contact_number || "";

    // Format birthdate for display
    if (teacher.birthdate) {
        const date = new Date(teacher.birthdate);
        const formattedDate = date.toLocaleDateString("en-US", { 
            month: "long", 
            day: "numeric", 
            year: "numeric" 
        });
        document.getElementById("birthdate").value = formattedDate;
    }

    // Populate preview card
    document.getElementById("previewId").textContent = teacher.id_number || "";
    document.getElementById("previewName").textContent = teacher.fullname || "";
    
    if (teacher.birthdate) {
        const date = new Date(teacher.birthdate);
        document.getElementById("previewBirthdate").textContent = date.toLocaleDateString("en-US", {
            month: "2-digit",
            day: "2-digit",
            year: "numeric",
        });
    }
    
    document.getElementById("previewContact").textContent = teacher.contact_number || "";
    document.getElementById("previewEmail").textContent = teacher.email || "";
    document.getElementById("previewDepartment").textContent = teacher.department || "";

    // Load profile picture if available
    const previewImageElement = document.getElementById("previewImage");
    
    if (teacher.profile_picture_type) {
        const imgUrl = `${API_ENDPOINT}/picture_api/${teacher.id}`;
        previewImageElement.innerHTML = `<img src="${imgUrl}" alt="${teacher.fullname}" onerror="this.style.display='none'; this.parentElement.innerHTML='<svg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'100\\' height=\\'100\\' viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\' stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\'><path d=\\'M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2\\'/><circle cx=\\'12\\' cy=\\'7\\' r=\\'4\\'/></svg>';">`;
    } else {
        // Use default placeholder
        previewImageElement.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>`;
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadTeacherDetails();
});