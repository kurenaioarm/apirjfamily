<?php

class stack_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function stack_allproject()
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $userid = $_POST['USER_ID'];
            $projectid = $_POST['PROJECT_ID'];
            $viewdate = $_POST['VIEW_DATE'];

            $strSqls_Step1 = "select * from webintra.project_stack where USER_ID = " .$userid. " and PROJECT_ID =  " .$projectid. "   and VIEW_DATE = to_char(to_date(' " .$viewdate. "' ,'dd/mm/yyyy'))";
            $objQuery_Step1 = $this->oracle_webintra->prepare($strSqls_Step1);
            $objQuery_Step1->setFetchMode(PDO::FETCH_ASSOC);
            $objQuery_Step1->execute();
            $row_objQuery_Step1 = $objQuery_Step1->fetch(PDO::FETCH_BOTH);

            if($row_objQuery_Step1['USER_ID']  !=  null){
                $stack_number = $row_objQuery_Step1['STACK_NUMBER']+1;
                $responseArray = array();
                $responseArray['json_result'] = true;
                $strSql_Insert = "UPDATE webintra.project_stack SET STACK_NUMBER = " .$stack_number. "
                                            where USER_ID = :USERID and PROJECT_ID =  :PROJECTID   and VIEW_DATE = to_char(to_date(:VIEWDATE ,'dd/mm/yyyy'))";
                ////connect oracle แสดงข้อมูล
                $objQuery_Insert = $this->oracle_webintra->prepare($strSql_Insert);
                $objQuery_Insert->setFetchMode(PDO::FETCH_ASSOC);
                $objQuery_Insert->execute(array(':USERID' => $userid, ':PROJECTID' => $projectid, ':VIEWDATE' =>$viewdate));
            }else{
                $responseArray = array();
                $responseArray['json_result'] = true;
                $strSql_Insert = "INSERT INTO webintra.project_stack (USER_ID, PROJECT_ID, VIEW_DATE, STACK_NUMBER)
                                  VALUES (:USERID , :PROJECTID , to_date(:VIEWDATE,'dd/mm/yyyy'),1 )";
                ////connect oracle แสดงข้อมูล
                $objQuery_Insert = $this->oracle_webintra->prepare($strSql_Insert);
                $objQuery_Insert->setFetchMode(PDO::FETCH_ASSOC);
                $objQuery_Insert->execute(array(':USERID' => $userid, ':PROJECTID' => $projectid, ':VIEWDATE' =>$viewdate));

            }
        }
    }
}
