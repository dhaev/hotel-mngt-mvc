<?php
class ExampleController extends Controller {
    public function index() {
        $model = $this->loadModel('ExampleModel');
        $data = $model->getData();
        $this->renderView('exampleView', $data);
    }
}
?>
