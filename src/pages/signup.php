<?php
 require_once __DIR__ . '/../../database/session_manager.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaCET-RMS - Create Account</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="../../styles/signup.css">
</head>
<body>
    <div class="background-pattern"></div>
    
    <div class="floating-rooms">
        <div class="room-card"></div>
        <div class="room-card"></div>
        <div class="room-card"></div>
        <div class="room-card"></div>
        <div class="room-card"></div>
    </div>

    <div class="container">
        <!-- Left Side - Branding -->
        <div class="brand-section">
            <div class="logo-area">
                <div class="logo-combo">
                    <div class="logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 4h3a2 2 0 0 1 2 2v14"/>
                            <path d="M2 20h3"/>
                            <path d="M13 20h9"/>
                            <path d="M10 12v.01"/>
                            <path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/>
                        </svg>
                    </div>
                    <div class="logo-text">
                        <h1>FaCET</h1>
                        <p>Room Management System</p>
                    </div>
                </div>
            </div>

            <div class="brand-content">
                <h2>Join Our Room<br>Management System</h2>
                <p>Create your account and start managing room bookings efficiently with our powerful platform.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <span>Quick and easy registration</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <span>Instant access to booking tools</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span>Secure account protection</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Sign Up Form -->
        <div class="form-section">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Sign up to start booking rooms</p>
            </div>

            <form id="signupForm" novalidate>
                <div class="step-indicator">
                    <div class="progress-bar"></div>
                    <div class="step active" data-step-indicator="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Personal</div>
                    </div>
                    <div class="step" data-step-indicator="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step" data-step-indicator="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Security</div>
                    </div>
                </div>

                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <div class="name-group">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" required>
                            <div class="error-message">First name is required.</div>
                        </div>
                        <div class="form-group middle-name">
                            <label for="middleName">Middle Name</label>
                            <input type="text" id="middleName" name="middleName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="lastName" required>
                            <div class="error-message">Last name is required.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Birthday</label>
                        <input type="date" id="birthday" name="birthday" required>
                        <div class="error-message">Birthday is required.</div>
                    </div>
                </div>

                <!-- Step 2: Account and Contact Details -->
                <div class="form-step" data-step="2">
                    <div class="form-group">
                        <label for="idNumber">ID Number</label>
                        <input type="text" id="idNumber" name="idNumber" required>
                        <div class="error-message">ID Number is required.</div>
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="tel" id="contactNumber" name="contactNumber" required>
                        <div class="error-message">Contact number is required.</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                        <div class="error-message">A valid email is required.</div>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="" disabled selected>Select your department</option>
                            <option value="BS-Information Technology">BS-Information Technology</option>
                            <option value="BS-Civil Engineering">BS-Civil Engineering</option>
                            <option value="BS-Math">BS-Math</option>
                            <option value="BITM">BITM</option>
                        </select>
                        <div class="error-message">Department is required.</div>
                    </div>
                </div>

                <!-- Step 3: Security -->
                <div class="form-step" data-step="3">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required>
                            <svg id="togglePassword" class="toggle-password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div id="password-requirements" class="password-requirements">
                            <p>Password must contain all of the following:</p>
                            <ul>
                                <li id="length" class="invalid">Minimum 8 characters</li>
                                <li id="uppercase" class="invalid">At least 1 uppercase letter (A-Z)</li>
                                <li id="lowercase" class="invalid">At least 1 lowercase letter (a-z)</li>
                                <li id="number" class="invalid">At least 1 number (0-9)</li>
                                <li id="special" class="invalid">At least 1 special character (e.g., !, @, #, $)</li>
                            </ul>
                        </div>
                        <div class="error-message">Password is required.</div>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                            <svg id="toggleConfirmPassword" class="toggle-password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div class="error-message" id="confirmPasswordError">Passwords do not match.</div>
                    </div>
                </div>

                <div class="navigation-buttons">
                    <button type="button" class="btn btn-prev" style="display: none;">Previous</button>
                    <button type="button" class="btn btn-next">Next</button>
                </div>

                <div class="signin-prompt">
                    Already have an account? <a href="signin.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('signupForm');
            const steps = Array.from(form.querySelectorAll('.form-step'));
            const stepIndicators = Array.from(form.querySelectorAll('.step'));
            const progressBar = form.querySelector('.progress-bar');
            const nextBtn = form.querySelector('.btn-next');
            const prevBtn = form.querySelector('.btn-prev');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');

            let currentStep = 1;
            const API_ENDPOINT = '../../database/users_api.php';

            nextBtn.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < steps.length) {
                        currentStep++;
                        showStep(currentStep);
                    } else {
                        submitForm();
                    }
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            function showStep(stepNumber) {
                steps.forEach(step => {
                    step.classList.toggle('active', parseInt(step.dataset.step) === stepNumber);
                });
                
                stepIndicators.forEach(indicator => {
                    indicator.classList.toggle('active', parseInt(indicator.dataset.stepIndicator) === stepNumber);
                });

                updateProgressBar();
                updateButtons();
            }

            function updateProgressBar() {
                const totalSteps = stepIndicators.length;
                const progress = (currentStep - 1) / (totalSteps - 1) * 100;
                progressBar.style.width = `${progress}%`;
            }

            function updateButtons() {
                prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                nextBtn.textContent = currentStep === steps.length ? 'Create Account' : 'Next';
            }

            const requirements = {
                length: document.getElementById('length'),
                uppercase: document.getElementById('uppercase'),
                lowercase: document.getElementById('lowercase'),
                number: document.getElementById('number'),
                special: document.getElementById('special')
            };

            const requirementRegex = {
                length: /.{8,}/,
                uppercase: /[A-Z]/,
                lowercase: /[a-z]/,
                number: /[0-9]/,
                special: /[^A-Za-z0-9]/
            };

            passwordInput.addEventListener('input', () => {
                const password = passwordInput.value;
                let allValid = true;
                for (const key in requirements) {
                    const requirement = requirements[key];
                    const regex = requirementRegex[key];
                    if (regex.test(password)) {
                        requirement.classList.remove('invalid');
                        requirement.classList.add('valid');
                    } else {
                        requirement.classList.remove('valid');
                        requirement.classList.add('invalid');
                        allValid = false;
                    }
                }
            });

            function validateStep(stepNumber) {
                let isValid = true;
                const currentStepElement = steps[stepNumber - 1];
                const currentStepFields = currentStepElement.querySelectorAll('[required]');
                
                currentStepFields.forEach(field => {
                    field.classList.remove('invalid');
                    const formGroup = field.closest('.form-group');
                    const errorDiv = formGroup.querySelector('.error-message');
                    
                    if(errorDiv) {
                        errorDiv.style.display = 'none';
                    }

                    if (!field.value.trim()) {
                        field.classList.add('invalid');
                        if(errorDiv) {
                           errorDiv.style.display = 'block';
                        }
                        isValid = false;
                    }
                });

                if (stepNumber === 3) {
                    const password = document.getElementById('password');
                    const confirmPassword = document.getElementById('confirmPassword');
                    const errorDiv = document.getElementById('confirmPasswordError');
                    
                    confirmPassword.classList.remove('invalid');
                    errorDiv.style.display = 'none';

                    let allPasswordReqsMet = true;
                    for (const key in requirements) {
                        if (requirements[key].classList.contains('invalid')) {
                            allPasswordReqsMet = false;
                            break;
                        }
                    }

                    if (!allPasswordReqsMet) {
                        password.classList.add('invalid');
                        isValid = false;
                    }

                    if (password.value !== confirmPassword.value) {
                        confirmPassword.classList.add('invalid');
                        errorDiv.style.display = 'block';
                        isValid = false;
                    }
                }

                return isValid;
            }

            async function submitForm() {
                nextBtn.disabled = true;
                nextBtn.textContent = 'Creating Account...';

                const formData = new FormData(form);
                const middleName = formData.get('middleName') ? ` ${formData.get('middleName')} ` : ' ';
                const fullname = `${formData.get('firstName')}${middleName}${formData.get('lastName')}`;

                const userData = {
                    fullname: fullname,
                    birthdate: formData.get('birthday'),
                    id_number: formData.get('idNumber'),
                    contact_number: formData.get('contactNumber'),
                    email: formData.get('email'),
                    department: formData.get('department'),
                    password: formData.get('password')
                };

                try {
                    const response = await fetch(`${API_ENDPOINT}?action=signup`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(userData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Account created successfully!');
                        window.location.href = 'signin.php';
                    } else {
                        alert(`Sign Up Failed: ${result.message}`);
                    }

                } catch (error) {
                    console.error('Sign Up Fetch Error:', error);
                    alert('An unexpected error occurred during sign up.');
                } finally {
                    nextBtn.disabled = false;
                    updateButtons();
                }
            }

            showStep(currentStep);

            // --- Password Toggle Functionality ---
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

            const eyeIcon = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            const eyeOffIcon = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

            function toggleVisibility(input, icon) {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.innerHTML = isPassword ? eyeOffIcon : eyeIcon;
            }

            togglePassword.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
            toggleConfirmPassword.addEventListener('click', () => toggleVisibility(confirmPasswordInput, toggleConfirmPassword));
        });
    </script>
</body>
</html>