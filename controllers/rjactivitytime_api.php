<?php

class rjactivitytime_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "RJActivityTime", 'value' => "RJActivityTime_API"));
    }

    function timeline_event()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->timeline_event();
    }

    function timeline_date()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->timeline_date();
    }

    function event_two_year()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_two_year();
    }

    function event_date()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_date();
    }

    function event_cancel()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_cancel();
    }

    function event_update()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_update();
    }

    function event_Insert()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_Insert();
    }

    function event_all()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->event_all();
    }
}
