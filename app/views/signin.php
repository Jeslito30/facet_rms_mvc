<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>FaCET-RMS</title>
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/facet-logo.jpg">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/signin.css">
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
                <h2>Streamline Your<br>Room Reservations</h2>
                <p>Efficient room management and booking made simple. Access your workspace anytime, anywhere.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M2 12h20"/>
                            </svg>
                        </div>
                        <span>Real-time availability tracking</span>
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
                        <span>Easy scheduling and management</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span>Secure and reliable platform</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Sign In Form -->
        <div class="form-section">
            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to manage your room bookings</p>
            </div>

            <form id="signinForm">
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="password" placeholder="Enter your password" required>
                        <svg class="toggle-password" id="togglePassword" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="signin-btn">Sign In</button>

                <div class="divider">or</div>

                <div class="signup-prompt">
                    Don't have an account? <a href="<?php echo BASE_URL; ?>/user/signup">Sign Up</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            
            // Change icon
            if (type === 'text') {
                this.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                this.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        });

        document.getElementById('signinForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your sign-in logic here
            console.log('Sign in attempted');
        });
    </script>
     <script src="<?php echo BASE_URL; ?>/js/signin.js"></script>
</body>
</html>