<?php
class Test extends Controller {

    function __construct() {
        parent::__construct();
        Session::init();
    }

    function index() {
        $this->view->render('test/jsonDefault', 'json');
        $this->model->index();
    }
    
    function test() {
        $this->view->render('test/jsonDefault', 'json');
        $this->model->test();
    }

}
