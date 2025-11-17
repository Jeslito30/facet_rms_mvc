<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$db_server = "localhost";
$db_user = "wb_user";
$db_pass = "1234";
$db_name = "facet_rms";

// Create connection
try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception(mysqli_connect_error());
    }
} catch (Exception $e) {
    // If connection fails, return a JSON error message
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit();
}
?>