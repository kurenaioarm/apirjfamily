<?php

class rjdataexport_api extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->render('token/jsonDefault', 'json');
        echo json_encode(array('title' => "RJDataExport", 'value' => "RJDataExport"));
    }

    function ods_uc_permissions()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->ods_uc_permissions();
    }

    function ods_uc_permissionsV2()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->ods_uc_permissionsV2();
    }

    function check_ods_uc90()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->check_ods_uc90();
    }

    function check_service_charge()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->check_service_charge();
    }

    function check_pttype_name()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->check_pttype_name();
    }

    function icd10icd9_one()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->icd10icd9_one();
    }

    function icd10icd9_all()
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->icd10icd9_all();
    }

//    -----------------------------------------------------------------------------------------------------------------------------------------

    function data_ins()//1
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_ins();
    }

    function data_pat()//2
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_pat();
    }

    function data_opd()//3
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_opd();
    }

    function data_orf()//4
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_orf();
    }

    function data_odx()//5
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_odx();
    }

    function data_oop()//6
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_oop();
    }

    function data_ipd()//7
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_ipd();
    }

    function data_irf()//8
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_irf();
    }

    function data_idx()//9
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_idx();
    }

    function data_iop()//10
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_iop();
    }

    function data_cht()//11
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_cht();
    }

    function data_cha()//12
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_cha();
    }

    function data_aer()//13
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_aer();
    }

    function data_adp()//14
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_adp();
    }

    function data_lvd()//15
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_lvd();
    }

    function data_dru()//16
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_dru();
    }

    function data_labfu()//17
    {
        $this->view->render('token/jsonDefault', 'json');
        $this->model->data_labfu();
    }


}

