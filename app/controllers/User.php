<?php
class User extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User_model');
    }

    public function index() {
        $this->view('my-profile');
    }

    public function login() {
        $this->view('signin');
    }

    public function signup() {
        $this->view('signup');
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /facet-rms/public/user/login');
    }

    public function signin_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->userModel->signin($data);
        echo json_encode($result);
    }

    public function signup_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->userModel->signup($data);
        echo json_encode($result);
    }
}