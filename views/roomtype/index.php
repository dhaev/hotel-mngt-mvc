<div class="container mt-5">
    <h2 class="text-center mb-4">Room Types</h2>
    <a href="<?= BASE_URL ?>index.php?controller=RoomType&action=add" class="btn btn-primary mb-3">Add Room Type</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room Type</th>
                <th>Price</th>
                <th>Description</th>
                <!-- <th>Image</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roomTypes as $roomType): ?>
                <tr>
                    <td><?= htmlspecialchars($roomType['RtypeID'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($roomType['rtype'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($roomType['price'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($roomType['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <!-- <td><img src="<--?= BASE_URL ?>img/rtype/<--?= htmlspecialchars($roomType['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Room Type Image" style="width: 100px;"></td> -->
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=RoomType&action=edit&id=<?= $roomType['RtypeID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
