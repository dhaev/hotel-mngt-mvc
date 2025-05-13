<?php
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';
class HomeController extends Controller {
    // public function index() {
    //     // Load the home view
    //     require_once __DIR__ . '/../views/home.php';
    // }
    public function index() {
        $roomTypes = RoomTypeModel::getAllRoomTypes();
        $this->renderView('home', ['roomTypes' => $roomTypes]);
    }
}
