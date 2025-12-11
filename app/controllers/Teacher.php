<?php
class Teacher extends Controller {
    private $teacherModel;

    public function __construct() {
        checkAuth();
        $this->teacherModel = $this->model('Teacher_model');
    }

    public function index() {
        $this->view('teachers');
    }

    public function details() {
        $this->view('teacher-details');
    }

    public function list_api() {
        $result = $this->teacherModel->list($_GET);
        echo json_encode($result);
    }

    public function get_api($id) {
        $result = $this->teacherModel->get($id);
        echo json_encode($result);
    }

    public function picture_api($id) {
        $result = $this->teacherModel->get_picture($id);
        if ($result['success']) {
            header('Content-Type: ' . $result['data']['profile_picture_type']);
            echo $result['data']['profile_picture'];
        } else {
            echo json_encode($result);
        }
    }

    public function create_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['fullname']) || empty($data['email']) || empty($data['idNumber'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        $result = $this->teacherModel->create($data);
        echo json_encode($result);
    }

    public function update_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || empty($data['fullname']) || empty($data['email']) || empty($data['idNumber'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        if (!filter_var($data['id'], FILTER_VALIDATE_INT)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            return;
        }

        $result = $this->teacherModel->update($data);
        echo json_encode($result);
    }

    public function delete_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            return;
        }

        $result = $this->teacherModel->delete($data['id']);
        echo json_encode($result);
    }
}