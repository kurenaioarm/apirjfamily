<?php

/**
 * normal
 * * fdfdsfdsfsf
 * ! sdfdsfsdf
 * ?dfsdfs
 * TODO: fdkfkdjsf
 * @param myparam is 
 */

class Vacation extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Session::init();
    }

    public function index()
    {
        $this->view->render('vacation/jsonDefault', 'json');
    }

    public function xhrVacation()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacation();
    }

    public function xhrVacation_del()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacation_del();
    }

    public function xhrVacation_chk()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacation_chk();
    }

    public function xhrVacation_app()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacation_app();
    }

    public function xhrVacation_appa()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacation_appa();
    }

    public function xhrVaca_Notify()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVaca_Notify();
    }

    public function xhrVactionList()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVactionList();
    }

    public function xhrVactionViews()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVactionViews();
    }

    public function xhrVacAction()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrVacAction();
    }

    public function xhrGetBalance()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrGetBalance();
    }

    public function xhrGetMyVaction()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrGetMyVaction();
    }

    public function xhrActVacLst()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrActVacLst();
    }

    public function xhrGetVacationOnline()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrGetVacationOnline();
    }


    // * สร้าง Method ใน controller vacation เพื่อใช้เรียกจาก API และส่ง parameter ไปยัง vacation Model 
    public function xhrGetSumVacation()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        $this->model->xhrGetSumVacation();
    }

    // * สร้าง Method xhrCalDayVacation ใน controller vacation เพื่อใช้เรียกจาก API และส่ง parameter ไปยัง vacation Model 
    public function xhrCalDayVacation()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->model->xhrCalDayVacation();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->xhrVacation_get();
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

    //! เรียกรายการคนที่ลาในวันที่ที่ส่งมา โดยธุรการ หรือ ผบ
    public function xhrVactionOnDay()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->model->xhrVactionOnDay();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
                $this->model->xhrVactionOnDay();
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

    //! บันทึกลาออนไลน์
    public function xhrVacationOnline()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->model->xhrVacationOnline();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
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
    public function xhrTestUpload()
    {
        $this->view->render('vacation/jsonDefault', 'json');
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->model->xhrTestUpload();
                break;
            case 'PUT':
                # code...
                break;
            case 'GET':
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
}
