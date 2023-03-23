<?php

class Test_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $responseArray = array('id' => 1, 'name' => 'index function of test controller');
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }
    
    function test() {
        $responseArray = array('id' => 1, 'name' => 'test function of test controller');
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

}
