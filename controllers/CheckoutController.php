<?php
require_once __DIR__ . '/../models/CheckoutModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class CheckoutController extends Controller {
    public function index() {
        $checkouts = CheckoutModel::getAllCheckouts();
        $this->renderView('end_date/index', ['checkouts' => $checkouts]);
    }

    public function end_date() {
        if (isset($_GET['id'])) {
            $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
            if (CheckoutModel::checkoutBooking($reservationId)) {
                header('Location: ' . BASE_URL . 'index.php?controller=end_date&action=index');
            } else {
                echo 'Failed to check out.';
            }
        } else {
            header('Location: ' . BASE_URL . 'index.php?controller=end_date&action=index');
        }
    }
}
?>

<!-- php
// Include database connection
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get reservation ID from the request
    $reservation_id = isset($_POST['reservation_id']) ? intval($_POST['reservation_id']) : null;

    if (!$reservation_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Reservation ID is required']);
        exit;
    }

    try {
        // Start database transaction
        $conn->begin_transaction();

        // Update the reservations table to mark as checked in
        $query5 = "
            UPDATE reservations 
            SET checked_out = 1, date_checked_out = NOW(), status = 1 
            WHERE id = ?
        ";
        $stmt5 = $conn->prepare($query5);
        $stmt5->bind_param('i', $reservation_id);
        $stmt5->execute();

        // Commit transaction
        $conn->commit();
        http_response_code(200);
        echo json_encode(['success' => 'check out completed']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?> -->
