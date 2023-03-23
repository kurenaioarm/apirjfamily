<?php
class Error extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->view->render('index/jsonDefault', 'json');
        $arrResponse = array();
        $arrResponse['json_result'] = false;
        $arrResponse['json_data'] = array(
            'details' => 'This page doesn\'t exist!'
        );
        echo json_encode($arrResponse, JSON_PRETTY_PRINT);
        exit();
    }

}

