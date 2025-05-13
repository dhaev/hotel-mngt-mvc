<?php
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/RoomModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
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
            $start_date = ValidationHelper::sanitizeInput($_POST['start_date']);
            $end_date = ValidationHelper::sanitizeInput($_POST['end_date']);
            $room = $_POST['room']; // Array of room types and quantities

            // Check for duplicate room types
            $rtypeIds = array_column($room, 'rtype');
            if (count($rtypeIds) !== count(array_unique($rtypeIds))) {
                $this->renderView('booking/add', [
                    'error' => 'Each room type can only be selected once.',
                ]);
                return;
            }

            if (isset($_SESSION['email']) && isset($_SESSION['CustomerID'])) {
                $user_id = $_SESSION['CustomerID'];
            } else {
                // Sanitize and validate inputs
                $fname = ValidationHelper::sanitizeInput($_POST['fname']);
                $lname = ValidationHelper::sanitizeInput($_POST['lname']);
                $email = ValidationHelper::sanitizeInput($_POST['email']);
                $phone = ValidationHelper::sanitizeInput($_POST['phone']);

                // Insert customer into the database
                $user_id = CustomerModel::addCustomer($fname, $lname, $email, $phone);
            }

            $status = 0;
            $total_amount = 0;
            $currency = 'usd';
            $metadata = [
                'user_id' => $user_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rooms' => json_encode($room)
            ];

            try {
                $line_item_array = [];
                foreach ($room as $value) {
                    $rtype = $value['rtype'];
                    $numr = $value['numr'];
                    $room_type_details = RoomTypeModel::getRoomTypeById($rtype);
                    $pricePerRoom = $room_type_details["price"];
                    $roomtype = $room_type_details["rtype"];

                    $checkinDate = new DateTime($start_date);
                    $checkoutDate = new DateTime($end_date);
                    $numDays = $checkinDate->diff($checkoutDate)->days;

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

                $checkout_session = $stripe->end_date->sessions->create([
                    'line_items' => $line_item_array,
                    'mode' => 'payment',
                    'success_url' => BASE_URL . 'success.php',
                    'cancel_url' => BASE_URL . 'cancel.php',
                    'metadata' => $metadata
                ]);

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
            $reservationId = ValidationHelper::sanitizeInput($_POST['reservation_id']);
            $start_date = ValidationHelper::sanitizeInput($_POST['start_date']);
            $end_date = ValidationHelper::sanitizeInput($_POST['end_date']);
            $rooms = $_POST['room']; // Array of room types and quantities

            // Check for duplicate room types
            $rtypeIds = array_column($rooms, 'rtype');
            if (count($rtypeIds) !== count(array_unique($rtypeIds))) {
                // Re-render the edit view with error
                $reservation = BookingModel::getBookingById($reservationId);
                $roomDetails = BookingModel::getBookingDetails($reservationId);
                $roomTypes = RoomTypeModel::getAllRoomTypes();
                $this->renderView('booking/edit', [
                    'reservation' => $reservation,
                    'roomDetails' => $roomDetails,
                    'roomTypes' => $roomTypes,
                    'error' => 'Each room type can only be selected once.'
                ]);
                return;
            }

            global $conn;

            try {
                $totalAmountPreviouslyCharged = BookingModel::getAmountPaid($conn, $reservationId);
                $roomTypeDetails = RoomTypeModel::getAllRoomTypes();
                $numDays = (new DateTime($start_date))->diff(new DateTime($end_date))->days;                
                $totalAmountToCharge = array_reduce($rooms, function ($carry, $room) use ($roomTypeDetails) {
                    return $carry + ($roomTypeDetails[$room['rtype']]['price'] * $room['numr']);
                }, 0);

                $amountDifference = ($totalAmountToCharge * intval($numDays)) - $totalAmountPreviouslyCharged;

                if ($amountDifference > 0) {
                    $paymentIntent = self::createPaymentIntent($amountDifference, $reservationId, $start_date, $end_date, $rooms);
                    $clientSecret = $paymentIntent->client_secret;

                    header('Location: ' . BASE_URL . 'views/payments.php?client_secret=' . urlencode($clientSecret) . '&pk_key=' . urlencode(STRIPE_CLIENT_API_KEY));
                    exit();
                } elseif ($amountDifference < 0) {
                    $refundAmount = abs($amountDifference);
                    self::processRefunds($conn, $reservationId, $refundAmount, $totalAmountPreviouslyCharged);
                    BookingModel::updateBooking($reservationId, $start_date, $end_date, $totalAmountToCharge);
                    BookingModel::deleteBookingDetails($reservationId);

                    foreach ($rooms as $value) {
                        $roomTypeId = $value['rtype'];
                        $numRooms = $value['numr'];
                        BookingModel::insertBookingDetails($reservationId, $roomTypeId, $numRooms);
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

    private static function createPaymentIntent($amount, $reservationId, $start_date, $end_date, $rooms) {
        $metadata = [
            'reservation_id' => $reservationId,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'rooms' => json_encode($rooms)
        ];

        return \Stripe\PaymentIntent::create([
            'amount' => $amount * 100, // Stripe expects the amount in cents
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => $metadata,
        ]);
    }

    private static function processRefunds($conn, $reservationId, $refundAmount, $totalAmountPreviouslyCharged) {
        $refundableAmounts = $totalAmountPreviouslyCharged;
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

            BookingModel::insertRefunds($refundable['id'], $refundable['payment_intent'], $refundAmountForIntent / 100, $refund->id);

            $remainingRefundAmount -= $refundAmountForIntent;
        }

        if ($remainingRefundAmount > 0) {
            throw new Exception("Not enough funds to refund the full amount.");
        }
    }
}
?>