<?php

class rjhcr_api extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "RJHCR", 'value' => "RJHCR_API"));
    }

    function checkup_queue()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_queue();
        //echo json_encode(array('title' => "RJHCR", 'value' => "RJHCR_API"));
    }

    function checkup_labgroup()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_labgroup();
    }

    function checkup_hnall()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_hnall();
    }

    function checkup_hnalllabgroup()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_hnalllabgroup();
    }

    function checkup_lab()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_lab();
    }

    function checkup_lrfrlct()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->checkup_lrfrlct();
    }

    function stack_download()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_download();
    }
}
