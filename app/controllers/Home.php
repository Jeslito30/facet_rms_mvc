<?php
class Home extends Controller {
    public function index() {
        checkAuth();
        $this->view('index');
    }
}
