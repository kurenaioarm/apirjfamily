<?php

class rjactivitytime_api_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function timeline_event() //ตรวจสอบ ID_Event
    {
        $sdate = $_POST['SDATE'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select ACTIVITY_ID, cast(ACTIVITY_TITLE AS varchar2(2000)) as ACTIVITY_TITLE, cast(DESCRIPTION AS varchar2(2000)) as DESCRIPTION, 
                          TO_CHAR(STARTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') STARTDATE, 
                          TO_CHAR(ENDDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') ENDDATE, 
                          ACTIVITY_BGCOLOR, cast(ACTIVITY_LINK AS varchar2(2000)) as ACTIVITY_LINK, CANCELDATE , 
                          TO_CHAR(FIRSTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') FIRSTDATE , FIRSTSTF
                          from webout.Activitytimeline 
                          where to_char(to_date(STARTDATE),'mm/yyyy') = to_char(to_date(:SDATE ,'dd/mm/yyyy'),'mm/yyyy')";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webout->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);

        $result = $objQuery->execute(array(':SDATE' => $sdate));

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

    public function timeline_date() //ตรวจสอบ DATE_Event
    {
        $activity_data = $_POST['ACTIVITY_ID'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select ACTIVITY_ID, cast(ACTIVITY_TITLE AS varchar2(2000)) as ACTIVITY_TITLE, cast(DESCRIPTION AS varchar2(2000)) as DESCRIPTION, 
                          TO_CHAR(STARTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') STARTDATE, 
                          TO_CHAR(ENDDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') ENDDATE, 
                          ACTIVITY_BGCOLOR, cast(ACTIVITY_LINK AS varchar2(2000)) as ACTIVITY_LINK, CANCELDATE ,
                           TO_CHAR(FIRSTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') FIRSTDATE , FIRSTSTF
                          from webout.Activitytimeline 
                          where ACTIVITY_ID = :ACTIVITY_ID";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webout->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);

        $result = $objQuery->execute(array(':ACTIVITY_ID' => $activity_data));

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


    public function event_two_year() //ตรวจสอบ Event ย้อนหลัง 1 ปี ไปข้่งหน้า 1 ปี
    {
        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select ACTIVITY_ID, cast(ACTIVITY_TITLE AS varchar2(2000)) as ACTIVITY_TITLE, cast(DESCRIPTION AS varchar2(2000)) as DESCRIPTION, STARTDATE, ENDDATE, 
                              ACTIVITY_BGCOLOR, cast(ACTIVITY_LINK AS varchar2(2000)) as ACTIVITY_LINK, CANCELDATE , FIRSTDATE , FIRSTSTF
                              from webout.Activitytimeline 
                             where startdate between to_date(sysdate-365)and to_date(sysdate+365)";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webout->prepare($strSql);
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

    public function event_all() //ตรวจสอบ Event ย้อนหลัง 1 ปี ไปข้่งหน้า 1 ปี
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ACTIVITY_ID from webout.Activitytimeline";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webout->prepare($strSql);
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

    public function event_date() //ตรวจสอบ Event ตามช่วงเวลา
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ACTIVITY_ID, cast(ACTIVITY_TITLE AS varchar2(2000)) as ACTIVITY_TITLE, cast(DESCRIPTION AS varchar2(2000)) as DESCRIPTION, 
                              TO_CHAR(STARTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') STARTDATE, 
                              TO_CHAR(ENDDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') ENDDATE, 
                              ACTIVITY_BGCOLOR, cast(ACTIVITY_LINK AS varchar2(2000)) as ACTIVITY_LINK, CANCELDATE ,
                              TO_CHAR(FIRSTDATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') FIRSTDATE , FIRSTSTF
                                from webout.Activitytimeline 
                                where trunc(STARTDATE) BETWEEN TO_DATE(:SDATE,'dd/mm/yyyy')
                                and TO_DATE(:EDATE,'dd/mm/yyyy')";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webout->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':SDATE' => $sdate,':EDATE' => $edate));

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

    public function event_cancel() //cancel Event
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $activity_id = $_POST['ACTIVITY_ID'];
            $tdate = $_POST['TDATE'];
            $cancelstf = $_POST['CANCELSTF'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "UPDATE webout.Activitytimeline SET CANCELDATE = to_char(to_date(:TDATE ,'dd/mm/yyyy')), CANCELSTF = :CANCELSTF
                              where ACTIVITY_ID = :ACTIVITY_ID";


            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webout->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':ACTIVITY_ID' => $activity_id,':TDATE' => $tdate,':CANCELSTF' => $cancelstf));

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

    public function event_update() //cancel Event
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $activity_title = $_POST['ACTIVITY_TITLE'];
            $description = $_POST['DESCRIPTION'];
            $sdate = $_POST['STARTDATE'];
            $edate = $_POST['ENDDATE'];
            $bgcolor = $_POST['ACTIVITY_BGCOLOR'];
            $link = $_POST['ACTIVITY_LINK'];
            $firstdate = $_POST['FIRSTDATE'];
            $firststf = $_POST['FIRSTSTF'];
            $activity_id = $_POST['ACTIVITY_ID'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "UPDATE webout.Activitytimeline SET 
                                ACTIVITY_TITLE = :ACTIVITY_TITLE ,
                                DESCRIPTION = :DESCRIPTION ,
                                STARTDATE = to_date(:STARTDATE ,'dd/mm/yyyy hh24:mi:ss'), 
                                ENDDATE = to_date(:ENDDATE ,'dd/mm/yyyy hh24:mi:ss'),
                                ACTIVITY_BGCOLOR = :ACTIVITY_BGCOLOR, 
                                ACTIVITY_LINK = :ACTIVITY_LINK , 
                                FIRSTDATE = to_date(:FIRSTDATE ,'dd/mm/yyyy hh24:mi:ss'), 
                                FIRSTSTF = :FIRSTSTF
                                where ACTIVITY_ID = :ACTIVITY_ID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webout->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':ACTIVITY_TITLE' => $activity_title,':DESCRIPTION' => $description,':STARTDATE' => $sdate,':ENDDATE' => $edate,':ACTIVITY_BGCOLOR' => $bgcolor,
                ':ACTIVITY_LINK' => $link, ':ACTIVITY_ID' => $activity_id, ':FIRSTDATE' => $firstdate, ':FIRSTSTF' => $firststf));

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

    public function event_Insert() //cancel Event
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $activity_id = $_POST['ACTIVITY_ID'];
            $activity_title = $_POST['ACTIVITY_TITLE'];
            $description = $_POST['DESCRIPTION'];
            $sdate = $_POST['STARTDATE'];
            $edate = $_POST['ENDDATE'];
            $bgcolor = $_POST['ACTIVITY_BGCOLOR'];
            $link = $_POST['ACTIVITY_LINK'];
            $firstdate = $_POST['FIRSTDATE'];
            $firststf = $_POST['FIRSTSTF'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "INSERT INTO webout.Activitytimeline(ACTIVITY_ID, ACTIVITY_TITLE, DESCRIPTION, STARTDATE, ENDDATE, ACTIVITY_BGCOLOR, ACTIVITY_LINK, FIRSTDATE, FIRSTSTF)
                            VALUES (:ACTIVITY_ID,:ACTIVITY_TITLE,:DESCRIPTION,
                            to_date(:STARTDATE,'dd/mm/yyyy hh24:mi:ss'),
                            to_date(:ENDDATE,'dd/mm/yyyy hh24:mi:ss'),
                            :ACTIVITY_BGCOLOR,:ACTIVITY_LINK,
                            to_date(:FIRSTDATE,'dd/mm/yyyy hh24:mi:ss'),
                            :FIRSTSTF)";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webout->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ACTIVITY_ID' => $activity_id,':ACTIVITY_TITLE' => $activity_title,':DESCRIPTION' => $description,':STARTDATE' => $sdate,':ENDDATE' => $edate,':ACTIVITY_BGCOLOR' => $bgcolor,
                ':ACTIVITY_LINK' => $link,':FIRSTDATE' => $firstdate, ':FIRSTSTF' => $firststf));

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
