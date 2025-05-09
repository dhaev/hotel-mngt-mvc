<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/DatabaseHelper.php'; // Include DatabaseHelper

class CheckinModel {
    public static function getAllCheckins() {
        global $conn;
        $sql = "SELECT r.id AS BookID, 
                       CONCAT(c.fname, ' ', c.lname) AS CustomerName, 
                       r.start_date AS check_in 
                FROM reservations r 
                INNER JOIN customer c ON r.user_id = c.CustomerID 
                WHERE r.checked_in = 1 AND r.checked_out = 0";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, null);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function checkinBooking($reservationId) {
        global $conn;
        $sql = "UPDATE reservations SET checked_in = 1, date_checked_in = NOW() WHERE id = ?";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
        return $stmt ? true : false;
    }
}
?>
