<?php
class Controller {
    public function loadModel($model) {
        require_once __DIR__ . "/../models/$model.php"; // Use absolute path
        return new $model();
    }

    public function renderView($view, $data = []) {
        // Require global dependencies for all views
        require_once __DIR__ . '/../config/config.php';
        require_once __DIR__ . '/../helpers/AuthHelper.php';
        // ...add more requires as needed...

        extract($data);
        $headerPath = __DIR__ . "/../views/header.php";
        $viewPath = __DIR__ . "/../views/$view.php";
        $footerPath = __DIR__ . "/../views/footer.php";
        if (file_exists($headerPath)) {
            require_once $headerPath;
        }
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("View file not found: $viewPath");
        }
        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }
}
?>