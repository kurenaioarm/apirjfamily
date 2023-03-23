<?php

class medicalrecord_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "medicalrecord_api", 'value' => "medicalrecord_api"));
    }

    function medicalrecord_admin() // เช็คเAdmin
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->medicalrecord_admin();
    }

    function medr_access_ip() // เช็คเข้าใช้งาน IP
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->medr_access_ip();
    }

    function check_children() // เช็คมีเด็กกี่คน
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->check_children();
    }

    function data_children() // ข้อมูลเด็ก
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_children();
    }

    function data_mthds() // โรคประจำตัวของมารดา
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_mthds();
    }

    function data_previousOB() // previous_OB
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_previousOB();
    }

    function data_presentOB() // present_OB (ภาวะแทรกซ้อนขณะตั้งครรภ์)
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_presentOB();
    }

    function data_brthsignmed() // ยาที่มารดารับประทานขณะตั้งครรภ์
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_brthsignmed();
    }

    function data_labmom() // ข้อมูลการฝากครรภ์ บันทึกผลทางห้องปฏิบัติการ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_labmom();
    }

    function data_dlvstdtapgar() // ประเมินสภาวะทารก
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_dlvstdtapgar();
    }


    function data_labmom2() // บันทึกการคลอด ข้อมูล lab แม่ BNZTHN
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_labmom2();
    }

}
