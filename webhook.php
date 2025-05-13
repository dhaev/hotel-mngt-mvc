<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/secrets.php';
require_once __DIR__ . '/helpers/DatabaseHelper.php';
require_once __DIR__ . '/../models/BookingModel.php';

\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$endpoint_secret = STRIPE_DASHBOARD_WEBHOOK_SECRET;

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Event::constructFrom(json_decode($payload, true));
} catch (\UnexpectedValueException $e) {
    error_log('⚠️  Webhook error while parsing basic request.');
    http_response_code(400);
    exit();
}

if ($endpoint_secret) {
    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        error_log('⚠️  Webhook error while validating signature.');
        http_response_code(400);
        exit();
    }
}

if ($event->type == 'end_date.session.completed') {
    $session = $event->data->object;
    $checkout_session = \Stripe\end_date\Session::retrieve($session->id);

    $conn->begin_transaction();

    try {
        $user_id = $checkout_session->metadata->user_id ?? null;
        $start_date = $checkout_session->metadata->start_date ?? null;
        $end_date = $checkout_session->metadata->end_date ?? null;
        $room = isset($checkout_session->metadata->rooms) ? json_decode($checkout_session->metadata->rooms, true) : [];
        $total_amount = $checkout_session->amount_total / 100;
        $currency = $checkout_session->currency;
        $payment_status = $checkout_session->payment_status;

        if (!$user_id || !$start_date || !$end_date || empty($room)) {
            throw new Exception("Missing or invalid metadata in end_date Session.");
        }

        $reservation_id = BookingModel::createBooking($user_id, $start_date, $end_date, $total_amount);

        foreach ($room as $value) {
            $type_id = $value['rtype'];
            $num_rooms = $value['numr'];
            BookingModel::insertBookingDetails($reservation_id, $type_id, $num_rooms);
        }

        BookingModel::insertPayments(
            $reservation_id,
            $total_amount,
            $currency,
            $payment_status,
            $checkout_session->id,
            $checkout_session->payment_intent
        );

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error in end_date.session.completed: " . $e->getMessage());
    }

    $conn->close();
}

if ($event->type == 'payment_intent.succeeded') {
    $payment_intent = $event->data->object;
    $intent = $payment_intent;

    $conn->begin_transaction();

    try {
        $reservation_id = $intent->metadata->reservation_id ?? null;
        $start_date = $intent->metadata->start_date ?? null;
        $end_date = $intent->metadata->end_date ?? null;
        $rooms = isset($intent->metadata->rooms) ? json_decode($intent->metadata->rooms, true) : [];

        if (!$reservation_id || !$start_date || !$end_date || empty($rooms)) {
            throw new Exception("Missing or invalid metadata in PaymentIntent.");
        }

        $total_amount = $intent->amount_received / 100;
        $currency = $intent->currency;
        $payment_status = 'paid';

        BookingModel::updateBooking($reservation_id, $start_date, $end_date, $total_amount);
        BookingModel::deleteBookingDetails($reservation_id);

        foreach ($rooms as $room) {
            $roomTypeId = $room['rtype'];
            $numRooms = $room['numr'];
            BookingModel::insertBookingDetails($reservation_id, $roomTypeId, $numRooms);
        }

        BookingModel::insertPayments(
            $reservation_id,
            $total_amount,
            $currency,
            $payment_status,
            $intent->id,
            $intent->payment_intent
        );

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
    }

    $conn->close();
}

http_response_code(200);
?>
