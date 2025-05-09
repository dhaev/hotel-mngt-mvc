<?php
require_once __DIR__ . '/../models/CheckinModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class CheckinController extends Controller {
    public function index() {
        $checkins = CheckinModel::getAllCheckins();
        $this->renderView('checkin/index', ['checkins' => $checkins]);
    }

    public function checkin() {
        if (isset($_GET['id'])) {
            $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
            if (CheckinModel::checkinBooking($reservationId)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Checkin&action=index');
            } else {
                echo 'Failed to check in.';
            }
        } else {
            header('Location: ' . BASE_URL . 'index.php?controller=Checkin&action=index');
        }
    }
}
?>
