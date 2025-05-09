<?php
require_once __DIR__ . '/../models/CheckoutModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class CheckoutController extends Controller {
    public function index() {
        $checkouts = CheckoutModel::getAllCheckouts();
        $this->renderView('checkout/index', ['checkouts' => $checkouts]);
    }

    public function checkout() {
        if (isset($_GET['id'])) {
            $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
            if (CheckoutModel::checkoutBooking($reservationId)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Checkout&action=index');
            } else {
                echo 'Failed to check out.';
            }
        } else {
            header('Location: ' . BASE_URL . 'index.php?controller=Checkout&action=index');
        }
    }
}
?>
