<?php
class Controller {
    public function loadModel($model) {
        require_once __DIR__ . "/../models/$model.php"; // Use absolute path
        return new $model();
    }

    public function renderView($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/$view.php"; // Use absolute path
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("View file not found: $viewPath");
        }
    }
}
?>