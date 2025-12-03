<?php
class Booking_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $conn = $this->db->getConnection();
        if (empty($data['roomId']) || empty($data['meetingTitle']) || empty($data['date']) ||
            empty($data['startTime']) || empty($data['endTime']) || empty($data['attendees'])) {
            return ['success' => false, 'message' => 'Missing required booking fields'];
        }
        
        $roomId         = intval($data['roomId']);
        $meetingTitle   = mysqli_real_escape_string($conn, $data['meetingTitle']);
        $date           = mysqli_real_escape_string($conn, $data['date']);
        $startTime      = mysqli_real_escape_string($conn, $data['startTime']);
        $endTime        = mysqli_real_escape_string($conn, $data['endTime']);
        $attendees      = intval($data['attendees']);
        $recurring      = mysqli_real_escape_string($conn, $data['recurring'] ?? 'None');
        $description    = mysqli_real_escape_string($conn, $data['description'] ?? '');
        $userId = $_SESSION['user_id'] ?? null;
        
        $sql = "INSERT INTO bookings (room_id, requestor_id, meeting_title, booking_date, start_time, end_time, attendees, recurring, description, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisssssss", 
            $roomId, $userId, $meetingTitle, $date, $startTime, $endTime, $attendees, $recurring, $description
        );
        
        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'id' => mysqli_insert_id($conn), 'message' => 'Booking request created successfully'];
        } else {
            return ['success' => false, 'message' => 'Error creating booking request'];
        }
    }

    public function list() {
        $conn = $this->db->getConnection();
        $sql = "SELECT b.*, r.name AS room_name, r.building, r.floor, r.capacity, u.fullname AS requestor_name, r.image_url
                FROM bookings b
                JOIN rooms r ON b.room_id = r.id
                JOIN users u ON b.requestor_id = u.id
                ORDER BY b.request_date DESC";
        
        $result = mysqli_query($conn, $sql);
        if (!$result) { return ['success' => false, 'message' => 'Error fetching bookings']; }
        
        $bookings = [];
        while ($row = mysqli_fetch_assoc($result)) { $bookings[] = $row; }
        
        return ['success' => true, 'data' => $bookings, 'message' => 'Bookings retrieved successfully'];
    }

    public function get($id) {
        $conn = $this->db->getConnection();
        if (empty($id)) { return ['success' => false, 'message' => 'Booking ID is required']; }
        
        $sql = "SELECT b.*, r.name AS room_name, r.building, r.floor, r.capacity, u.fullname AS requestor_name, r.image_url
                FROM bookings b
                JOIN rooms r ON b.room_id = r.id
                JOIN users u ON b.requestor_id = u.id
                WHERE b.id = ?";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 0) { return ['success' => false, 'message' => 'Booking not found']; }
        
        $booking = mysqli_fetch_assoc($result);
        return ['success' => true, 'data' => $booking, 'message' => 'Booking retrieved successfully'];
    }

    public function approve($id) {
        return $this->updateStatus($id, 'Approved');
    }

    public function reject($id) {
        return $this->updateStatus($id, 'Rejected');
    }

    private function updateStatus($id, $newStatus) {
        $conn = $this->db->getConnection();
        if (!$id) { return ['success' => false, 'message' => 'Booking ID is required']; }

        mysqli_begin_transaction($conn);

        try {
            $sqlBooking = "UPDATE bookings SET status = ? WHERE id = ?";
            $stmtBooking = mysqli_prepare($conn, $sqlBooking);
            mysqli_stmt_bind_param($stmtBooking, "si", $newStatus, $id);
            mysqli_stmt_execute($stmtBooking);
            
            if (mysqli_stmt_affected_rows($stmtBooking) === 0) {
                throw new Exception("Booking not found or already in this status.");
            }

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
            
            mysqli_commit($conn);
            return ['success' => true, 'id' => $id, 'status' => $newStatus, 'message' => "Booking successfully $newStatus"];
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            return ['success' => false, 'message' => "Failed to update booking/room: " . $e->getMessage()];
        }
    }
}
