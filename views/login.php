<?php
require_once __DIR__ . '/../header.php'; // Include the header
require_once __DIR__ . '/../helpers/AuthHelper.php';
AuthHelper::startSecureSession(); // Ensure secure session handling
?>
<form action="../controllers/AuthController.php?action=login" method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <div>
        <input type="email" name="email" placeholder="Email" required>
    </div>
    <div>
        <input type="password" name="pwd" placeholder="Password" required>
    </div>
    <button type="submit" name="submit">Login</button>
</form>
