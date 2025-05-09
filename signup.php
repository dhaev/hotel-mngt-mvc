<?php
require_once 'controllers/SignupController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new SignupController();
    $controller->handleSignup($_POST);
} else {
    header('Location: views/auth/signup.php');
    exit();
}