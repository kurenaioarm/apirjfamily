<?php

class Token extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function get_access_token()
    {
        $this->view->render('token/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->login();
                break;
            case 'PATCH':
                # code...
                break;
            case 'DELETE':
                # code...
                break;
            default:
                # code...
                break;
        }
    }

    public function chkToken()
    {
        $this->view->render('token/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->chkToken();
                break;
            case 'PATCH':
                # code...
                break;
            case 'DELETE':
                # code...
                break;
            default:
                # code...
                break;
        }
    }

    function index() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->view->render('token/index');
                //$this->model->xhrVactionOnDay();
                break;
            case 'PATCH':
                # code...
                break;
            case 'DELETE':
                # code...
                break;
            default:
                # code...
                break;
        }
       
    }
    
    function run() {
        $this->model->run();
    }

}
