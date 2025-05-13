<?php
require_once __DIR__ . '/../config/config.php';
class AuthHelper {
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // Temporarily disable domain configuration
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Use HTTPS if available
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();

            // Generate CSRF token if not already set
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }
    }
}
