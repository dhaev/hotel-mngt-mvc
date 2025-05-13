<?php
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';
require_once __DIR__ . '/../core/Controller.php';

class CustomerController extends Controller {
    public function index() {
        $customers = CustomerModel::getAllCustomers();
        $this->renderView('customer/index', ['customers' => $customers]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fname = ValidationHelper::sanitizeInput($_POST['fname']);
            $lname = ValidationHelper::sanitizeInput($_POST['lname']);
            $email = ValidationHelper::sanitizeInput($_POST['email']);
            $phone = ValidationHelper::sanitizeInput($_POST['phone']);

            if (CustomerModel::addCustomer($fname, $lname, $email, $phone)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Customer&action=index');
            } else {
                echo 'Failed to add customer.';
            }
        } else {
            $this->renderView('customer/add');
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = ValidationHelper::sanitizeInput($_POST['id']);
            $fname = ValidationHelper::sanitizeInput($_POST['fname']);
            $lname = ValidationHelper::sanitizeInput($_POST['lname']);
            $email = ValidationHelper::sanitizeInput($_POST['email']);
            $phone = ValidationHelper::sanitizeInput($_POST['phone']);
            $address = ValidationHelper::sanitizeInput($_POST['address'] ?? '');
            $country = ValidationHelper::sanitizeInput($_POST['country'] ?? '');
            $city = ValidationHelper::sanitizeInput($_POST['city'] ?? '');

            if (CustomerModel::editCustomer($id, $fname, $lname, $email, $phone, $address, $country, $city)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Customer&action=index');
            } else {
                echo 'Failed to edit customer.';
            }
        } else {
            $id = $_GET['id'];
            $customer = CustomerModel::getCustomerById($id);
            $this->renderView('customer/edit', ['customer' => $customer]);
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $id = ValidationHelper::sanitizeInput($_GET['id']);
            if (CustomerModel::deleteCustomer($id)) {
                header('Location: ' . BASE_URL . 'index.php?controller=Customer&action=index');
            } else {
                echo 'Failed to delete customer.';
            }
        }
    }
}
?>
