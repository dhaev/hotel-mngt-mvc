<?php
require_once __DIR__ . '/config/config.php'; // Ensure config.php is loaded
require_once __DIR__ . '/helpers/AuthHelper.php';
AuthHelper::startSecureSession(); // Ensure secure session handling

try {
    require_once __DIR__ . '/views/login.php';
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>