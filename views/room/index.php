<div class="container mt-5">
    <h2 class="text-center mb-4">Rooms</h2>
    <a href="<?= BASE_URL ?>index.php?controller=Room&action=add" class="btn btn-primary mb-3">Add Room</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?= htmlspecialchars($room['RoomID'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($room['rnum'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($room['rtype'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=Room&action=edit&id=<?= $room['RoomID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
