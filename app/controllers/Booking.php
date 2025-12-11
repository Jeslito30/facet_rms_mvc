<?php
class Booking extends Controller {
    private $bookingModel;

    public function __construct() {
        checkAuth();
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

        if (empty($data['roomId']) || empty($data['meetingTitle']) || empty($data['date']) ||
            empty($data['startTime']) || empty($data['endTime']) || empty($data['attendees'])) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Missing required booking fields']);
            return;
        }

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

        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
            return;
        }

        $result = $this->bookingModel->approve($data['id']);
        echo json_encode($result);
    }

    public function reject_api() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
            return;
        }

        $result = $this->bookingModel->reject($data['id']);
        echo json_encode($result);
    }
}