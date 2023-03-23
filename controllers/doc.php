<?php

class Doc extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function appointment()
    {
        $this->view->render('doc/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->appointment();
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

    public function visit($hn)
    {
        $this->view->render('doc/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->visit($hn);
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

    public function doclist()
    {
        $this->view->render('doc/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                //$this->model->login();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->doclist();
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
                $this->view->render('doc/index');
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

}
