<?php
class stack_hr extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "Stack_hr", 'value' => "Stack_hr"));
    }

    function stack_hr_distinct()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_hr_distinct();
    }

    function stack_hr_sum()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_hr_sum();
    }

    function stack_hr_day_distinct()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_hr_day_distinct();
    }

    function stack_hr_day_sum()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->stack_hr_day_sum();
    }

    function from_the_beginning()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->from_the_beginning();
    }
}