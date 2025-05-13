<div class="container mt-5">
    <h2 class="text-center mb-4">Customers</h2>
    <a href="<?= BASE_URL ?>index.php?controller=Customer&action=add" class="btn btn-primary mb-3">Add Customer</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['CustomerID'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['fname'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['lname'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>index.php?controller=Customer&action=edit&id=<?= $customer['CustomerID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= BASE_URL ?>index.php?controller=Customer&action=delete&id=<?= $customer['CustomerID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
