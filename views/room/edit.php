<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Room</h2>
    <form action="<?= BASE_URL ?>index.php?controller=Room&action=edit" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($room['RoomID'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-group">
            <label for="rtype">Room Type</label>
            <select id="rtype" name="rtype" class="form-control" required>
                <option value="">Select Room Type</option>
                <?php foreach ($roomTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type['RtypeID'], ENT_QUOTES, 'UTF-8') ?>" 
                        <?= $type['RtypeID'] == $room['RtypeID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['rtype'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="rnum">Room Number</label>
            <input type="text" id="rnum" name="rnum" class="form-control" value="<?= htmlspecialchars($room['rnum'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Room</button>
    </form>
</div>
