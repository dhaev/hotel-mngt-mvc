<?php
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class RoomController extends Controller {
    public function index() {
        $rooms = RoomModel::getAllRooms();
        $this->renderView('room/index', ['rooms' => $rooms]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rtype = ValidationHelper::sanitizeInput($_POST['rtype']);
            $rnum = ValidationHelper::sanitizeInput($_POST['rnum']);

            if (RoomModel::addRoom($rtype, $rnum)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Room&action=index');
            } else {
                echo 'Failed to add room.';
            }
        } else {
            $roomTypes = RoomModel::getRoomTypes();
            $this->renderView('room/add', ['roomTypes' => $roomTypes]);
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = ValidationHelper::sanitizeInput($_POST['id']);
            $rtype = ValidationHelper::sanitizeInput($_POST['rtype']);
            $rnum = ValidationHelper::sanitizeInput($_POST['rnum']);

            if (RoomModel::editRoom($id, $rtype, $rnum)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Room&action=index');
            } else {
                echo 'Failed to edit room.';
            }
        } else {
            $id = $_GET['id'];
            $room = RoomModel::getRoomById($id);
            $roomTypes = RoomTypeModel::getAllRoomTypes();
            $this->renderView('room/edit', ['room' => $room, 'roomTypes' => $roomTypes]);
        }
    }

    public static function checkRoomAvailability() {
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;

        $roomAvailability = RoomModel::getRoomAvailability($start, $end);

        header('Content-Type: application/json');
        echo json_encode($roomAvailability);
    }
}
?>
