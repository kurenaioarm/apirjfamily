<?php

class er_esi extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "ER", 'value' => "ER_ESI"));
    }

    function er_esi()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->er_esi();
    }

}
