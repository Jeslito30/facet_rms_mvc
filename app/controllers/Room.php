<?php
class Room extends Controller {
    private $roomModel;

    public function __construct() {
        checkAuth();
        $this->roomModel = $this->model('Room_model');
    }

    public function index() {
        $this->view('rooms');
    }

    public function get_all_api() {
        $result = $this->roomModel->get_all();
        echo json_encode($result);
    }

    public function save_room_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || empty($data['capacity']) || empty($data['building']) || empty($data['floor'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Missing required room fields']);
            return;
        }

        $result = $this->roomModel->save_room($data);
        echo json_encode($result);
    }

    public function delete_room_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid room ID']);
            return;
        }

        $result = $this->roomModel->delete_room($data);
        echo json_encode($result);
    }
}