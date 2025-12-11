<?php
function checkAuth() {
    // Ensure session is started on all pages
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user_id session variable is not set
    if (!isset($_SESSION['user_id'])) {
     // This constant can be defined in your API files before including this script
        if (defined('IS_API')) {
            showError('Authentication required. Please log in.', 401);
       } else {
           redirect('/facet-rms/public/user/login');
       }
    }
}
