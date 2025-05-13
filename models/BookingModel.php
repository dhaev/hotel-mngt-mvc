<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/secrets.php';
require_once __DIR__ . '/../helpers/DatabaseHelper.php';

class BookingModel {
    public static function getAllBookings() {
        global $conn;
        $sql = "SELECT r.id, c.fname, c.lname, r.start_date AS check_in, r.end_date AS check_out 
                FROM reservations r 
                INNER JOIN customer c ON r.user_id = c.CustomerID";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, null);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function getBookingById($id) {
        global $conn;
        $sql = "SELECT id, DATE(`start_date`) AS start_date, DATE(`end_date`) AS end_date, DATEDIFF(`end_date`, `start_date`) AS num_days 
                FROM `reservations` 
                WHERE id = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $id);
        $result = mysqli_stmt_get_result($stmt);
        echo json_encode($result);
        return mysqli_fetch_assoc($result);
    }

    public static function getBookingDetails($id) {
        global $conn;
        $sql = "WITH get_details AS (
                    SELECT id, type_id, num_rooms 
                    FROM `reservation_details` 
                    WHERE reservation_id = ?
                )
                SELECT id, type_id, num_rooms, price, (num_rooms * price) AS sub_total, rtype 
                FROM get_details 
                JOIN room_type ON type_id = RtypeID";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $id);
        $result = mysqli_stmt_get_result($stmt);
        echo json_encode($result);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function createBooking($userId, $start_date, $end_date, $total_amount) {
        global $conn;
        $sql = "INSERT INTO reservations (user_id, start_date, end_date, total_amount) VALUES (?, ?, ?, ?)";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'issi', $userId, $start_date, $end_date, $total_amount);
        $reservationId = mysqli_insert_id($conn);
        return $reservationId;
    }

    public static function updateBooking($reservationId, $start_date, $end_date, $total_amount) {
        global $conn;
        $sql = "UPDATE reservations SET start_date = ?, end_date = ?, total_amount = ? WHERE id = ?";
        DatabaseHelper::executeQuery($conn, $sql, 'ssdi', $start_date, $end_date, $total_amount, $reservationId);
    }

    public static function deleteBooking($id) {
        global $conn;
        $sql = "DELETE FROM reservations WHERE id = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $id);
        return $stmt;
    }

    public static function insertBookingDetails($reservationId, $roomTypeId, $numRooms) {
        global $conn;
        $sqlDetails = "INSERT INTO reservation_details (reservation_id, type_id, num_rooms) VALUES (?, ?, ?)";
        DatabaseHelper::executeQuery($conn, $sqlDetails, 'iii', $reservationId, $roomTypeId, $numRooms);
        return $stmt;
    }

    public static function deleteBookingDetails($reservationId) {
        global $conn;
        $sqlDeleteDetails = "DELETE FROM reservation_details WHERE reservation_id = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sqlDeleteDetails, 'i', $reservationId);
        return $stmt;
    }

    public static function getAmountPaid($conn, $reservationId) {
        $sql = "SELECT total_amount FROM reservations WHERE id = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public static function getRoomTypeDetails($conn, $roomTypeId) {
        $sql = "SELECT * FROM room_type WHERE RtypeID = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $roomTypeId);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public static function insertRefunds($refundable, $payment_intent, $refundAmountForIntent, $refundId) {
        global $conn;
        $sql = "INSERT INTO refunds (payment_id, payment_intent, amount, refund_date, stripe_refund_id) VALUES (?, ?, ?, NOW(), ?)";
        DatabaseHelper::executeQuery($conn, $sql, 'isds', $refundable, $payment_intent, $refundAmountForIntent, $refundId);
    }

    public static function insertPayments($reservation_id, $total_amount, $currency, $payment_status, $charge_id, $charge_payment_intent) {
        $sql = "INSERT INTO payments (reservation_id, amount, currency, payment_status, payment_date, stripe_payment_id, payment_intent) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, "idssss", $reservation_id, $total_amount, $currency, $payment_status, $charge_id, $charge_payment_intent);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function calculateTotalAmount($conn, $reservationId) {
        $sql = "CALL calculateTotalAmount(?)";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return isset($row['total_amount']) ? $row['total_amount'] : 0;
    }

    private static function fetchRefundableAmounts($conn, $reservationId) {
        $sql = "CALL fetchRefundableAmounts(?)";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>