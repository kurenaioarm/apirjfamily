<?php

class get_token extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "test", 'value' => "tttt"));
    }

    function gettoken()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->gettoken_RJFamily();
        //echo json_encode(array('title' => "gettoken", 'value' => "gettoken_test"));
    }

}
