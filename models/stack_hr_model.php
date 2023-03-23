<?php

class stack_hr_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function stack_hr_distinct()
    {

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select * from (
                            select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family'  statistics_date , 'DISTINCT' as status, to_char(statistics_date,'MMYYYY') as Months,
                            count(case when home_1 != 0 then home_1 end ) cnt_home1
                            
                            from webout.statis_rj_family 
                            where statistics_date between add_months(to_date(sysdate),-12)
                            and to_date(sysdate)
                            group by to_char(statistics_date,'MMYYYY'))aa
                            order by aa.Months desc
                           ";

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
    }


    public function stack_hr_sum()
    {

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select * from (
                            select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family' statistics_date , 'SUM' as status, to_char(statistics_date,'MMYYYY') as Months,
                            sum(home_1) as cnt_home1
                            
                            from webout.statis_rj_family 
                            where statistics_date between add_months(to_date(sysdate),-12)
                            and to_date(sysdate)
                            group by to_char(statistics_date,'MMYYYY'))aa
                            order by aa.Months desc
                            ";

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
    }


    public function stack_hr_day_distinct()
    {
        $sdate = $_POST['SDATE'];
        $edate = $_POST['EDATE'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select *from (
                            select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family'  statistics_date , 'DISTINCT' as status, to_char(statistics_date,'DD-MM-YYYY') month,
                            count(case when home_1 != 0 then home_1 end ) cnt_home1
                            
                            from webout.statis_rj_family 
                            where statistics_date between to_date(:SDATE,'ddmmyyyy')
                            and to_date(:EDATE,'ddmmyyyy')
                            /*statistics_date between to_date('01012022','ddmmyyyy')
                            and to_date('31032022','ddmmyyyy')*/
                            group by statistics_date) aa
                            order by aa.month desc";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webintra->prepare($strSql);
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

    public function stack_hr_day_sum()
    {
        $sdate = $_POST['SDATE'];
        $edate = $_POST['EDATE'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select *from (
                            select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family' statistics_date , 'SUM' as status, to_char(statistics_date,'DD-MM-YYYY') month,
                            sum(home_1) as cnt_home1
                            
                            from webout.statis_rj_family 
                            where statistics_date between to_date(:SDATE,'ddmmyyyy')
                            and to_date(:EDATE,'ddmmyyyy')
                            group by statistics_date) aa
                            order by aa.month desc";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webintra->prepare($strSql);
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

    public function from_the_beginning(){
        $responseArray = array();
        $responseArray['json_result'] = true;

        $strSql = "select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family' statistics_date , 'SUM' as status,
                            sum(home_1) as cnt_home1
                            from webout.statis_rj_family 
                            union 
                            select 'ShadoWoARM' as PRJNAME ,'Dashboard RJ Family'  statistics_date , 'DISTINCT' as status,
                            count(case when home_1 != 0 then home_1 end ) cnt_home1
                            from webout.statis_rj_family";

        ////connect oracle แสดงข้อมูล
        $objQuery = $this->oracle_webintra->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array());
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
