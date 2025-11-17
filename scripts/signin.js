// scripts/signin.js - MODIFIED TO USE ASYNCHRONOUS FETCH API FOR DATABASE
const signinForm = document.getElementById("signinForm");
const API_ENDPOINT = '../../database/users_api.php'; // New API path

signinForm.addEventListener("submit", async (e) => { // Make the function ASYNC
  e.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const remember = document.getElementById("remember").checked;
  const signInBtn = document.querySelector('.signin-btn');

  if (email && password) {
    signInBtn.disabled = true;
    signInBtn.textContent = 'Signing In...';

    const credentials = { email, password };

    try {
        // Call the PHP API for sign-in
        const response = await fetch(`${API_ENDPOINT}?action=signin`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(credentials)
        });

        const result = await response.json();

        if (result.success) {
            console.log("Sign in successful:", result.user.email);
            
            // Store session data upon successful login
            if (remember) {
                localStorage.setItem("userSession", JSON.stringify({ 
                    id: result.user.id,
                    email: result.user.email, 
                    fullname: result.user.fullname,
                    loggedIn: true 
                }));
            }
            
            // Redirect to dashboard
            window.location.href = "index.php";
        } else {
            alert(`Sign In Failed: ${result.message}`);
        }
    } catch (error) {
        console.error('Sign In Fetch Error:', error);
        alert('An unexpected error occurred during sign in. Check server connection.');
    } finally {
        signInBtn.disabled = false;
        signInBtn.textContent = 'Sign In';
    }
  }
});

// Optionally, you might want to check for an existing session on load
document.addEventListener('DOMContentLoaded', () => {
    const session = localStorage.getItem("userSession");
    if (session) {
        const user = JSON.parse(session);
        if (user.loggedIn) {
            // Already logged in, redirect to dashboard
            // window.location.href = 
        }
    }
});