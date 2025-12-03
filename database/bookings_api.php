<?php
// jeslito30/facet-rms2/facet-rms2-10463fbabce2d8b54d4dec68095cbcf1225e29ee/database/bookings_api.php
define('IS_API', true); 
require_once 'auth_check.php';
require_once 'db_connect.php'; 

header('Content-Type: application/json');

function respondWithError($message) {
    global $conn;
    $error = $conn ? mysqli_error($conn) : 'No connection object';
    echo json_encode(['success' => false, 'message' => $message . " (" . $error . ")"]);
    exit();
}

function respondWithSuccess($data = [], $message = 'Success') {
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit();
}

function getJsonPayload() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}

if (mysqli_connect_errno()) {
    respondWithError("Database connection failed");
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'] ?? null;

// --- CREATE BOOKING (POST) - Sets status to 'Pending' ---
if ($method === 'POST' && $action === 'create') {
    if (!$userId) { respondWithError('Authentication required.'); }
    
    $data = getJsonPayload();
    
    if (empty($data['roomId']) || empty($data['meetingTitle']) || empty($data['date']) ||
        empty($data['startTime']) || empty($data['endTime']) || empty($data['attendees'])) {
        respondWithError('Missing required booking fields');
    }
    
    $roomId         = intval($data['roomId']);
    $meetingTitle   = mysqli_real_escape_string($conn, $data['meetingTitle']);
    $date           = mysqli_real_escape_string($conn, $data['date']);
    $startTime      = mysqli_real_escape_string($conn, $data['startTime']);
    $endTime        = mysqli_real_escape_string($conn, $data['endTime']);
    $attendees      = intval($data['attendees']);
    $recurring      = mysqli_real_escape_string($conn, $data['recurring'] ?? 'None');
    $description    = mysqli_real_escape_string($conn, $data['description'] ?? '');
    
    $sql = "INSERT INTO bookings (room_id, requestor_id, meeting_title, booking_date, start_time, end_time, attendees, recurring, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisssssss", 
        $roomId, $userId, $meetingTitle, $date, $startTime, $endTime, $attendees, $recurring, $description
    );
    
    if (mysqli_stmt_execute($stmt)) {
        respondWithSuccess(['id' => mysqli_insert_id($conn)], 'Booking request created successfully');
    } else {
        respondWithError('Error creating booking request');
    }
}

// --- READ ALL BOOKINGS (GET) ---
if ($method === 'GET' && $action === 'list') {
    $sql = "SELECT b.*, r.name AS room_name, r.building, r.floor, r.capacity, u.fullname AS requestor_name, r.image_url
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            JOIN users u ON b.requestor_id = u.id
            ORDER BY b.request_date DESC";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) { respondWithError('Error fetching bookings'); }
    
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) { $bookings[] = $row; }
    
    respondWithSuccess($bookings, 'Bookings retrieved successfully');
}

// --- READ SINGLE BOOKING (GET) ---
if ($method === 'GET' && $action === 'get') {
    $id = $_GET['id'] ?? '';
    if (empty($id)) { respondWithError('Booking ID is required'); }
    
    $sql = "SELECT b.*, r.name AS room_name, r.building, r.floor, r.capacity, u.fullname AS requestor_name, r.image_url
            FROM bookings b
            JOIN rooms r ON b.room_id = r.id
            JOIN users u ON b.requestor_id = u.id
            WHERE b.id = ?";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 0) { respondWithError('Booking not found'); }
    
    $booking = mysqli_fetch_assoc($result);
    respondWithSuccess($booking, 'Booking retrieved successfully');
}

// --- UPDATE STATUS (POST) - Approve/Reject ---
if ($method === 'POST' && ($action === 'approve' || $action === 'reject')) {
    $data = getJsonPayload();
    $id = intval($data['id'] ?? 0);
    $newStatus = ($action === 'approve') ? 'Approved' : 'Rejected';
    
    if (!$id) { respondWithError('Booking ID is required'); }

    mysqli_begin_transaction($conn);

    try {
        // 1. Update Booking Status
        $sqlBooking = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmtBooking = mysqli_prepare($conn, $sqlBooking);
        mysqli_stmt_bind_param($stmtBooking, "si", $newStatus, $id);
        mysqli_stmt_execute($stmtBooking);
        
        if (mysqli_stmt_affected_rows($stmtBooking) === 0) {
            throw new Exception("Booking not found or already in this status.");
        }

        // 2. If Approved, update Room Status to 'Occupied'
        if ($newStatus === 'Approved') {
            $sqlGetRoomId = "SELECT room_id FROM bookings WHERE id = ?";
            $stmtGetRoomId = mysqli_prepare($conn, $sqlGetRoomId);
            mysqli_stmt_bind_param($stmtGetRoomId, "i", $id);
            mysqli_stmt_execute($stmtGetRoomId);
            $result = mysqli_stmt_get_result($stmtGetRoomId);
            $row = mysqli_fetch_assoc($result);
            $roomId = $row['room_id'] ?? null;

            if (!$roomId) { throw new Exception("Room ID not found for booking."); }

            $sqlRoom = "UPDATE rooms SET status = 'Occupied' WHERE id = ?";
            $stmtRoom = mysqli_prepare($conn, $sqlRoom);
            mysqli_stmt_bind_param($stmtRoom, "i", $roomId);
            mysqli_stmt_execute($stmtRoom);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        respondWithSuccess(['id' => $id, 'status' => $newStatus], "Booking successfully $newStatus");
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        respondWithError("Failed to update booking/room: " . $e->getMessage());
    }
}

respondWithError('Invalid action or method');
?>