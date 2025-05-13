<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class AuthController {
    public function login() {
        AuthHelper::startSecureSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = ValidationHelper::sanitizeInput($_POST['email']);
            $pwd = ValidationHelper::sanitizeInput($_POST['pwd']);

            if (UserModel::loginUser($email, $pwd)) {
                header('Location: ../public/index.php');
            } else {
                header('Location: ../views/auth/login.php?error=invalid_credentials');
            }
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        AuthHelper::startSecureSession();
        session_unset();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        header('Location: '. __DIR__ . '/../../index.php');
    }
}
