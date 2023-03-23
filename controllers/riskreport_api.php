<?php

class riskreport_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "RiskReport", 'value' => "RiskReport_API"));
    }

    function riskreport_admin()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_admin();
    }
    //    ----------------------------------------------------------------------
    function riskreport_rminformtype() //ประเภทเรื่องที่แจ้ง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rminformtype();
    }
    //    ----------------------------------------------------------------------
    function riskreport_rmlct() //หน่วยงานที่เกิดเหตุ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmlct();
    }
    function check_rmlct() //check หน่วยงานที่เกิดเหตุ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->check_rmlct();
    }
    function riskreport_rmplace() //สถานที่ที่เกิดเหตุ
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmplace();
    }
    //    ----------------------------------------------------------------------
    function riskreport_rmgrp() //ประเภทความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmgrp();
    }
    function riskreport_rmtypegrp() //เรื่องความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmtypegrp();
    }
    function riskreport_rmtype() //รายการความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmtype();
    }
    function riskreport_rmleveldtl() //ระดับความรุนแรง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmleveldtl();
    }
    //    ----------------------------------------------------------------------
    function riskreport_rmgroup() //รายละเอียดความเสี่ยงทั้งหมด ใช้บันทึกค่าที่เลือก
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->riskreport_rmgroup();
    }
    //    ----------------------------------------------------------------------
    function switch_rmgrp() //switch ประเภทความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->switch_rmgrp();
    }
    function switch_rmtypegrp() //switch เรื่องความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->switch_rmtypegrp();
    }
    function switch_rmtype() //switch รายการความเสี่ยง
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->switch_rmtype();
    }
}
