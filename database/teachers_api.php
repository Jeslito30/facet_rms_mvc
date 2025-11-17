<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Define the IS_API constant so our auth check knows to return JSON instead of redirecting
define('IS_API', true); 
// Include the authentication check. The path is direct as they are in the same directory.
require_once 'auth_check.php';

require_once 'db_connect.php';

header('Content-Type: application/json');

function respondWithError($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

function respondWithSuccess($data, $message = 'Success') {
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit();
}

if (!$conn || mysqli_connect_errno()) {
    respondWithError('Database connection failed');
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// --- GET ALL USERS ---
if ($method === 'GET' && $action === 'list') {
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? 'all';
    $department = $_GET['department'] ?? 'all';
    
    $sql = "SELECT id, id_number, fullname, email, 
            contact_number, birthdate, department, status, 
            profile_picture_type, created_at, updated_at 
            FROM users WHERE 1=1 AND id_number != 'A-0001'";
    
    // Add search filter
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " AND (fullname LIKE '%$search%' 
                  OR id_number LIKE '%$search%' OR email LIKE '%$search%')";
    }
    
    // Add status filter
    if ($status !== 'all') {
        $status = mysqli_real_escape_string($conn, $status);
        $sql .= " AND LOWER(status) = LOWER('$status')";
    }
    
    // Add department filter
    if ($department !== 'all') {
        $department = mysqli_real_escape_string($conn, $department);
        $sql .= " AND department LIKE '%$department%'";
    }
    
    $sql .= " ORDER BY fullname";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        respondWithError('Error fetching users: ' . mysqli_error($conn));
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    
    respondWithSuccess($users, 'Users retrieved successfully');
}

// --- GET SINGLE USER ---
if ($method === 'GET' && $action === 'get') {
    $id = $_GET['id'] ?? '';
    if (empty($id)) {
        respondWithError('User ID is required');
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    // FIXED: Fetch all necessary fields instead of just id and fullname
    $sql = "SELECT id, id_number, fullname, email, 
            contact_number, birthdate, department, status, 
            profile_picture_type, created_at, updated_at 
            FROM users WHERE id = '$id'";
    
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        respondWithError('Error fetching user: ' . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($result) === 0) {
        respondWithError('User not found');
    }
    
    $user = mysqli_fetch_assoc($result);
    
    // Ensure all fields are present (even if empty)
    $user['fullname'] = $user['fullname'] ?? '';
    $user['email'] = $user['email'] ?? '';
    $user['id_number'] = $user['id_number'] ?? '';
    $user['contact_number'] = $user['contact_number'] ?? '';
    $user['birthdate'] = $user['birthdate'] ?? '';
    $user['department'] = $user['department'] ?? '';
    $user['status'] = $user['status'] ?? 'Active';
    
    respondWithSuccess($user, 'User retrieved successfully');
}

// --- GET PROFILE PICTURE ---
if ($method === 'GET' && $action === 'picture') {
    $id = $_GET['id'] ?? '';
    if (empty($id)) {
        respondWithError('User ID is required');
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT profile_picture, profile_picture_type FROM users WHERE id = '$id'";
    
    $result = @mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) === 0) {
        respondWithError('User not found');
    }
    
    $user = mysqli_fetch_assoc($result);
    if (empty($user['profile_picture'])) {
        respondWithError('No profile picture available');
    }
    
    // Send image directly
    header('Content-Type: ' . $user['profile_picture_type']);
    echo $user['profile_picture'];
    exit();
}

// --- CREATE USER (FIXED) ---
if ($method === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['fullname']) || 
        empty($data['email']) || empty($data['idNumber'])) {
        respondWithError('Missing required fields');
    }
    
    $fullname = $data['fullname'];
    $email = $data['email'];
    $idNumber = $data['idNumber'];
    $contactNumber = $data['contactNumber'];
    $birthdate = $data['birthdate'];
    $department = $data['department'];
    $status = $data['status'] ?? 'Active';
    
    // Handle base64 profile picture
    $profilePicture = null;
    $profilePictureType = null;
    $hasPicture = false;

    if (!empty($data['profilePicture'])) {
        $base64String = $data['profilePicture'];
        
        // Extract MIME type and data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            $imageType = $matches[1];
            $profilePictureType = "image/$imageType";
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $profilePicture = base64_decode($base64String);
            $hasPicture = true; 
        }
    }
    
    if ($hasPicture) {
        $sql = "INSERT INTO users (id_number, fullname, email, 
                contact_number, birthdate, department, status, profile_picture, profile_picture_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        // SQL for no picture
        $sql = "INSERT INTO users (id_number, fullname, email, 
                contact_number, birthdate, department, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    }
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        respondWithError('Error preparing statement: ' . mysqli_error($conn));
    }
    
    // Bind parameters
    
    if ($hasPicture) {
        // Bind all parameters as strings ('s')
        mysqli_stmt_bind_param($stmt, "sssssssss", 
            $idNumber, $fullname, $email,
            $contactNumber, $birthdate, $department, $status, 
            $profilePicture, $profilePictureType 
        );
        
        // CRITICAL FIX: Use send_long_data for the LONGBLOB (profile_picture). It is the 8th parameter (index 7).
        if (!mysqli_stmt_send_long_data($stmt, 7, $profilePicture)) {
             respondWithError('Error sending profile picture data: ' . mysqli_error($conn));
        }

    } else {
        // Bind for no picture (7 's' parameters)
        mysqli_stmt_bind_param($stmt, "sssssss", 
            $idNumber, $fullname, $email,
            $contactNumber, $birthdate, $department, $status
        );
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $newId = mysqli_insert_id($conn);
        respondWithSuccess(['id' => $newId], 'User created successfully');
    } else {
        if (mysqli_errno($conn) == 1062) {
            respondWithError('Email or ID number already exists');
        } else {
            respondWithError('Error creating user: ' . mysqli_error($conn));
        }
    }
}

