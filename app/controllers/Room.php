<?php
class Room extends Controller {
    private $roomModel;

    public function __construct() {
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
        $result = $this->roomModel->save_room($data);
        echo json_encode($result);
    }

    public function delete_room_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->roomModel->delete_room($data);
        echo json_encode($result);
    }
}