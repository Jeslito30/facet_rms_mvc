<?php
class Teacher extends Controller {
    private $teacherModel;

    public function __construct() {
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
        $result = $this->teacherModel->create($data);
        echo json_encode($result);
    }

    public function update_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->teacherModel->update($data);
        echo json_encode($result);
    }

    public function delete_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->teacherModel->delete($data['id']);
        echo json_encode($result);
    }
}