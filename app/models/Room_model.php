<?php
class Room_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function get_all() {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM rooms ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            return ['success' => false, 'message' => 'Error fetching rooms'];
        }

        $rooms = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($row['amenities'])) {
                $row['amenities'] = json_decode($row['amenities'], true);
            }
            $rooms[] = $row;
        }

        return ['success' => true, 'data' => $rooms];
    }

    public function save_room($roomData) {
        $conn = $this->db->getConnection();
        $id = isset($roomData['id']) && !empty($roomData['id']) && is_numeric($roomData['id']) ? intval($roomData['id']) : null;
        $name        = mysqli_real_escape_string($conn, $roomData['name'] ?? '');
        $description = mysqli_real_escape_string($conn, $roomData['description'] ?? '');
        $capacity    = intval($roomData['capacity'] ?? 0);
        $building    = mysqli_real_escape_string($conn, $roomData['building'] ?? '');
        $floor       = mysqli_real_escape_string($conn, $roomData['floor'] ?? '');
        $status      = mysqli_real_escape_string($conn, $roomData['status'] ?? '');
        $startTime   = mysqli_real_escape_string($conn, $roomData['startTime'] ?? '');
        $endTime     = mysqli_real_escape_string($conn, $roomData['endTime'] ?? '');
        
        $amenities = $roomData['amenities'] ?? [];
        if (!is_array($amenities)) {
            $amenities = [];
        }
        $amenities_json = mysqli_real_escape_string($conn, json_encode($amenities));

        if ($id && $id > 0) {
            $sql = "UPDATE rooms SET name = '$name', description = '$description', capacity = $capacity, 
                    building = '$building', floor = '$floor', status = '$status', startTime = '$startTime', 
                    endTime = '$endTime', amenities = '$amenities_json' WHERE id = $id";
        } else {
            $sql = "INSERT INTO rooms (name, description, capacity, building, floor, status, startTime, endTime, amenities)
                    VALUES ('$name', '$description', $capacity, '$building', '$floor', '$status', '$startTime', '$endTime', '$amenities_json')";
        }

        if (mysqli_query($conn, $sql)) {
            $new_id = $id ? $id : mysqli_insert_id($conn);
            return ['success' => true, 'message' => 'Room saved successfully', 'id' => $new_id];
        } else {
            return ['success' => false, 'message' => 'Error saving room'];
        }
    }

    public function delete_room($roomData) {
        $conn = $this->db->getConnection();
        $id = mysqli_real_escape_string($conn, $roomData['id'] ?? null);

        if (!$id) { 
            return ['success' => false, 'message' => 'Room ID is missing'];
        }

        $sql = "DELETE FROM rooms WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            return ['success' => true, 'message' => "Room with ID $id deleted successfully"];
        } else {
            return ['success' => false, 'message' => 'Error deleting room'];
        }
    }
}
