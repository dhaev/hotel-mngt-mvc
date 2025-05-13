<?php
require_once __DIR__ . '/../config/config.php'; // Correct path to config.php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class SignupController {
    public function signup() {
        AuthHelper::startSecureSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = ValidationHelper::sanitizeInput($_POST['uname']);
            $email = ValidationHelper::sanitizeInput($_POST['email']);
            $pwd = ValidationHelper::sanitizeInput($_POST['pwd']);
            $rpwd = ValidationHelper::sanitizeInput($_POST['rpwd']);
            $image = 'img/default.png';

            $errors = [];

            if (ValidationHelper::signupEmpty($name, $email, $pwd, $rpwd)) {
                $errors['general'] = 'Please fill all fields.';
            }
            if (ValidationHelper::invalidEmail($email)) {
                $errors['email'] = 'Invalid email format.';
            }
            if (ValidationHelper::pwdMatch($pwd, $rpwd)) {
                $errors['rpwd'] = 'Passwords do not match.';
            }
            if (!ValidationHelper::enforcePasswordPolicy($pwd)) {
                $errors['pwd'] = 'Password must be at least 8 characters long and include at least one letter and one number.';
            }
            if (ValidationHelper::validUsername($name)) {
                $errors['uname'] = 'Username can only contain alphanumeric characters.';
            }
            if (UserModel::unameExists($name)) {
                $errors['uname'] = 'Username already exists.';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: ../views/auth/signup.php');
                exit();
            }

            UserModel::createUser($name, $email, $pwd, $image);
            session_regenerate_id(true);

            header('Location: ../public/index.php?success=signup');
        } else {
            require_once __DIR__ . '/../views/auth/signup.php';
        }
    }
}
