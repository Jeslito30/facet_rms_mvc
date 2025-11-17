<?php
    // Ensure session is started on all pages
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user_id session variable is not set
    if (!isset($_SESSION['user_id'])) {
     // This constant can be defined in your API files before including this script
        if (defined('IS_API')) {
         // For API requests, send a 401 Unauthorized status and a JSON error message
            http_response_code(401);
           header('Content-Type: application/json');
          echo json_encode([
                'success' => false,
               'message' => 'Authentication required. Please log in.'
        ]);
           exit;
       } else {
           // For regular page loads, redirect the user to the sign-in page
           // The path is relative from the 'database' directory to 'src/pages/'
           header('Location: /facet-rms/src/pages/signin.php');
           exit;
       }
    }
   ?>