<?php require_once __DIR__ . '/../../header.php'; ?>
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
            <?php foreach ($checkins as $checkin): ?>
                <tr>
                    <td><?= htmlspecialchars($checkin['BookID'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($checkin['CustomerName'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($checkin['check_in'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=Checkin&action=checkout&id=<?= $checkin['BookID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to check out?')">Check Out</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
