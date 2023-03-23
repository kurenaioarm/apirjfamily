<?php

class allproject_api_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function pname_api()//คำนำหน้าชื่อ
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select PNAME, cast(NAME AS varchar2(2000)) as NAME from pname where canceldate is null";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute();

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

    public function pname2_api()//คำนำหน้าชื่อ แบบระบบ
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $PNAME = $_POST['PNAME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select PNAME, cast(NAME AS varchar2(2000)) as NAME from pname where canceldate is null and PNAME = :PNAME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':PNAME' => $PNAME));

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

    public function province_area_api()//พื้นที่ ตำบล/แขวง , อำเภอ/เขต , จังหวัด , รหัสไปรษณีย์
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['TUMBON_ID'] == "TUMBON_ALL" && $_POST['AMPUR_ID'] == "" && $_POST['CHANGWAT_ID'] == ""){//ตำบลทั้งหมด
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select tumbon.tumbon ,cast(tumbon.name AS varchar2(2000)) as tname from webout.tumbon";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute();

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
            }else if($_POST['TUMBON_ID'] == "" && $_POST['AMPUR_ID'] == "AMPUR_ALL" && $_POST['CHANGWAT_ID'] == ""){//อำเภอทั้งหมด
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select ampur.ampur ,cast(ampur.name AS varchar2(2000)) as aname from webout.ampur";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute();

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
            }else if($_POST['TUMBON_ID'] == "" && $_POST['AMPUR_ID'] == "" && $_POST['CHANGWAT_ID'] == "CHANGWAT_ALL"){//จังหวัดทั้งหมด
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select changwat.changwat ,cast(changwat.name AS varchar2(2000)) as cname from webout.changwat";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute();

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
            }else if($_POST['TUMBON_ID'] != "" && $_POST['AMPUR_ID'] != ""  && $_POST['CHANGWAT_ID'] != ""){//ใส่จังหวัด อำเภอ ตำบล
                $changwat_id = $_POST['CHANGWAT_ID'];
                $ampur_id = $_POST['AMPUR_ID'];
                $tumbon_id = $_POST['TUMBON_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select tumbon.tumbon ,cast(tumbon.NAME AS varchar2(2000)) as TNAME , 
                                ampur.ampur,cast(ampur.NAME AS varchar2(2000)) as ANAME , 
                                changwat.changwat,cast(changwat.NAME AS varchar2(2000)) as CNAME , zipcode
                                from webout.tumbon 
                                left outer join webout.ampur
                                     on tumbon.ampur = ampur.ampur
                                     and tumbon.changwat = ampur.changwat
                                left outer join webout.changwat 
                                     on ampur.changwat = changwat.changwat
                                left outer join webout.zipcode
                                     on tumbon.changwat = zipcode.changwat
                                     and tumbon.ampur = zipcode.ampur
                                     and tumbon.tumbon = zipcode.tumbon
                                
                                where changwat.changwat = :CHANGWATID
                                and ampur.ampur = :AMPURID
                                and tumbon.tumbon = :TUMBONID
                                group by tumbon.tumbon,tumbon.NAME ,ampur.ampur,ampur.NAME , changwat.changwat,changwat.NAME , zipcode";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':TUMBONID' => $tumbon_id,':AMPURID' => $ampur_id,':CHANGWATID' => $changwat_id));

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
            }else if($_POST['TUMBON_ID'] == "" && $_POST['AMPUR_ID'] != "" && $_POST['CHANGWAT_ID'] != ""){//ใส่จังหวัด อำเภอ
                $changwat_id = $_POST['CHANGWAT_ID'];
                $ampur_id = $_POST['AMPUR_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select tumbon.tumbon ,cast(tumbon.name AS varchar2(2000)) as tname , ampur.ampur , changwat.changwat
                                from webout.tumbon 
                                left outer join webout.ampur
                                     on tumbon.ampur = ampur.ampur
                                     and tumbon.changwat = ampur.changwat
                                left outer join webout.changwat 
                                     on ampur.changwat = changwat.changwat
                                left outer join webout.zipcode
                                     on tumbon.changwat = zipcode.changwat
                                     and tumbon.ampur = zipcode.ampur
                                     and tumbon.tumbon = zipcode.tumbon
                                     
                                where changwat.changwat = :CHANGWATID
                                and ampur.ampur = :AMPURID
                                group by tumbon.tumbon ,tumbon.name ,ampur.ampur , changwat.changwat";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':AMPURID' => $ampur_id,':CHANGWATID' => $changwat_id));

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
            }else if($_POST['TUMBON_ID'] == "" && $_POST['AMPUR_ID'] == "" && $_POST['CHANGWAT_ID'] != ""){//ใส่จังหวัด
                $changwat_id = $_POST['CHANGWAT_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select ampur.ampur ,cast(ampur.name AS varchar2(2000)) as aname , changwat.changwat
                                from webout.tumbon 
                                left outer join webout.ampur
                                     on tumbon.ampur = ampur.ampur
                                     and tumbon.changwat = ampur.changwat
                                left outer join webout.changwat 
                                     on ampur.changwat = changwat.changwat
                                left outer join webout.zipcode
                                     on tumbon.changwat = zipcode.changwat
                                     and tumbon.ampur = zipcode.ampur
                                     and tumbon.tumbon = zipcode.tumbon
                                where changwat.changwat = :CHANGWATID
                                group by ampur.ampur ,ampur.name , changwat.changwat";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':CHANGWATID' => $changwat_id));

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
    }

    public function staff_name()//นำรหัส มาเช็คชื่อ staff / นำบัตรประชาชน มาเช็คชื่อ staff
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            if($_POST['STAFF'] != ""){
                $staff_id = $_POST['STAFF'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select STAFF, LCT , 
                              cast(DSPNAME AS varchar2(2000)) as DSPNAME, 
                              cast(WRKDIVNM AS varchar2(2000)) as WRKDIVNM ,
                              cast(DIVNAME AS varchar2(2000)) as DIVNAME ,
                              IDCRD from rjvt.staff where STAFF = :STAFF";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_his->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':STAFF' => $staff_id));

                if ($result) {
                    $dataset = $objQuery->fetchAll();
                    $responseArray['json_total'] = count($dataset);
                    $responseArray['json_data'] = $dataset;
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();

                } else {
                    $errorDetail = $objQuery->errorInfo();
                    $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                    echo json_encode($responseArray);
                    exit();
                }
            }else if ($_POST['IDCRD'] != ""){
                $idcrd = $_POST['IDCRD'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select STAFF, LCT , 
                              cast(DSPNAME AS varchar2(2000)) as DSPNAME, 
                             cast(WRKDIVNM AS varchar2(2000)) as WRKDIVNM ,
                              cast(DIVNAME AS varchar2(2000)) as DIVNAME ,
                              IDCRD from rjvt.staff where IDCRD = :IDCRD";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_his->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':IDCRD' => $idcrd));

                if ($result) {
                    $dataset = $objQuery->fetchAll();
                    $responseArray['json_total'] = count($dataset);
                    $responseArray['json_data'] = $dataset;
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();

                } else {
                    $errorDetail = $objQuery->errorInfo();
                    $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                    echo json_encode($responseArray);
                    exit();
                }
            }else if ($_POST['USERID'] != ""){
                $userid = $_POST['USERID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select STAFF, LCT ,
                                cast(DSPNAME AS varchar2(2000)) as DSPNAME, 
                                cast(USERID AS varchar2(2000)) as USERID,
                                CARDNO
                                from rjvt.Phisuser 
                                where userid = :USERID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_his->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':USERID' => $userid));

                if ($result) {
                    $dataset = $objQuery->fetchAll();
                    $responseArray['json_total'] = count($dataset);
                    $responseArray['json_data'] = $dataset;
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

    public function agency_name()//นำรหัส มา เช็คหน่วยงาน
    {
        $lct_id = $_POST['LCT'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select LCT , cast(DSPNAME AS varchar2(2000)) as DSPNAME from rjvt.LCT  where LCT = :LCT";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_his->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);

        $result = $objQuery->execute(array(':LCT' => $lct_id));

        if ($result) {
            $dataset = $objQuery->fetchAll();
            $responseArray['json_total'] = count($dataset);
            $responseArray['json_data'] = $dataset;
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
