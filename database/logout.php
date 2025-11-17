 1 <?php
    // Always start the session to access it
    session_start();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the sign-in page after logout
    header('Location: ../src/pages/signin.php');
    exit;
    ?>