// --- UPDATE USER (FIXED) ---
if ($method === 'POST' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['id'])) {
        respondWithError('User ID is required');
    }
    
    $id = $data['id'];
    $fullname = $data['fullname'] ?? '';
    $email = $data['email'] ?? '';
    $idNumber = $data['idNumber'] ?? '';
    $contactNumber = $data['contactNumber'] ?? '';
    $birthdate = $data['birthdate'] ?? '';
    $department = $data['department'] ?? '';
    $status = $data['status'] ?? 'Active';
    
    // Validate required fields
    if (empty($fullname) || empty($email) || empty($idNumber)) {
        respondWithError('Missing required fields: fullname, email, or ID number');
    }
    
    $profilePicture = null;
    $profilePictureType = null;
    $hasPicture = false;

    // Check if profile picture needs updating
    if (!empty($data['profilePicture'])) {
        $base64String = $data['profilePicture'];
        
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            $imageType = $matches[1];
            $profilePictureType = "image/$imageType";
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $profilePicture = base64_decode($base64String);
            
            if ($profilePicture === false) {
                respondWithError('Invalid profile picture data');
            }
            
            $hasPicture = true;
        }
    } 
    
    if ($hasPicture) {
        $sql = "UPDATE users SET id_number=?, fullname=?, 
                email=?, contact_number=?, birthdate=?, department=?, status=?,
                profile_picture=?, profile_picture_type=? WHERE id=?";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            respondWithError('Error preparing statement: ' . mysqli_error($conn));
        }
        
        // Bind parameters - note: profile_picture is bound as a blob placeholder
        mysqli_stmt_bind_param($stmt, "ssssssssss", 
            $idNumber, $fullname, $email,
            $contactNumber, $birthdate, $department, $status,
            null, $profilePictureType, $id
        );
        
        // Send the actual blob data for profile_picture (parameter index 7)
        if (!mysqli_stmt_send_long_data($stmt, 7, $profilePicture)) {
             respondWithError('Error sending profile picture data during update: ' . mysqli_error($conn));
        }
        
    } else {
        $sql = "UPDATE users SET id_number=?, fullname=?, 
                email=?, contact_number=?, birthdate=?, department=?, status=? WHERE id=?";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            respondWithError('Error preparing statement: ' . mysqli_error($conn));
        }
        
        // Bind 8 parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", 
            $idNumber, $fullname, $email,
            $contactNumber, $birthdate, $department, $status, $id
        );
    }
    
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) >= 0) {
            respondWithSuccess(['id' => $id], 'User updated successfully');
        } else {
            respondWithError('User not found or no changes made');
        }
    } else {
        if (mysqli_errno($conn) == 1062) {
            respondWithError('Email or ID number already exists');
        } else {
            respondWithError('Error updating user: ' . mysqli_error($conn));
        }
    }
    
    mysqli_stmt_close($stmt);
    exit();
}

// --- DELETE USER ---
if ($method === 'POST' && $action === 'delete') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || empty($data['id'])) {
        respondWithError('User ID is required');
    }
    
    $id = mysqli_real_escape_string($conn, $data['id']);
    $sql = "DELETE FROM users WHERE id = '$id'";
    
    if (@mysqli_query($conn, $sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            respondWithSuccess(['id' => $id], 'User deleted successfully');
        } else {
            respondWithError('User not found');
        }
    } else {
        respondWithError('Error deleting user: ' . mysqli_error($conn));
    }
}

respondWithError('Invalid action or method');
?>