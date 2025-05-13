<div class="container mt-5">
    <h2 class="text-center mb-4">Bookings</h2>
    <a href="<?= BASE_URL ?>index.php?controller=Booking&action=add" class="btn btn-primary mb-3">Add Booking</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($booking['fname'] . ' ' . $booking['lname'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($booking['check_in'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($booking['check_out'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=Booking&action=edit&id=<?= $booking['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= BASE_URL ?>index.php?controller=start_date&action=start_date&id=<?= $booking['id'] ?>" class="btn btn-success btn-sm">Check In</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Booking&action=delete&id=<?= $booking['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Cancel</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
