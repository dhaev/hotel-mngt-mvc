<div class="container mt-5">
    <h2 class="text-center mb-4">Check-Ins</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Check-In Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checkins as $start_date): ?>
                <tr>
                    <td><?= htmlspecialchars($start_date['BookID'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($start_date['CustomerName'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($start_date['check_in'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=start_date&action=end_date&id=<?= $start_date['BookID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to check out?')">Check Out</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
