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
        $name        = $roomData['name'] ?? '';
        $description = $roomData['description'] ?? '';
        $capacity    = intval($roomData['capacity'] ?? 0);
        $building    = $roomData['building'] ?? '';
        $floor       = $roomData['floor'] ?? '';
        $status      = $roomData['status'] ?? '';
        $startTime   = $roomData['startTime'] ?? '';
        $endTime     = $roomData['endTime'] ?? '';
        
        $amenities = $roomData['amenities'] ?? [];
        if (!is_array($amenities)) {
            $amenities = [];
        }
        $amenities_json = json_encode($amenities);

        if ($id && $id > 0) {
            $sql = "UPDATE rooms SET name = ?, description = ?, capacity = ?, 
                    building = ?, floor = ?, status = ?, startTime = ?, 
                    endTime = ?, amenities = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssissssssi", $name, $description, $capacity, $building, $floor, $status, $startTime, $endTime, $amenities_json, $id);
        } else {
            $sql = "INSERT INTO rooms (name, description, capacity, building, floor, status, startTime, endTime, amenities)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssissssss", $name, $description, $capacity, $building, $floor, $status, $startTime, $endTime, $amenities_json);
        }

        if (mysqli_stmt_execute($stmt)) {
            $new_id = $id ? $id : mysqli_insert_id($conn);
            return ['success' => true, 'message' => 'Room saved successfully', 'id' => $new_id];
        } else {
            return ['success' => false, 'message' => 'Error saving room'];
        }
    }

    public function delete_room($roomData) {
        $conn = $this->db->getConnection();
        $id = $roomData['id'] ?? null;

        if (!$id) { 
            return ['success' => false, 'message' => 'Room ID is missing'];
        }

        $sql = "DELETE FROM rooms WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => "Room with ID $id deleted successfully"];
        } else {
            return ['success' => false, 'message' => 'Error deleting room'];
        }
    }
}
