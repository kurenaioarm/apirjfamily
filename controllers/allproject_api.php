<?php

class allproject_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "allproject_api", 'value' => "allproject_api"));
    }

    function pname_api()//คำนำหน้าชื่อ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->pname_api();
    }

    function pname2_api()//คำนำหน้าชื่อ แบบระบุ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->pname2_api();
    }

    function province_area_api()//พื้นที่ ตำบล/แขวง , อำเภอ/เขต , จังหวัด , รหัสไปรษณีย์
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->province_area_api();
    }
    
    function staff_name()//นำรหัส มา ชื่อ staff
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->staff_name();
    }

    function agency_name()//นำรหัส มา เช็คหน่วยงาน
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->agency_name();
    }


}
