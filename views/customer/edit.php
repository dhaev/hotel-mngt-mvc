<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Customer</h2>
    <form action="<?= BASE_URL ?>index.php?controller=Customer&action=edit" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($customer['CustomerID'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?= htmlspecialchars($customer['fname'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?= htmlspecialchars($customer['lname'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($customer['phone'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($customer['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" value="<?= htmlspecialchars($customer['country'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($customer['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Customer</button>
    </form>
</div>
