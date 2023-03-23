<?php
class stack extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "Stack", 'value' => "Stack"));
    }

    function stack()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_allproject();
    }

}