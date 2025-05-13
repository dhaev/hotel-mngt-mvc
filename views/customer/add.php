<div class="container mt-5">
    <h2 class="text-center mb-4">Add Customer</h2>
    <form action="<?= BASE_URL ?>index.php?controller=Customer&action=add" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="form-group col-md-6">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group col-md-6">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>
</div>
