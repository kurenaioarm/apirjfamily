<?php
class riskreport_api_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function riskreport_admin()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $admin_id = $_POST['ADMIN_ID'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select riskreport_admin. *  from webintra.riskreport_admin where ADMIN_ID = :ADMINID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':ADMINID' => $admin_id));

            if ($result) {
                $dataset = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function riskreport_rminformtype() //ประเภทเรื่องที่แจ้ง
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if($_POST['INFORMTYPE_ID'] == ""){
                $Where_INFORMTYPE = "";
                $informtype_id = "";
            }else{
                $Where_INFORMTYPE = "and INFORMTYPE = :INFORMTYPEID";
                $informtype_id = $_POST['INFORMTYPE_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select INFORMTYPE , cast(NAME AS varchar2(2000)) as name from rjvt.rminformtype where CANCELDATE is null $Where_INFORMTYPE";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['INFORMTYPE_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':INFORMTYPEID' => $informtype_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();
            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function riskreport_rmlct() //check หน่วยงานที่เกิดเหตุ โดยยึด RMLCT
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if($_POST['RMLCT_ID'] == ""){
                $Where_RMLCT = "";
                $rmlct_id = "";
            }else{
                $Where_RMLCT = "and RMLCT = :RMLCTID";
                $rmlct_id = $_POST['RMLCT_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select RMLCT , cast(NAME AS varchar2(2000)) as name , RMPLACE from rjvt.rmlct where CANCELDATE is null $Where_RMLCT";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['RMLCT_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':RMLCTID' => $rmlct_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();
            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function check_rmlct()  //check หน่วยงานที่เกิดเหตุ โดยยึด RMPLACE
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if( $_POST['RMPLACE_ID'] == ""){
                $Where_RMPLACE = "";
                $rmplace_id = "";
            }else{
                $Where_RMPLACE = "and RMPLACE = :RMPLACEID";
                $rmplace_id = $_POST['RMPLACE_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select RMLCT , cast(NAME AS varchar2(2000)) as name , RMPLACE from rjvt.rmlct where CANCELDATE is null $Where_RMPLACE";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['RMPLACE_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':RMPLACEID' => $rmplace_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();
            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function riskreport_rmplace() //check สถานที่เกิดเหตุ โดยยึด RMPLACE
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if( $_POST['RMPLACE_ID'] == ""){
                $Where_RMPLACE = "";
                $rmplace_id = "";
            }else{
                $Where_RMPLACE = "and RMPLACE = :RMPLACEID";
                $rmplace_id = $_POST['RMPLACE_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select RMPLACE , cast(NAME AS varchar2(2000)) as name from rjvt.rmplace where CANCELDATE is null $Where_RMPLACE";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['RMPLACE_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':RMPLACEID' => $rmplace_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function riskreport_rmgrp() //ประเภทความเสี่ยง RMGRP
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select rmgrp.rmgrp,  cast(rmgrp.name AS varchar2(2000)) as namegrp, rmtypegrp.rmtypegrp, cast(rmtypegrp.name AS varchar2(2000)) as nametypegrp,
                            rmtype.rmtype, cast(rmtype.name AS varchar2(2000)) as nametype , rmtype.varcode
                            from rjvt.rmgrp
                            left outer join rjvt.rmtypegrp
                                 on rmgrp.rmgrp = rmtypegrp.rmgrp
                                 and  rmtypegrp.canceldate is null
                            
                            left outer join rjvt.rmtype
                                 on rmtypegrp.rmtypegrp = rmtype.rmtypegrp
                                 and rmtype.canceldate is null
                            
                            where rmgrp.canceldate is null and rmtypegrp.name LIKE '%$search_ntg%' and rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by rmgrp.rmgrp, rmtypegrp.rmtypegrp, rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                //-------------------------------------- Step1 หาค่าที่ไม่ซ้ำ  --------------------------------------
                $RMGRP_Array = array();
                foreach ($dataset_all as $dataset_RMGRP_Array){
                    array_push($RMGRP_Array,$dataset_RMGRP_Array["RMGRP"]);
                }
                $OneOnly_RMGRP = array_unique($RMGRP_Array);  //ดึงค่าที่ไม่ซ้ำกันออกมาแสดง
                //-------------------------------------- Step2 หา RMGRPDATA --------------------------------------
                $RMGRP_DATA = array();
                $Count_RMGRP_Array = count($RMGRP_Array);
                for ($a = 0; $a < $Count_RMGRP_Array; $a++) {
                    if(isset($OneOnly_RMGRP[$a])){
                        array_push($RMGRP_DATA,[
                            'RMGRP' => $dataset_all[$a]["RMGRP"],
                            'NAMEGRP' => $dataset_all[$a]["NAMEGRP"]
                        ]);
                    }
                }

                $responseArray['json_total'] =  count($RMGRP_DATA);
                $responseArray['json_data'] =  $RMGRP_DATA;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function riskreport_rmtypegrp() //เรื่องความเสี่ยง RMTYPEGRP
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select rmgrp.rmgrp,  cast(rmgrp.name AS varchar2(2000)) as namegrp, rmtypegrp.rmtypegrp, cast(rmtypegrp.name AS varchar2(2000)) as nametypegrp,
                            rmtype.rmtype, cast(rmtype.name AS varchar2(2000)) as nametype , rmtype.varcode
                            from rjvt.rmgrp
                            left outer join rjvt.rmtypegrp
                                 on rmgrp.rmgrp = rmtypegrp.rmgrp
                                 and  rmtypegrp.canceldate is null
                            
                            left outer join rjvt.rmtype
                                 on rmtypegrp.rmtypegrp = rmtype.rmtypegrp
                                 and rmtype.canceldate is null
                            
                            where rmgrp.canceldate is null and rmtypegrp.name LIKE '%$search_ntg%' and rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by rmgrp.rmgrp, rmtypegrp.rmtypegrp, rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                //-------------------------------------- Step1 หาค่าที่ไม่ซ้ำ  --------------------------------------
                $RMTYPEGRP_Array = array();
                foreach ($dataset_all as $dataset_RMTYPEGRP_Array){
                    array_push($RMTYPEGRP_Array,$dataset_RMTYPEGRP_Array["RMTYPEGRP"]);
                }
                $OneOnly_RMTYPEGRP = array_unique($RMTYPEGRP_Array);  //ดึงค่าที่ไม่ซ้ำกันออกมาแสดง
                //-------------------------------------- Step2 หา RMGRPDATA --------------------------------------
                $RMTYPEGRP_DATA = array();
                $Count_RMTYPEGRP_Array = count($RMTYPEGRP_Array);
                for ($b = 0; $b < $Count_RMTYPEGRP_Array; $b++) {
                    if(isset($OneOnly_RMTYPEGRP[$b])){
                        array_push($RMTYPEGRP_DATA,[
                            'RMGRP' => $dataset_all[$b]["RMGRP"],
                            'RMTYPEGRP' => $dataset_all[$b]["RMTYPEGRP"],
                            'NAMETYPEGRP' => $dataset_all[$b]["NAMETYPEGRP"]
                        ]);
                    }
                }

                $responseArray['json_total'] =  count($RMTYPEGRP_DATA);
                $responseArray['json_data'] =  $RMTYPEGRP_DATA;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function riskreport_rmtype() //รายการความเสี่ยง RMTYPE
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select rmgrp.rmgrp,  cast(rmgrp.name AS varchar2(2000)) as namegrp, rmtypegrp.rmtypegrp, cast(rmtypegrp.name AS varchar2(2000)) as nametypegrp,
                            rmtype.rmtype, cast(rmtype.name AS varchar2(2000)) as nametype , rmtype.varcode, rmtype.rmleveldtl, rmtype.centinial
                            from rjvt.rmgrp
                            left outer join rjvt.rmtypegrp
                                 on rmgrp.rmgrp = rmtypegrp.rmgrp
                                 and  rmtypegrp.canceldate is null
                            
                            left outer join rjvt.rmtype
                                 on rmtypegrp.rmtypegrp = rmtype.rmtypegrp
                                 and rmtype.canceldate is null
                            
                            where rmgrp.canceldate is null and rmtypegrp.name LIKE '%$search_ntg%' and rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by rmgrp.rmgrp, rmtypegrp.rmtypegrp, rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset_all);
                $responseArray['json_data'] =  $dataset_all;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function riskreport_rmgroup() //รายละเอียดความเสี่ยงทั้งหมด ใช้บันทึกค่าที่เลือก
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if( $_POST['RMTYPE_ID'] == ""){
                $Where_RMTYPE = "";
                $rmtype_id = "";
            }else{
                $Where_RMTYPE = "and RMTYPE = :RMTYPEID";
                $rmtype_id = $_POST['RMTYPE_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select rmgrp.rmgrp,  cast(rmgrp.name AS varchar2(2000)) as namegrp, rmtypegrp.rmtypegrp, cast(rmtypegrp.name AS varchar2(2000)) as nametypegrp,
                            rmtype.rmtype, cast(rmtype.name AS varchar2(2000)) as nametype, rmtype.varcode, rmtype.rmleveldtl, rmtype.centinial
                            from rjvt.rmgrp
                            left outer join rjvt.rmtypegrp
                                 on rmgrp.rmgrp = rmtypegrp.rmgrp
                                 and  rmtypegrp.canceldate is null
                            
                            left outer join rjvt.rmtype
                                 on rmtypegrp.rmtypegrp = rmtype.rmtypegrp
                                 and rmtype.canceldate is null
                            
                            where rmgrp.canceldate is null $Where_RMTYPE
                            order by rmgrp.rmgrp, rmtypegrp.rmtypegrp, rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['RMTYPE_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':RMTYPEID' => $rmtype_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();

                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }

    public function riskreport_rmleveldtl () //ระดับความรุนแรง
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            if( $_POST['RMLEVELDTL_ID'] == ""){
                $Where_RMLEVELDTL = "";
                $rmleveldtl_id = "";
            }else{
                $Where_RMLEVELDTL = "and RMLEVELDTL = :RMLEVELDTL";
                $rmleveldtl_id = $_POST['RMLEVELDTL_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select RMLEVELDTL , RMLEVEL , cast(NAME AS varchar2(2000)) as name, cast(FREQDTL AS varchar2(2000)) as freqdtl from rjvt.RMLEVELDTL where CANCELDATE is null $Where_RMLEVELDTL";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            if( $_POST['RMLEVELDTL_ID'] == ""){
                $result = $objQuery->execute();
            }else{
                $result = $objQuery->execute(array(':RMLEVELDTL' => $rmleveldtl_id));
            }

            if ($result) {
                $dataset = $objQuery->fetchAll();

                $responseArray['json_total'] =  count($dataset);
                $responseArray['json_data'] =  $dataset;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function switch_rmgrp() //switch ประเภทความเสี่ยง RMGRP
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select risk_rmgrp.rmgrp,  cast(risk_rmgrp.name AS varchar2(2000)) as namegrp, risk_rmgrp.canceldate as crmgrp, 
                               risk_rmtypegrp.rmtypegrp, cast(risk_rmtypegrp.name AS varchar2(2000)) as nametypegrp, risk_rmtypegrp.canceldate as crmtypegrp,
                               risk_rmtype.rmtype, cast(risk_rmtype.name AS varchar2(2000)) as nametype , risk_rmtype.varcode,risk_rmtype.canceldate as crmtype
       
                            from webintra.risk_rmgrp
                            left outer join webintra.risk_rmtypegrp
                                 on risk_rmgrp.rmgrp = risk_rmtypegrp.rmgrp
                            
                            left outer join webintra.risk_rmtype
                                 on risk_rmtypegrp.rmtypegrp = risk_rmtype.rmtypegrp
                            
                            where risk_rmtypegrp.name LIKE '%$search_ntg%' and risk_rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by risk_rmgrp.rmgrp, risk_rmtypegrp.rmtypegrp, risk_rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                //-------------------------------------- Step1 หาค่าที่ไม่ซ้ำ  --------------------------------------
                $RMGRP_Array = array();
                foreach ($dataset_all as $dataset_RMGRP_Array){
                    array_push($RMGRP_Array,$dataset_RMGRP_Array["RMGRP"]);
                }
                $OneOnly_RMGRP = array_unique($RMGRP_Array);  //ดึงค่าที่ไม่ซ้ำกันออกมาแสดง
                //-------------------------------------- Step2 หา RMGRPDATA --------------------------------------
                $RMGRP_DATA = array();
                $Count_RMGRP_Array = count($RMGRP_Array);
                for ($a = 0; $a < $Count_RMGRP_Array; $a++) {
                    if(isset($OneOnly_RMGRP[$a])){
                        array_push($RMGRP_DATA,[
                            'RMGRP' => $dataset_all[$a]["RMGRP"],
                            'NAMEGRP' => $dataset_all[$a]["NAMEGRP"],
                            'CRMGRP' => $dataset_all[$a]["CRMGRP"]
                        ]);
                    }
                }

                $responseArray['json_total'] =  count($RMGRP_DATA);
                $responseArray['json_data'] =  $RMGRP_DATA;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function switch_rmtypegrp() //switch เรื่องความเสี่ยง RMTYPEGRP
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select risk_rmgrp.rmgrp,  cast(risk_rmgrp.name AS varchar2(2000)) as namegrp, risk_rmgrp.canceldate as crmgrp, 
                               risk_rmtypegrp.rmtypegrp, cast(risk_rmtypegrp.name AS varchar2(2000)) as nametypegrp, risk_rmtypegrp.canceldate as crmtypegrp,
                               risk_rmtype.rmtype, cast(risk_rmtype.name AS varchar2(2000)) as nametype , risk_rmtype.varcode,risk_rmtype.canceldate as crmtype
       
                            from webintra.risk_rmgrp
                            left outer join webintra.risk_rmtypegrp
                                 on risk_rmgrp.rmgrp = risk_rmtypegrp.rmgrp
                            
                            left outer join webintra.risk_rmtype
                                 on risk_rmtypegrp.rmtypegrp = risk_rmtype.rmtypegrp
                            
                            where risk_rmtypegrp.name LIKE '%$search_ntg%' and risk_rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by risk_rmgrp.rmgrp, risk_rmtypegrp.rmtypegrp, risk_rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                //-------------------------------------- Step1 หาค่าที่ไม่ซ้ำ  --------------------------------------
                $RMTYPEGRP_Array = array();
                foreach ($dataset_all as $dataset_RMTYPEGRP_Array){
                    array_push($RMTYPEGRP_Array,$dataset_RMTYPEGRP_Array["RMTYPEGRP"]);
                }
                $OneOnly_RMTYPEGRP = array_unique($RMTYPEGRP_Array);  //ดึงค่าที่ไม่ซ้ำกันออกมาแสดง
                //-------------------------------------- Step2 หา RMGRPDATA --------------------------------------
                $RMTYPEGRP_DATA = array();
                $Count_RMTYPEGRP_Array = count($RMTYPEGRP_Array);
                for ($b = 0; $b < $Count_RMTYPEGRP_Array; $b++) {
                    if(isset($OneOnly_RMTYPEGRP[$b])){
                        array_push($RMTYPEGRP_DATA,[
                            'RMGRP' => $dataset_all[$b]["RMGRP"],
                            'RMTYPEGRP' => $dataset_all[$b]["RMTYPEGRP"],
                            'NAMETYPEGRP' => $dataset_all[$b]["NAMETYPEGRP"],
                            'CRMTYPEGRP'  => $dataset_all[$b]["CRMTYPEGRP"]
                        ]);
                    }
                }

                $responseArray['json_total'] =  count($RMTYPEGRP_DATA);
                $responseArray['json_data'] =  $RMTYPEGRP_DATA;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
    public function switch_rmtype() //switch รายการความเสี่ยง RMTYPE
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $search_ntg = $_POST['SEARCH_NTG'];
            $search_nt = $_POST['SEARCH_NT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select risk_rmgrp.rmgrp,  cast(risk_rmgrp.name AS varchar2(2000)) as namegrp, risk_rmgrp.canceldate as crmgrp, 
                               risk_rmtypegrp.rmtypegrp, cast(risk_rmtypegrp.name AS varchar2(2000)) as nametypegrp, risk_rmtypegrp.canceldate as crmtypegrp,
                               risk_rmtype.rmtype, cast(risk_rmtype.name AS varchar2(2000)) as nametype , risk_rmtype.varcode,risk_rmtype.canceldate as crmtype, risk_rmtype.rmleveldtl, risk_rmtype.centinial

                            from webintra.risk_rmgrp
                            left outer join webintra.risk_rmtypegrp
                                 on risk_rmgrp.rmgrp = risk_rmtypegrp.rmgrp
                            
                            left outer join webintra.risk_rmtype
                                 on risk_rmtypegrp.rmtypegrp = risk_rmtype.rmtypegrp
                            
                            where risk_rmtypegrp.name LIKE '%$search_ntg%' and risk_rmtype.name LIKE '%$search_nt%'--and RMTYPE = 2899
                            order by risk_rmgrp.rmgrp, risk_rmtypegrp.rmtypegrp, risk_rmtype.rmtype";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute();

            if ($result) {
                $dataset_all = $objQuery->fetchAll();
                $responseArray['json_total'] =  count($dataset_all);
                $responseArray['json_data'] =  $dataset_all;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();

            } else {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
        }
    }
}