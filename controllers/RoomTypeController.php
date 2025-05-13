<?php
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class RoomTypeController extends Controller {
    public function index() {
        $roomTypes = RoomTypeModel::getAllRoomTypes();
        $this->renderView('roomtype/index', ['roomTypes' => $roomTypes]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rtype = ValidationHelper::sanitizeInput($_POST['rtype']);
            $price = ValidationHelper::sanitizeInput($_POST['price']);
            $desc = ValidationHelper::sanitizeInput($_POST['desc']);
            $image = $_FILES['file'];

            $errors = [];

            // Validate inputs
            if (empty($rtype) || empty($price) || empty($desc) || empty($image['name'])) {
                $errors[] = 'All fields are required.';
            }
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Price must be a positive number.';
            }
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                $errors[] = 'Invalid image type. Only JPG, JPEG, and PNG are allowed.';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=add');
                exit();
            }

            // Ensure the directory exists
            $uploadDir = __DIR__ . '/../public/img/rtype/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Process the image upload
            $imagePath = 'img/rtype/' . uniqid() . '.' . $fileExtension;
            if (!move_uploaded_file($image['tmp_name'], $uploadDir . basename($imagePath))) {
                $_SESSION['errors'] = ['Failed to upload image.'];
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=add');
                exit();
            }

            // Add room type
            if (RoomTypeModel::addRoomType($rtype, $price, $desc, $imagePath)) {
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=index');
            } else {
                $_SESSION['errors'] = ['Failed to add room type.'];
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=add');
            }
        } else {
            $this->renderView('roomtype/add');
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = ValidationHelper::sanitizeInput($_POST['rtid']);
            $rtype = ValidationHelper::sanitizeInput($_POST['rtype']);
            $price = ValidationHelper::sanitizeInput($_POST['price']);
            $desc = ValidationHelper::sanitizeInput($_POST['desc']);
            $image = $_FILES['file'];

            $errors = [];

            // Validate inputs
            if (empty($rtype) || empty($price) || empty($desc)) {
                $errors[] = 'All fields are required.';
            }
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Price must be a positive number.';
            }

            // Ensure the directory exists
            $uploadDir = __DIR__ . '/../public/img/rtype/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Handle optional image upload
            $imagePath = null;
            if (!empty($image['name'])) {
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = 'Invalid image type. Only JPG, JPEG, and PNG are allowed.';
                } else {
                    $imagePath = 'img/rtype/' . uniqid() . '.' . $fileExtension;
                    if (!move_uploaded_file($image['tmp_name'], $uploadDir . basename($imagePath))) {
                        $errors[] = 'Failed to upload image.';
                    }
                }
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=edit&id=' . $id);
                exit();
            }

            // Edit room type
            if (RoomTypeModel::editRoomType($id, $rtype, $price, $desc, $imagePath)) {
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=index');
            } else {
                $_SESSION['errors'] = ['Failed to edit room type.'];
                header('Location: ' . BASE_URL . 'index.php?controller=RoomType&action=edit&id=' . $id);
            }
        } else {
            $id = $_GET['id'];
            $roomType = RoomTypeModel::getRoomTypeById($id);
            $this->renderView('roomtype/edit', ['roomType' => $roomType]);
        }
    }
}
?>
