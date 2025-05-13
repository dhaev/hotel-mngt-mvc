<?php
AuthHelper::startSecureSession(); // Ensure secure session handling
?>
<form action="../../controllers/SignupController.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div>
        <input type="text" name="uname" placeholder="Username" required>
        <?php if (isset($_SESSION['errors']['uname'])): ?>
            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['uname'], ENT_QUOTES, 'UTF-8') ?></small>
        <?php endif; ?>
    </div>

    <div>
        <input type="email" name="email" placeholder="Email" required>
        <?php if (isset($_SESSION['errors']['email'])): ?>
            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['email'], ENT_QUOTES, 'UTF-8') ?></small>
        <?php endif; ?>
    </div>

    <div>
        <input type="password" name="pwd" placeholder="Password" required pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}">
        <?php if (isset($_SESSION['errors']['pwd'])): ?>
            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['pwd'], ENT_QUOTES, 'UTF-8') ?></small>
        <?php endif; ?>
    </div>

    <div>
        <input type="password" name="rpwd" placeholder="Repeat Password" required>
        <?php if (isset($_SESSION['errors']['rpwd'])): ?>
            <small class="text-danger"><?= htmlspecialchars($_SESSION['errors']['rpwd'], ENT_QUOTES, 'UTF-8') ?></small>
        <?php endif; ?>
    </div>

    <button type="submit" name="submit">Sign Up</button>
</form>

<?php
// Clear errors after displaying them
unset($_SESSION['errors']);
?>
