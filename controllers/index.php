<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->view->render('index/jsonDefault', 'json');
        echo 'hey';
        //$this->model->index();
    }
    
    function test() {
        $this->view->render('index/jsonDefault', 'json');
        $this->model->test();
    }

}
