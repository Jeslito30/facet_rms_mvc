<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// At the top of users_api.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php'; // Your database connection




header('Content-Type: application/json');

// Helper function for error handling and JSON response
function dieWithError($conn, $message) {
    $error = $conn ? mysqli_error($conn) : 'No connection object';
    echo json_encode(['success' => false, 'message' => $message . " (" . $error . ")"]);
    exit();
}

function getJsonPayload() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}

// Check for connection error
if (!$conn || mysqli_connect_errno()) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// --- SIGN UP (POST) - Register a new user ---
if ($method === 'POST' && $action === 'signup') {
    $data = getJsonPayload();
    
    if (!$data || empty($data['fullname']) || empty($data['email']) || empty($data['password']) || empty($data['id_number']) || empty($data['contact_number']) || empty($data['birthdate']) || empty($data['department'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $fullname = $data['fullname'];
    $email = $data['email'];
    $password = $data['password'];
    $id_number = $data['id_number'];
    $contact_number = $data['contact_number'];
    $birthdate = $data['birthdate'];
    $department = $data['department'];

    // Hash the password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the users table using prepared statements
    $sql = "INSERT INTO users (fullname, email, password_hash, id_number, contact_number, birthdate, department) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $fullname, $email, $password_hash, $id_number, $contact_number, $birthdate, $department);

    // Execute query and check for errors
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true, 
            'message' => 'User created successfully', 
            'id' => mysqli_insert_id($conn)
        ]);
    } else {
        // Check for duplicate entry error (MySQL error code 1062)
        if (mysqli_errno($conn) == 1062) {
            echo json_encode(['success' => false, 'message' => 'Email or ID number already registered.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error creating user: ' . mysqli_error($conn)]);
        }
    }
    mysqli_stmt_close($stmt);
    exit();
}

// --- SIGN IN (POST) - Authenticate a user ---
if ($method === 'POST' && $action === 'signin') {
    $data = getJsonPayload();

    if (!$data || empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $email = $data['email'];
    $password = $data['password'];

    // Fetch user by email using prepared statements
    $sql = "SELECT id, fullname, password_hash, id_number, contact_number, birthdate, department, role, status FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify the submitted password against the hash
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct - Sign in successful
                
                // Start the session and store user data
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role'];


                echo json_encode([
                    'success' => true, 
                    'message' => 'Sign in successful',
                    'user' => [
                        'id' => $user['id'],
                        'fullname' => $user['fullname'],
                        'email' => $email,
                        'id_number' => $user['id_number'],
                        'contact_number' => $user['contact_number'],
                        'birthdate' => $user['birthdate'],
                        'department' => $user['department'],
                        'role' => $user['role'],
                        'status' => $user['status']
                    ]
                ]);
            } else {
                // Password incorrect
                echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            }
        } else {
            // User not found
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query error']);
    }
    mysqli_stmt_close($stmt);
    exit();
}

// If no action matched, return error
echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit();

// --- PROTECTED AREA ---
// Any actions below this point will require authentication.
define('IS_API', true);
require_once 'auth_check.php';

// Example of a protected action (you can add more)
// if ($method === 'GET' && $action === 'get_current_user') {
//     // The user is authenticated, you can access $_SESSION['user_id']
//     $userId = $_SESSION['user_id'];
//     // Fetch and return user data...
// }
?>