<?php

class donate_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "donate_api", 'value' => "donate_api"));
    }

    function donate_admin()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_admin();
    }

    function maxdonate_id()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->maxdonate_id();
    }

    function donate_item()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_item();
    }

    function donate_home()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_home();
    }

    function donate_insert()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_insert();
    }

    function donate_update()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_update();
    }

    function donate_confirm()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_confirm();
    }

    function donate_cu()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donate_cu();
    }

    function donateitem_insert()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donateitem_insert();
    }

    function item_insert()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->item_insert();
    }

    function donateitem_fpdf()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donateitem_fpdf();
    }

    function donateitemdt_fpdf()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->donateitemdt_fpdf();
    }
}
