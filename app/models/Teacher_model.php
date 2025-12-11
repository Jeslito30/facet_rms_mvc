<?php
class Teacher_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function list($params) {
        $conn = $this->db->getConnection();
        $search = $params['search'] ?? '';
        $status = $params['status'] ?? 'all';
        $department = $params['department'] ?? 'all';
        
        $sql = "SELECT id, id_number, fullname, email, 
                contact_number, birthdate, department, status, 
                profile_picture_type, created_at, updated_at 
                FROM users WHERE 1=1 AND id_number != 'A-0001'";
        
        $types = "";
        $values = [];

        if (!empty($search)) {
            $sql .= " AND (fullname LIKE ? OR id_number LIKE ? OR email LIKE ?)";
            $searchTerm = "%{$search}%";
            $types .= "sss";
            $values[] = $searchTerm;
            $values[] = $searchTerm;
            $values[] = $searchTerm;
        }
        
        if ($status !== 'all') {
            $sql .= " AND LOWER(status) = LOWER(?)";
            $types .= "s";
            $values[] = $status;
        }
        
        if ($department !== 'all') {
            $sql .= " AND department LIKE ?";
            $departmentTerm = "%{$department}%";
            $types .= "s";
            $values[] = $departmentTerm;
        }
        
        $sql .= " ORDER BY fullname";

        $stmt = mysqli_prepare($conn, $sql);

        if (!empty($types)) {
            mysqli_stmt_bind_param($stmt, $types, ...$values);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            return ['success' => false, 'message' => 'Error fetching users: ' . mysqli_error($conn)];
        }
        
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        
        return ['success' => true, 'data' => $users, 'message' => 'Users retrieved successfully'];
    }

    public function get($id) {
        $conn = $this->db->getConnection();
        if (empty($id)) {
            return ['success' => false, 'message' => 'User ID is required'];
        }
        
        $sql = "SELECT id, id_number, fullname, email, 
                contact_number, birthdate, department, status, 
                profile_picture_type, created_at, updated_at 
                FROM users WHERE id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (!$result) {
            return ['success' => false, 'message' => 'Error fetching user: ' . mysqli_error($conn)];
        }
        
        if (mysqli_num_rows($result) === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user = mysqli_fetch_assoc($result);
        
        $user['fullname'] = $user['fullname'] ?? '';
        $user['email'] = $user['email'] ?? '';
        $user['id_number'] = $user['id_number'] ?? '';
        $user['contact_number'] = $user['contact_number'] ?? '';
        $user['birthdate'] = $user['birthdate'] ?? '';
        $user['department'] = $user['department'] ?? '';
        $user['status'] = $user['status'] ?? 'Active';
        
        return ['success' => true, 'data' => $user, 'message' => 'User retrieved successfully'];
    }

    public function get_picture($id) {
        $conn = $this->db->getConnection();
        if (empty($id)) {
            return ['success' => false, 'message' => 'User ID is required'];
        }
        
        $sql = "SELECT profile_picture, profile_picture_type FROM users WHERE id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }
        
        $user = mysqli_fetch_assoc($result);
        if (empty($user['profile_picture'])) {
            return ['success' => false, 'message' => 'No profile picture available'];
        }
        
        return ['success' => true, 'data' => $user];
    }

    public function create($data) {
        $conn = $this->db->getConnection();
        
        $fullname = $data['fullname'];
        $email = $data['email'];
        $idNumber = $data['idNumber'];
        $contactNumber = $data['contactNumber'] ?? null;
        $birthdate = $data['birthdate'] ?? null;
        $department = $data['department'] ?? null;
        $status = $data['status'] ?? 'Active';
        
        $profilePicture = null;
        $profilePictureType = null;

        if (!empty($data['profilePicture'])) {
            $base64String = $data['profilePicture'];
            
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                $imageType = $matches[1];
                $profilePictureType = "image/$imageType";
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
                $profilePicture = base64_decode($base64String);
            }
        }
        
        $sql = "INSERT INTO users (id_number, fullname, email, contact_number, birthdate, department, status, profile_picture, profile_picture_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Error preparing statement: ' . mysqli_error($conn)];
        }
        
        mysqli_stmt_bind_param($stmt, "sssssssss", 
            $idNumber, $fullname, $email,
            $contactNumber, $birthdate, $department, $status, 
            $profilePicture, $profilePictureType 
        );

        if (mysqli_stmt_execute($stmt)) {
            $newId = mysqli_insert_id($conn);
            return ['success' => true, 'id' => $newId, 'message' => 'User created successfully'];
        } else {
            if (mysqli_errno($conn) == 1062) {
                return ['success' => false, 'message' => 'Email or ID number already exists'];
            } else {
                return ['success' => false, 'message' => 'Error creating user: ' . mysqli_error($conn)];
            }
        }
    }

    public function update($data) {
        $conn = $this->db->getConnection();
        
        $id = $data['id'];
        $fullname = $data['fullname'];
        $email = $data['email'];
        $idNumber = $data['idNumber'];
        $contactNumber = $data['contactNumber'] ?? null;
        $birthdate = $data['birthdate'] ?? null;
        $department = $data['department'] ?? null;
        $status = $data['status'] ?? 'Active';
        
        $profilePicture = null;
        $profilePictureType = null;
        $hasPicture = false;

        if (!empty($data['profilePicture'])) {
            $base64String = $data['profilePicture'];
            
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                $imageType = $matches[1];
                $profilePictureType = "image/$imageType";
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
                $profilePicture = base64_decode($base64String);
                
                if ($profilePicture !== false) {
                    $hasPicture = true;
                }
            }
        } 
        
        if ($hasPicture) {
            $sql = "UPDATE users SET id_number=?, fullname=?, 
                    email=?, contact_number=?, birthdate=?, department=?, status=?,
                    profile_picture=?, profile_picture_type=? WHERE id=?";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            if (!$stmt) {
                return ['success' => false, 'message' => 'Error preparing statement: ' . mysqli_error($conn)];
            }
            
            mysqli_stmt_bind_param($stmt, "sssssssssi", 
                $idNumber, $fullname, $email,
                $contactNumber, $birthdate, $department, $status,
                $profilePicture, $profilePictureType, $id
            );
            
        } else {
            $sql = "UPDATE users SET id_number=?, fullname=?, 
                    email=?, contact_number=?, birthdate=?, department=?, status=? WHERE id=?";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            if (!$stmt) {
                return ['success' => false, 'message' => 'Error preparing statement: ' . mysqli_error($conn)];
            }
            
            mysqli_stmt_bind_param($stmt, "sssssssi", 
                $idNumber, $fullname, $email,
                $contactNumber, $birthdate, $department, $status, $id
            );
        }
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) >= 0) {
                return ['success' => true, 'id' => $id, 'message' => 'User updated successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found or no changes made'];
            }
        } else {
            if (mysqli_errno($conn) == 1062) {
                return ['success' => false, 'message' => 'Email or ID number already exists'];
            } else {
                return ['success' => false, 'message' => 'Error updating user: ' . mysqli_error($conn)];
            }
        }
    }

    public function delete($id) {
        $conn = $this->db->getConnection();
        if (empty($id)) {
            return ['success' => false, 'message' => 'User ID is required'];
        }
        
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return ['success' => true, 'id' => $id, 'message' => 'User deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'User not found'];
            }
        } else {
            return ['success' => false, 'message' => 'Error deleting user: ' . mysqli_error($conn)];
        }
    }
}
