<?php
// Define the IS_API constant so our auth check knows to return JSON instead of redirecting
define('IS_API', true); 
// Include the authentication check. The path is direct as they are in the same directory.
require_once 'auth_check.php';

// 1. Include the database connection file (correct path as both are in the /database/ folder)
require_once 'db_connect.php'; 

// Set the response header to JSON
header('Content-Type: application/json');

// Helper function for error handling and JSON response
function dieWithError($conn, $message) {
    // Get the database error if connection is available
    $error = $conn ? mysqli_error($conn) : 'No connection object';
    echo json_encode(['success' => false, 'message' => $message . " (" . $error . ")"]);
    exit();
}

// Helper function to get JSON data from the request body
function getJsonPayload() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}

// Check for connection error
if (mysqli_connect_errno()) {
    dieWithError(null, "Database connection failed");
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// --- READ (GET) - Fetch all rooms ---
if ($method === 'GET' && $action === 'get_all') {
    // NOTE: Replace 'rooms' with your actual table name if different
    $sql = "SELECT * FROM rooms ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        dieWithError($conn, "Error fetching rooms");
    }

    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Decode the JSON string from the 'amenities' column back into a PHP array/object
        if (isset($row['amenities'])) {
            $row['amenities'] = json_decode($row['amenities'], true);
        }
        $rooms[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $rooms]);
    mysqli_close($conn);
    exit();
}

// --- CREATE/UPDATE (POST) - Save a room ---
if ($method === 'POST' && $action === 'save_room') {
    $roomData = getJsonPayload();
    if (!$roomData) { 
        error_log("ERROR: Invalid JSON data received");
        dieWithError(null, "Invalid data received"); 
    }

    // Log received data for debugging
    error_log("Received room data: " . json_encode($roomData));

    // Validate required fields
    if (empty($roomData['name']) || empty($roomData['description']) || empty($roomData['capacity']) || 
        empty($roomData['building']) || empty($roomData['floor']) || empty($roomData['status']) ||
        empty($roomData['startTime']) || empty($roomData['endTime'])) {
        dieWithError(null, "Missing required fields");
    }

    // Extract and sanitize data
    $id = isset($roomData['id']) && !empty($roomData['id']) && is_numeric($roomData['id']) ? intval($roomData['id']) : null;
    $name        = mysqli_real_escape_string($conn, $roomData['name'] ?? '');
    $description = mysqli_real_escape_string($conn, $roomData['description'] ?? '');
    $capacity    = intval($roomData['capacity'] ?? 0);
    $building    = mysqli_real_escape_string($conn, $roomData['building'] ?? '');
    $floor       = mysqli_real_escape_string($conn, $roomData['floor'] ?? '');
    $status      = mysqli_real_escape_string($conn, $roomData['status'] ?? '');
    $startTime   = mysqli_real_escape_string($conn, $roomData['startTime'] ?? '');
    $endTime     = mysqli_real_escape_string($conn, $roomData['endTime'] ?? '');
    
    // Handle amenities - ensure it's always a valid JSON string
    $amenities = $roomData['amenities'] ?? [];
    if (!is_array($amenities)) {
        $amenities = [];
    }
    $amenities_json = mysqli_real_escape_string($conn, json_encode($amenities));

    if ($id && $id > 0) {
        // UPDATE existing room
        $sql = "UPDATE rooms SET name = '$name', description = '$description', capacity = $capacity, 
                building = '$building', floor = '$floor', status = '$status', startTime = '$startTime', 
                endTime = '$endTime', amenities = '$amenities_json' WHERE id = $id";
        error_log("UPDATE query: $sql");
    } else {
        // INSERT new room
        $sql = "INSERT INTO rooms (name, description, capacity, building, floor, status, startTime, endTime, amenities)
                VALUES ('$name', '$description', $capacity, '$building', '$floor', '$status', '$startTime', '$endTime', '$amenities_json')";
        error_log("INSERT query: $sql");
    }

    if (mysqli_query($conn, $sql)) {
        $new_id = $id ? $id : mysqli_insert_id($conn);
        error_log("SUCCESS: Room saved with ID $new_id");
        echo json_encode(['success' => true, 'message' => 'Room saved successfully', 'id' => $new_id]);
        mysqli_close($conn);
        exit();
    } else {
        $error = mysqli_error($conn);
        error_log("SQL FAIL: $sql | ERROR: $error");
        dieWithError($conn, "Error saving room");
    }
}

// --- DELETE (POST) - Delete a room ---
if ($method === 'POST' && $action === 'delete_room') {
    $roomData = getJsonPayload();
    $id = mysqli_real_escape_string($conn, $roomData['id'] ?? null);

    if (!$id) { dieWithError(null, "Room ID is missing"); }

    $sql = "DELETE FROM rooms WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => "Room with ID $id deleted successfully"]);
        mysqli_close($conn);
        exit();
    } else {
        dieWithError($conn, "Error deleting room");
    }
}

// Handle invalid action/request
if (!($method === 'GET' && $action === 'get_all') && !($method === 'POST' && ($action === 'save_room' || $action === 'delete_room'))) {
    echo json_encode(['success' => false, 'message' => 'Invalid request or action']);
    mysqli_close($conn);
    exit();
}
?>