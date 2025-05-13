<?php
require_once 'config/config.php';
require_once 'helpers/AuthHelper.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';

AuthHelper::startSecureSession();

try {
    $router = new Router();
    $router->dispatch();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
