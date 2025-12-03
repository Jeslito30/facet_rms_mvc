<?php
class Booking extends Controller {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = $this->model('Booking_model');
    }

    public function index() {
        $this->view('bookings');
    }

    public function details() {
        $this->view('booking-details');
    }

    public function create_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->bookingModel->create($data);
        echo json_encode($result);
    }

    public function list_api() {
        $result = $this->bookingModel->list();
        echo json_encode($result);
    }

    public function get_api($id) {
        $result = $this->bookingModel->get($id);
        echo json_encode($result);
    }

    public function approve_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->bookingModel->approve($data['id']);
        echo json_encode($result);
    }

    public function reject_api() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->bookingModel->reject($data['id']);
        echo json_encode($result);
    }
}