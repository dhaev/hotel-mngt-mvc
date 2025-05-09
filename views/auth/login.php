<?php
require_once BASE_PATH .'shared/header.php';
?>
<form action="../public/index.php?controller=Auth&action=login" method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="pwd" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php
require_once __DIR__ . '/../shared/footer.php';
?>
