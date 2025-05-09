<?php
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/secrets.php'; 
require_once __DIR__ . '/../helpers/DatabaseHelper.php';
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

class BookingController extends Controller {
    public function index() {
        $bookings = BookingModel::getAllBookings();
        $this->renderView('booking/index', ['bookings' => $bookings]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

            // Validate and sanitize input data
            $start_date = ValidationHelper::sanitizeInput($_POST['checkin']);
            $end_date = ValidationHelper::sanitizeInput($_POST['checkout']);
            $room = $_POST['room']; // Array of room types and quantities

            if (isset($_SESSION['email']) && isset($_SESSION['CustomerID'])) {
                $user_id = $_SESSION['CustomerID'];
            } else {


                // Sanitize and validate inputs
                $fname = ValidationHelper::sanitizeInput($_POST['fname']);
                $lname = ValidationHelper::sanitizeInput($_POST['lname']);
                $email = ValidationHelper::sanitizeInput($_POST['email']);
                $phone = ValidationHelper::sanitizeInput($_POST['phone']);

                // Insert customer into the database
                global $conn;
                $stmt = $conn->prepare("INSERT INTO customer (fname, lname, email, phone) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fname, $lname, $email, $phone);
                $stmt->execute();

                // Retrieve the newly created customer ID
                $user_id = $stmt->insert_id;

                // Close the statement
                $stmt->close();
            }

            $status = 0;
            $room = $_POST['room'];
            $total_amount = 0;
            $currency = 'usd';
            $metadata = [
                'user_id' => $user_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rooms' => json_encode($room)
            ];
            var_dump($_POST);
            // var_dump($_POST['payment_method_id']);
            try {
                $line_item_array = [];
                foreach ($room as $value) {
                    $rtype = $value['rtype'];
                    $numr = $value['numr'];
                    $room_type_details = RoomTypeModel::getRoomTypeById($rtype);
                    $pricePerRoom = $room_type_details["price"];
                    $roomtype = $room_type_details["rtype"];
            
                    $currentDate = new DateTime();
                    $checkinDate = new DateTime($start_date);
                    $checkoutDate = new DateTime($end_date);

                    $interval = $checkinDate->diff($checkoutDate);
                    $numDays = $interval->days;
            
                    $totalPrice = $pricePerRoom * $numDays * intval($numr);
                    $total_amount += $totalPrice;
                    
                    $line_item_array[] = [
                        'price_data' => [
                            'currency' => $currency,
                            'product_data' => [
                                'name' => $roomtype,
                                'description' => "$numDays day(s): $start_date to $end_date",
                            ],
                            'unit_amount' => $pricePerRoom * $numDays * 100,
                        ],
                        'quantity' => intval($numr),
                    ];
                }

                $checkout_session = $stripe->checkout->sessions->create([
                    'line_items' => $line_item_array,
                    'mode' => 'payment',
                    'success_url' => BASE_URL.'success.php',
                    'cancel_url' => BASE_URL.'cancel.php',
                    'metadata' => $metadata
                ]);

                // Return the checkout session URL to the client
                // echo json_encode(['url' => $checkout_session->url]);
                header("HTTP/1.1 303 See Other");
                header("Location: " . $checkout_session->url);

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            $conn->close();

        } else {
            $this->renderView('booking/add');
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);
            // error_log($_POST);
            $reservationId = ValidationHelper::sanitizeInput($_POST['reservation_id']);
            $checkin = ValidationHelper::sanitizeInput($_POST['checkin']);
            $checkout = ValidationHelper::sanitizeInput($_POST['checkout']);
            $rooms = $_POST['room']; // Array of room types and quantities

            global $conn;

        try {
// Update booking dates


            // Calculate the total amount previously charged
            $totalAmountPreviouslyCharged = self::calculateTotalAmount($conn, $reservationId);

            // Calculate the new total amount
            $totalAmountToCharge = 0;
            $numDays = (new DateTime($checkin))->diff(new DateTime($checkout))->days;
            error_log("Number of Days: $numDays");
            $roomTypeDetails = RoomTypeModel::getAllRoomTypes();
            $totalAmountToCharge = array_reduce($rooms, function ($carry, $room) use ($roomTypeDetails) {
                // $pricePerRoom = $roomTypeDetails[$room['rtype']]['price'] ?? 0; 
                return $carry + ($roomTypeDetails[$room['rtype']]['price'] * $room['numr']);
            }, 0);

            // Determine if additional payment or refund is needed
            $amountDifference =( $totalAmountToCharge * intval($numDays)) - $totalAmountPreviouslyCharged;
            error_log("Total Amount Previously Charged: $totalAmountPreviouslyCharged, Total Amount to Charge: $totalAmountToCharge, Amount Difference: $amountDifference");
            

            if ($amountDifference > 0) {
                // Additional payment required
                $paymentIntent = self::createPaymentIntent($amountDifference, $reservationId, $checkin, $checkout, $rooms);
                $clientSecret = $paymentIntent->client_secret;

                // Ensure the correct path to payment.php
                header('Location: ' . BASE_URL . 'views/payments.php?client_secret=' . urlencode($clientSecret) . '&pk_key=' . urlencode(STRIPE_CLIENT_API_KEY));
                exit();
            } elseif ($amountDifference < 0) {
                // Refund required
                $refundAmount = abs($amountDifference);
                self::processRefunds($conn, $reservationId, $refundAmount);
                // header('Location: ' . BASE_URL . 'index.php?controller=Booking&action=edit' . '&id=' . $reservationId);
                    // Update reservation dates
                $sql = "UPDATE hotel_management.reservations SET start_date = ?, end_date = ? WHERE id = ?";
                DatabaseHelper::executeQuery($conn, $sql, 'ssi', $start_date, $end_date, $reservation_id);


                // Update reservation details
                $sqlDeleteDetails = "DELETE FROM reservation_details WHERE reservation_id = ?";
                DatabaseHelper::executeQuery($conn, $sqlDeleteDetails, 'i', $reservation_id);

                foreach ($room as $value) {
                    $roomTypeId = $value['rtype'];
                    $numRooms = $value['numr'];
                    $sqlDetails = "INSERT INTO reservation_details (reservation_id, type_id, num_rooms) VALUES (?, ?, ?)";
                    DatabaseHelper::executeQuery($conn, $sqlDetails, 'iii', $reservation_id, $roomTypeId, $numRooms);
                }

            }
            header('Location: ' . BASE_URL . 'index.php?controller=Booking&action=index');

  
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }

        } else {
            try {
                $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
                $reservation = BookingModel::getBookingById($reservationId);
                $roomDetails = BookingModel::getBookingDetails($reservationId);
                $roomTypes = RoomTypeModel::getAllRoomTypes();
                $this->renderView('booking/edit', [
                    'reservation' => $reservation,
                    'roomDetails' => $roomDetails,
                    'roomTypes' => $roomTypes
                ]);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $reservationId = ValidationHelper::sanitizeInput($_GET['id']);
            if (BookingModel::deleteBooking($reservationId)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Booking&action=index');
            } else {
                echo 'Failed to delete booking.';
            }
        }
    }

private static function calculateTotalAmount($conn, $reservationId) {
        $sql = " CALL calculateTotalAmount(?) ";
        $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return isset($row['total_amount']) ? $row['total_amount'] : 0;
}

private static function getRoomTypeDetails($conn, $roomTypeId) {
    $sql = "SELECT * FROM room_type WHERE RtypeID = ?";
    $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $roomTypeId);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

private static function createPaymentIntent($amount, $reservationId, $checkin, $checkout, $rooms) {
    $metadata = [
        'reservation_id' => $reservationId,
        'start_date' => $checkin,
        'end_date' => $checkout,
        'rooms' => json_encode($rooms)
    ];

    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount * 100, // Stripe expects the amount in cents
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
        'metadata' => $metadata,
    ]);

    return $paymentIntent;
}

private static function processRefunds($conn, $reservationId, $refundAmount) {
    $refundableAmounts = self::fetchRefundableAmounts($conn, $reservationId);
    $remainingRefundAmount = $refundAmount * 100; // Convert to cents
    if ($refundableAmounts < $remainingRefundAmount) {
        throw new Exception("Not enough funds to refund the full amount");
    }

    foreach ($refundableAmounts as $refundable) {
        if ($remainingRefundAmount <= 0) break;

        $refundAmountForIntent = min($remainingRefundAmount, $refundable['refundable_amount'] * 100);

        $refund = \Stripe\Refund::create([
            'payment_intent' => $refundable['payment_intent'],
            'amount' => $refundAmountForIntent,
        ]);

        if ($refund->status !== 'succeeded') {
            throw new Exception("Refund failed: " . $refund->status);
        }

        $sql = "INSERT INTO refunds (payment_id, payment_intent, amount, refund_date, stripe_refund_id) VALUES (?, ?, ?, NOW(), ?)";
        DatabaseHelper::executeQuery($conn, $sql, 'isds', $refundable['id'], $refundable['payment_intent'], $refundAmountForIntent / 100, $refund->id);

        $remainingRefundAmount -= $refundAmountForIntent;
    }

    if ($remainingRefundAmount > 0) {
        throw new Exception("Not enough funds to refund the full amount");
    }
}

private static function fetchRefundableAmounts($conn, $reservationId) {
    $sql = " CALL fetchRefundableAmounts(?) ";
    $stmt = DatabaseHelper::executeQuery($conn, $sql, 'i', $reservationId);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



}
?>