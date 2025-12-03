<?php
class User_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function signup($data) {
        $conn = $this->db->getConnection();
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
            return ['success' => true, 'message' => 'User created successfully', 'id' => mysqli_insert_id($conn)];
        } else {
            // Check for duplicate entry error (MySQL error code 1062)
            if (mysqli_errno($conn) == 1062) {
                return ['success' => false, 'message' => 'Email or ID number already registered.'];
            } else {
                return ['success' => false, 'message' => 'Error creating user: ' . mysqli_error($conn)];
            }
        }
    }

    public function signin($data) {
        $conn = $this->db->getConnection();
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

                    return [
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
                    ];
                } else {
                    // Password incorrect
                    return ['success' => false, 'message' => 'Invalid email or password.'];
                }
            } else {
                // User not found
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }
        } else {
            return ['success' => false, 'message' => 'Database query error'];
        }
    }
}
