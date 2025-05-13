<?php
class Router {
    public function dispatch() {
        $controllerName = $_GET['controller'] ?? 'Home';
        $actionName = $_GET['action'] ?? 'index';

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = BASE_PATH . "controllers/$controllerClass.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerClass();

            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                echo "Action '$actionName' not found in controller '$controllerClass'.";
            }
        } else {
            echo "Controller '$controllerClass' not found.";
        }
    }
}
?>