<?php
class User extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User_model');
    }

    public function index() {
        checkAuth();
        $this->view('my-profile');
    }

    public function login() {
        $this->view('signin');
    }

    public function signup() {
        $this->view('signup');
    }

    public function logout() {
        checkAuth();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /facet-rms/public/user/login');
    }

    public function signin_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Email and password are required']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        $result = $this->userModel->signin($data);
        echo json_encode($result);
    }

    public function signup_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['fullname']) || empty($data['email']) || empty($data['password']) || empty($data['id_number']) || empty($data['contact_number']) || empty($data['birthdate']) || empty($data['department'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        $result = $this->userModel->signup($data);
        echo json_encode($result);
    }
}