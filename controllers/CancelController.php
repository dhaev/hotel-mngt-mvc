<?php
require_once __DIR__ . '/../models/CancelModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class CancelController extends Controller {
    public function index() {
        $cancellations = CancelModel::getAllCancellations();
        $this->renderView('cancel/index', ['cancellations' => $cancellations]);
    }

    public function cancel() {
        if (isset($_GET['id'])) {
            $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
            if (CancelModel::cancelBooking($reservationId)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Cancel&action=index');
            } else {
                echo 'Failed to cancel booking.';
            }
        } else {
            header('Location: ' . BASE_URL . 'index.php?controller=Cancel&action=index');
        }
    }
}
?>
