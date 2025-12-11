<?php
class Calendar extends Controller {
    public function index() {
        checkAuth();
        $this->view('calendar');
    }
}
