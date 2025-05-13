<div class="container mt-5">
    <h2 class="text-center mb-4">Add Room</h2>
    <form action="<?= BASE_URL ?>index.php?controller=Room&action=add" method="post">
        <div class="form-group">
            <label for="rtype">Room Type</label>
            <select id="rtype" name="rtype" class="form-control" required>
                <option value="">Select Room Type</option>
                <?php foreach ($roomTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type['RtypeID'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($type['rtype'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="rnum">Room Number</label>
            <input type="text" id="rnum" name="rnum" class="form-control" placeholder="Room number..." required>
        </div>
        <button type="submit" class="btn btn-primary">Add Room</button>
    </form>
</div>
