<?php

/**
 * normal
 * * fdfdsfdsfsf
 * ! sdfdsfsdf
 * ?dfsdfs
 * TODO: fdkfkdjsf
 * @param myparam is 
 */

class Vacation_Model extends Model
{
    var $between_date = array();
    public function __construct()
    {
        parent::__construct();
        $path = 'models/commander_model.php';
        $path2 = 'models/notification_model.php';
        if (file_exists($path2)) {
            require $path2;
            $this->notification_model = new Notification_Model();
        }
    }

    public function xhrVacation()
    {
        $inputJSON = file_get_contents('php://input');
        $inputBody = json_decode($inputJSON, TRUE); //convert JSON into array
        $header = $this->getAuthorizationHeader();
        $responseArray = array(
            'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'],
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
            'AUTHORIZATION_HEADER' => $header,
            'BODY' => $inputBody
        );
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        exit();
    }

    public function xhrVacation_get()
    {
        $inputJSON = file_get_contents('php://input');
        $inputBody = json_decode($inputJSON, TRUE); //convert JSON into array
        $responseArray = array('method' => 'GET', 'name' => 'test value', 'u_val' => $inputBody);
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        exit();
    }

    private function getPersonal_new($humanId, $hash = false)
    {
        if ($hash) {
            $where_cause = "LOWER(CONVERT(VARCHAR(32), HashBytes('MD5',  CONVERT(varchar, IDNo)), 2))  = :humanId";
        } else {
            $where_cause = "IDNo = :humanId";
        }
        $sql = "SELECT * FROM (
            SELECT 'CS' AS DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName FROM CS_Person
            UNION
            SELECT 'LB' AS DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName FROM LB_Person
            UNION
            SELECT 'LT' AS DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName FROM LT_Person
            UNION
            SELECT 'LO' AS DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName FROM LO_Person
            UNION
            SELECT 'ME' AS DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName FROM ME_Person
            ) Person
           WHERE {$where_cause} ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array(':humanId' => $humanId));
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) == 1) {
            return (object) $dataset[0];
        } else {
            return false;
        }
    }

    private function getPersonal($humanId)
    {
        $strField = "IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, Shift, LevelID, AdminID, P4PGroup, BossName ";
        $sql = "SELECT * FROM (
            SELECT 'CS' AS DBType, {$strField} FROM CS_Person
            UNION
            SELECT 'LB' AS DBType, {$strField} FROM LB_Person
            UNION
            SELECT 'LT' AS DBType, {$strField} FROM LT_Person
            UNION
            SELECT 'LO' AS DBType, {$strField} FROM LO_Person
            UNION
            SELECT 'ME' AS DBType, {$strField} FROM ME_Person
            ) Person
           WHERE IDNo = :humanId ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array(':humanId' => $humanId));
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) == 1) {
            return (object) $dataset[0];
        } else {
            return false;
        }
    }

    public function xhrVacation_del()
    {
        //        $responseArray = array('id' => 1, 'name' => 'wissarut');
        //        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        $doc_id = $_POST['document_id'];
        $sql = "DELETE FROM All_VacationDocOnline WHERE DocumentID = :docid ";
        $objQuery = $this->mssql_db->prepare($sql);
        $result = $objQuery->execute(array(':docid' => $doc_id));
        if ($result) {
            $responseArray = array('doc_id' => $doc_id, 'result' => true);
        } else {
            $responseArray = array('doc_id' => $doc_id, 'result' => false);
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    public function xhrVacation_chk()
    {
        $doc_id = $_POST['document_id'];
        $chk_idcard = $_POST['checked_idcard'];
        $sql = "UPDATE All_VacationDocOnline SET CheckedBy = :chkidcard, CheckedDate = GETDATE() WHERE DocumentID = :docid ";
        $objQuery = $this->mssql_db->prepare($sql);
        $result = $objQuery->execute(array(':chkidcard' => $chk_idcard, ':docid' => $doc_id));
        if ($result) {
            $responseArray = array('doc_id' => $doc_id, 'result' => true);
        } else {
            $responseArray = array('doc_id' => $doc_id, 'result' => false);
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    private function getVacationDet($DocumentID)
    {
        $sql = "SELECT IDNo, DocumentID, VType FROM All_VacationDocOnline WHERE DocumentID = :docid ";
        $objQuery = $this->mssql_db->prepare($sql);
        $result = $objQuery->execute(array(':docid' => $DocumentID));
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) == 1) {
            return (object) $dataset[0];
        } else {
            return false;
        }
    }

    private function getVacationDeta($DocumentID)
    {
        $i = 1;
        //$DocumentID = $_POST['DocumentID'];
        $stringId = "";
        foreach ($DocumentID as $key => $value) {
            if ($i == 1) {
                $stringId .= $value;
            } else {
                $stringId .= "," . (int) $value;
            }

            $i++;
        }
        $sql = "SELECT DocumentID, IDNo, VType FROM All_VacationDocOnline WHERE DocumentID IN ($stringId) ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            $tmpArr1_2 = array();
            $tmpArr5 = array();
            foreach ($dataset as $key => $value) {
                if ($value['VType'] != '5') {
                    array_push($tmpArr1_2, $value);
                } else {
                    array_push($tmpArr5, $value);
                }
            }
            $responseData = array('1_2' => $tmpArr1_2, '5' => $tmpArr5);
            //echo json_encode($responseData, JSON_PRETTY_PRINT);
            return $responseData;
        } else {
            return false;
        }
    }

    //IDNo
    public function xhrGetUperHead()
    {
        //function ต้องเปิดใน controller ก่อน
        $div = $_POST['div'];
        $sect = $_POST['sect'];
        $subsect = $_POST['subsect'];
        $worksite = $_POST['worksite'];
        $level = $_POST['level'];
        $result = $this->getUperHead($div, $sect, $subsect, $worksite, $level);
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    private function getUperHead($div, $sect, $subsect, $worksite, $level)
    {
        $strField = 'IDNo, FName, LName, EMPID, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, LevelID, AdminID ';
        $templateField = 'MUL.IDNo, PST.FName, PST.LName, MUL.EMPID, MUL.RealDivCode, MUL.RealSectCode, MUL.RealSubsectCode, MUL.RealWorkSiteCode, MUL.LevelID, MUL.AdminID';
        $format = "SELECT * FROM (
                    SELECT 'CS' AS DBType, $strField FROM CS_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'CSM' AS DBType, " . str_replace('PST.', 'CSP.', str_replace('MUL.', 'CSM.', $templateField)) . " FROM CS_MultipleHead CSM
                     INNER JOIN CS_Person CSP ON CSP.IDNo = CSM.IDNo) 
                    UNION
                    SELECT 'LB' AS DBType, $strField FROM LB_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'LBM' AS DBType, " . str_replace('PST.', 'LBP.', str_replace('MUL.', 'LBM.', $templateField)) . " FROM LB_MultipleHead LBM
                     INNER JOIN LB_Person LBP ON LBP.IDNo = LBM.IDNo)  
                    UNION
                    SELECT 'LO' AS DBType, $strField FROM LO_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'LOM' AS DBType, " . str_replace('PST.', 'LOP.', str_replace('MUL.', 'LOM.', $templateField)) . " FROM LO_MultipleHead LOM
                     INNER JOIN LO_Person LOP ON LOP.IDNo = LOM.IDNo)  
                  ) Person
                  WHERE LevelID = '$level' ";
        switch ($level) {
            case '1':
                $condition = "";
                break;
            case '2':
                $condition = "AND RealDivCode = '$div' ";
                break;
            case '3':
                $condition = "AND RealDivCode = '$div' AND RealSectCode = '$sect' ";
                break;
            case '4':
                $condition = "AND RealDivCode = '$div' AND RealSectCode = '$sect' AND RealSubsectCode = '$subsect' ";
                break;
            case '5':
                $condition = "AND RealDivCode = '$div' AND RealSectCode = '$sect' AND RealSubsectCode = '$subsect' AND RealWorkSiteCode = '$worksite' ";
                break;

            default:
                break;
        }
        $sql = $format . $condition;
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) == 1) {
            return (object) $dataset[0];
        } else {
            //echo $sql;
            return false;
        }
    }

    private function putNotifyNext($vacaDetails, $appLevel)
    {
        //IDNo, DocumentID, VType
        $approvedLevel = (int) $appLevel;
        //DBType, IDNo, EMPID, Posnum, FName, LName, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, RealPosCode, Sex, Birth, LevelID, AdminID, P4PGroup,BossName
        $personDetailsObj = $this->getPersonal($vacaDetails->IDNo);
        $willNotify = false;
        if ($approvedLevel != 1) {
            if (($vacaDetails->VType == '1' || $vacaDetails->VType == '2') && $approvedLevel >= 4) {
                $willNotify = true;
            } elseif ($vacaDetails->VType == '5' && $approvedLevel >= 3) {
                $willNotify = true;
            }
            if ($willNotify) {
                $nextBossDetails = $this->getUperHead($personDetailsObj->RealDivCode, $personDetailsObj->RealSectCode, $personDetailsObj->RealSubsectCode, $personDetailsObj->RealWorkSiteCode, $approvedLevel - 1);
                $headerText = 'มีใบลา รออนุมัติ';
                $contentText = 'เรียนคุณ ' . $nextBossDetails->FName . ' มีใบลารออนุมัติในระบบ กรุณาตรวจสอบในระบบ HRIS บน Intranet';
                $resultNotify = $this->Vaca_Notify($headerText, $contentText, md5($nextBossDetails->IDNo));
                //$resultNotify = $this->Vaca_Notify($headerText, $contentText, md5('1339900062308'));
            }
        }
    }

    public function xhrVacation_app()
    {
        $doc_id = $_POST['document_id'];
        $vacaDetails = $this->getVacationDet($doc_id);
        if (!is_object($vacaDetails) && !$vacaDetails) {
            $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => 'No Data change.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            return;
        } else {
            //TODO: ทำการเรียกข้อมูลบุคคล จากเลขบัตรประชาชนที่ได้จากใบลาออนไลน์
            $personDetailsObj = $this->getPersonal($vacaDetails->IDNo);
            //TODO: ทำการเก็บช้อมูลที่ต้องการใช้ในการเปรียบเทียบ ไว้ใน ตัวแปร แบบ Array
            $arrCompareData = array('V_Type' => $vacaDetails->VType, 'DivCode' => $personDetailsObj->RealDivCode, 'SectCode' => $personDetailsObj->RealSectCode);
        }


        $app_idcard = $_POST['checked_idcard'];
        $app_details = $_POST['checked_details'];
        $app_action = $_POST['checked_action'];
        $app_level = $_POST['boss_level'];
        $completeField = '';

        if ($app_action == 'accept') {
            if ($app_level == '1' || $app_level == '2') {
                $completeField = ', CompleteForRec = 1 ';
            } elseif ($app_level == '3' && ($vacaDetails->VType == '1' || $vacaDetails->VType == '2')) {
                $completeField = ', CompleteForRec = 1 ';
            }
        }

        //TODO: ตรวจสอบว่าใบลาที่อนุมัติ อยู่ในโรงพยาบาลราชวิถี2 หรือไม่
        /*
        if ($arrCompareData['DivCode'] == '006') {
            if ($app_level == '1' || $app_level == '2' || $app_level == '3') {
                $completeField = ', CompleteForRec = 1 ';
            } elseif ($app_level == '2' && ($vacaDetails->VType == '1' || $vacaDetails->VType == '2')) {
                $completeField = ', CompleteForRec = 1 ';
            }
        } else {
            if ($app_level == '1' || $app_level == '2') {
                $completeField = ', CompleteForRec = 1 ';
            } elseif ($app_level == '3' && ($vacaDetails->VType == '1' || $vacaDetails->VType == '2')) {
                $completeField = ', CompleteForRec = 1 ';
            }
        }
        */


        if ($app_action == 'accept') {
            if ($vacaDetails->VType == '5') {
                $vacaTypeText = 'พักผ่อน';
            } elseif ($vacaDetails->VType == '1' || $vacaDetails->VType == '2') {
                $vacaTypeText = 'กิจ/ป่วย';
            }
        }

        switch ($app_level) {
            case '1':
                $num_f = '1';
                $lelevText = 'ผู้อำนวยการ';
                if ($app_action == 'accept') {
                    $completeField = ', CompleteForRec = 1 ';
                }
                break;
            case '2':
                $num_f = '1';
                $lelevText = 'รองภารกิจ';
                break;
            case '3':
                $num_f = '2';
                $lelevText = 'หัวหน้ากลุ่มงาน';
                break;
            case '4':
                $num_f = '3';
                $lelevText = 'หัวหน้างาน';
                break;
            case '5':
                $num_f = '4';
                $lelevText = 'หัวหน้าหน่วย/ward';
                break;
            default:
                $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => 'Level Admin Is Invalid');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                exit();
                break;
        }
        $Prove = 'Approved' . $num_f;
        $ProveBy = 'ApprovedBy' . $num_f;
        $ProvePos = 'ApprovedPost' . $num_f;
        $ProveDate = 'ApprovedDate' . $num_f;
        if ($app_action == 'accept') {
            $sql = "UPDATE All_VacationDocOnline SET $Prove = 1, $ProveBy = :appidcard , $ProvePos = :appdetails , $ProveDate = GETDATE() $completeField WHERE DocumentID = :docid ";
            $arrHeader = array('h' => 'การดำเนินการออนไลน์', 'u' => 'อนุมัติแล้ว');
            $arrContent = array('h' => 'คุณได้ทำการอนุมัติใบลา' . $vacaTypeText . ' ในระดับ' . $lelevText, 'u' => 'การลา' . $vacaTypeText . ' ของคุณผ่านการอนุมัติแล้วในระดับ ' . $lelevText);
        } elseif ($app_action == 'raject') {
            $sql = "UPDATE All_VacationDocOnline SET $Prove = 0, $ProveBy = :appidcard , $ProvePos = :appdetails ,Status = '2' , $ProveDate = GETDATE() WHERE DocumentID = :docid ";
            $arrHeader = array('h' => 'การดำเนินการออนไลน์', 'u' => 'ไม่อนุมัติ');
            $arrContent = array('h' => 'คุณได้ทำการไม่อนุมัติใบลา' . $vacaTypeText . ' ในระดับ' . $lelevText, 'u' => 'การลา' . $vacaTypeText . ' ของคุณไม่ได้รับการอนุมัติในระดับ ' . $lelevText);
        } else {
            $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => 'No Data change.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            return;
        }
        $objQuery = $this->mssql_db->prepare($sql);
        $result = $objQuery->execute(array(':appidcard' => $app_idcard, ':appdetails' => $app_details, ':docid' => $doc_id));
        if ($result) {
            $responseArray = array('doc_id' => $doc_id, 'result' => true);
            /* ส่ง Notification กลับไปยังคนขอ และตัวเอง */
            $this->Vaca_Notify($arrHeader['u'], $arrContent['u'], md5($vacaDetails->IDNo));
            $this->Vaca_Notify($arrHeader['h'], $arrContent['h'] . ' หากไม่ใช่คุณ กรุณาแจ้งผู้ดูแลระบบ', md5($app_idcard));
            $this->putNotifyNext($vacaDetails, $app_level);
        } else {
            $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => $objQuery->errorInfo());
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    public function xhrVacation_appa()
    {
        $doc_id = $_POST['document_id'];
        $app_idcard = $_POST['checked_idcard'];
        $app_details = $_POST['checked_details'];
        $app_action = $_POST['checked_action'];
        $app_level = $_POST['boss_level'];
        $vacaDeta = $this->getVacationDeta($doc_id);
        $rowCountAll = 0;
        foreach ($vacaDeta as $curVType => $vacaValueByType) {
            $completeField = '';
            if ($app_action == 'accept') {
                if ($app_level == '1' || $app_level == '2') {
                    $completeField = ', CompleteForRec = 1 ';
                } elseif ($app_level == '3' && $curVType == '1_2') {
                    $completeField = ', CompleteForRec = 1 ';
                }
            }
            switch ($app_level) {
                case '1':
                    $num_f = '1';
                    if ($app_action == 'accept') {
                        $completeField = ', CompleteForRec = 1 ';
                    }
                    break;
                case '2':
                    $num_f = '1';
                    break;
                case '3':
                    $num_f = '2';
                    break;
                case '4':
                    $num_f = '3';
                    break;
                case '5':
                    $num_f = '4';
                    break;
                default:
                    $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => 'Level Admin Is Invalid');
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();
                    break;
            }
            $Prove = 'Approved' . $num_f;
            $ProveBy = 'ApprovedBy' . $num_f;
            $ProvePos = 'ApprovedPost' . $num_f;
            $ProveDate = 'ApprovedDate' . $num_f;
            $i = 1;
            $stringId = '';
            foreach ($vacaValueByType as $key => $value) {
                if ($i == 1) {
                    $stringId .= (int) $value['DocumentID'];
                } else {
                    $stringId .= "," . (int) $value['DocumentID'];
                }
                $i++;
            }
            if ($app_action == 'accept') {
                $sql = "UPDATE All_VacationDocOnline SET $Prove = 1, $ProveBy = :appidcard , $ProvePos = :appdetails , $ProveDate = GETDATE() $completeField WHERE DocumentID IN ($stringId) ";
            } elseif ($app_action == 'raject') {
                $sql = "UPDATE All_VacationDocOnline SET $Prove = 0, $ProveBy = :appidcard , $ProvePos = :appdetails ,Status = '2' , $ProveDate = GETDATE() WHERE DocumentID IN ($stringId) ";
            } else {
                $responseArray = array('doc_id' => 'Multiple Value', 'result' => false, 'error' => 'No Data change.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                return;
            }
            $objQuery = $this->mssql_db->prepare($sql);
            //        $objQuery->bindParam(":docid", $stringId, PDO::PARAM_INT);
            $objQuery->bindParam(":appidcard", $app_idcard, PDO::PARAM_STR);
            $objQuery->bindParam(":appdetails", $app_details, PDO::PARAM_STR);
            $result[$curVType] = $objQuery->execute();
            $rowCount[$curVType] = $objQuery->rowCount();
            $rowCountAll += $rowCount[$curVType];
        }
        $responseArray = array('result' => true, 'RowAffectedAll' => $rowCountAll, 'RowAffected' => $rowCount);
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    private function genToken($hid)
    {
        return md5(substr($hid, 0, 4) . date("Ymd") . "rj@va");
    }

    private function chkToken($hid, $tmpToken)
    {
        $sysToken = $this->genToken($hid);
        if ($tmpToken == $sysToken) {
            return true;
        } else {
            return false;
        }
    }

    private function checkInHR($IDNo)
    {
        $str_field = " ,IDNo ,EMPID ,Posnum ,FName ,LName ,Sex ,LevelID ";
        $sth = $this->mssql_db->prepare(" SELECT count(*) as total FROM (
        SELECT 'CS' AS DBType $str_field FROM CS_Person
        UNION
        SELECT 'LB' AS DBType $str_field FROM LB_Person
        UNION
        SELECT 'LT' AS DBType $str_field FROM LT_Person
        UNION
        SELECT 'LO' AS DBType $str_field FROM LO_Person
        UNION
        SELECT 'ME' AS DBType $str_field FROM ME_Person
        ) Person
       WHERE IDNo = :idcard ");
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sth->execute(array(':idcard' => $IDNo));
        if (!$result) {
            $errorDetail = $sth->errorInfo();
            $responseArray['login_res'] = false;
            $arrError = array('title' => 'execute error', 'description' => $errorDetail[2]);
            $responseArray['login_details'] = $arrError;
            echo json_encode($responseArray);
            exit();
        }
        $countRow = $sth->fetchColumn();
        if ($countRow == 1) {
            return true;
        } else {
            $responseArray['login_res'] = false;
            $arrError = array('title' => 'ไม่พบข้อมูล', 'description' => 'ไม่มีบุคลากรในระบบ หรือบุคลากรได้พ้นจากตำแหน่ง');
            $responseArray['login_details'] = $arrError;
            echo json_encode($responseArray);
            exit();
        }
    }

    private function chkLogin($usr_md5, $pwd_md5)
    {
        $responseArray = array();
        if (!(is_null($usr_md5)) && !(is_null($pwd_md5))) {
            $sth = $this->hr_mysql_db->prepare("SELECT * from person_regis WHERE MD5(pr_pid) = :login AND pr_pwd = :password ");
            $sth->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sth->execute(array(
                ':login' => $usr_md5,
                ':password' => $pwd_md5
            ));

            $countRow = $sth->rowCount();
            $datasetVariable = $sth->fetchAll();
            $personalDataset = (object) $datasetVariable[0];
            if ($countRow == 1) {
                //ทำเช็คกับฐานข้อมูล HR ต่อ
                $userWorking = $this->checkInHR($personalDataset->pr_pid);
            }
            if ($countRow == 1 && $userWorking) {
                $responseArray['login_res'] = true;
                $responseArray['login_id'] = $personalDataset->pr_pid;
            } else {
                $responseArray['login_res'] = false;
                $responseArray['login_id'] = null;
            }
        } else {
            $responseArray['login_res'] = false;
            $responseArray['login_id'] = null;
        }
        return (object) $responseArray;
    }

    private function checkToken()
    {
        $responseObj = array();
        $usr_token = substr(filter_input(INPUT_POST, 'utoken', FILTER_SANITIZE_STRING), 0, 32);
        $pwd_token = substr(filter_input(INPUT_POST, 'ptoken', FILTER_SANITIZE_STRING), 0, 32);
        $tmp_token = substr(filter_input(INPUT_POST, 'tmptoken', FILTER_SANITIZE_STRING), 0, 32);
        $token_res = $this->chkLogin($usr_token, $pwd_token);
        if ($token_res->login_res) {
            if ($this->chkToken($token_res->login_id, $tmp_token)) {
                $responseObj['obj_result'] = true;
                $responseObj['obj_id'] = $token_res->login_id;
            } else {
                $responseObj['obj_result'] = true;
                $responseObj['obj_id'] = 'Tmp Token Wrong...!!';
                $responseObj['obj_err'] = array('tk1' => $tmp_token, 'tk2' => $this->genToken($token_res->login_id));
                echo json_encode($responseObj, JSON_PRETTY_PRINT);
                exit();
            }
        } else {
            $responseObj['obj_result'] = false;
        }
        return (object) $responseObj;
    }

    private function getAppDetails($userID)
    {
        $sth = $this->hr_mysql_db->prepare("SELECT * from tbl_notify WHERE MD5(notify_loginid) = :userID ");
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sth->execute(array(
            ':userID' => $userID
        ));
        $countRow = $sth->rowCount();
        $datasetVariable = $sth->fetchAll();
        if ($countRow == 1 && $result) {
            $personalDataset = $datasetVariable[0];
            return array('result' => true, 'data' => $personalDataset);
        } else {
            return array('result' => false);
        }
    }

    public function xhrVaca_Notify()
    {
        $appAndroid = 'f3ed969f-6d0f-448a-a1b5-1020d40806cf';
        $appIosid = '19c21f2b-a8a9-4b00-ac5a-aa9a2f351e52';

        $authorizAndroid = 'MzE2ZWFlYjQtNjE5NC00ZDNkLTk3MWUtY2FmYjQxOTZhYjA1';
        $authorizIos = 'NzNkMzBhNjItODI2Yi00MGE5LTg5MWYtMDE5Njg0NmMxYTZh';

        $responseArray = array();
        if (isset($_POST['utoken']) && isset($_POST['ptoken'])) {
            $objPerson = $this->checkToken();
            if ($objPerson->obj_result) {
                //$currentPersonID = $objPerson->obj_id;
                $heading = array("en" => filter_input(INPUT_POST, 'txtHeader', FILTER_SANITIZE_STRING));
                $content = array("en" => filter_input(INPUT_POST, 'txtContent', FILTER_SANITIZE_STRING));
                $userID = filter_input(INPUT_POST, 'notify_id', FILTER_SANITIZE_STRING);

                /* เอา id จากฐาน HR เพื่อเอา id ไปบันทึก log notification */
                $personalDataset = $this->getPersonal_new($userID, true);

                if ($personalDataset !== false) {
                    $currentPersonID = $personalDataset->IDNo;
                } else {
                    $currentPersonID = '';
                }
                /* เอา id จากฐาน HR เพื่อเอา id ไปบันทึก log notification */
                $userNotiDetails = $this->getAppDetails($userID);

                if ($currentPersonID == "" || is_null($currentPersonID)) {
                    $currentPersonID = $objPerson->obj_id;
                }
                //print_r($userNotiDetails);
                //exit();
                $arrHead = explode(' ', $heading["en"]);
                $arrContent = explode(' ', $content["en"]);
                $logType = '1';
                $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                switch ($arrHead[0]) {
                    case 'มีการเข้าใช้ระบบ':
                        $logType = '4';
                        $logUrl = null;
                        break;
                    case 'เรียนคุณ':
                        if ($arrContent[0] == 'การขอลาบันทึกในระบบแล้ว') {
                            $logType = '25';
                            $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                        } elseif ($arrContent[0] == 'มีการขออนุมัติลาในระบบ') {
                            $logType = '26';
                            $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                        }
                        break;
                    case 'มีใบลา':
                        $logType = '26';
                        $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                        break;
                    case 'การดำเนินการออนไลน์':
                        $logType = '23';
                        $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                        break;
                    case 'อนุมัติแล้ว':
                    case 'ไม่อนุมัติ':
                        $logType = '24';
                        $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                        break;
                    default:
                        $logType = '1';
                        $logUrl = $arrHead[0];
                        break;
                }
                $this->notification_model->addLog($heading['en'], $content['en'], '1234567890123', $currentPersonID, $logType, $logUrl);

                if ($userNotiDetails['result']) {
                    $logMobile = true;
                    $notiObj = (object) $userNotiDetails['data'];
                    if ($notiObj->notify_operatingsystem == 'Ios') {
                        $currentAppID = $appIosid;
                        $currentAuthorize = $authorizIos;
                    } elseif ($notiObj->notify_operatingsystem == 'Android') {
                        $currentAppID = $appAndroid;
                        $currentAuthorize = $authorizAndroid;
                    }
                    $currentMobileID = $notiObj->notify_regid;

                    //$currentPersonID = $notiObj->notify_loginid;

                    $fields = array(
                        'app_id' => $currentAppID,
                        'include_player_ids' => [$currentMobileID],
                        'data' => array("foo" => "bar"),
                        //'url' => 'http://www.yoursite.com',
                        'contents' => $content,
                        'headings' => $heading
                    );


                    $jsonFields = json_encode($fields);
                    //print("\nJSON sent:\n");
                    //print($fields);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json; charset=utf-8',
                        'Authorization: Basic ' . $currentAuthorize
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonFields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $response = curl_exec($ch);
                    curl_close($ch);
                } else {
                    $logMobile = true;
                    $response = false;
                }

                if ($response) {
                    $responseArray['json_result'] = true;
                    $responseArray['json_data'] = array('action' => 'Notify Compleate');
                } else {
                    $responseArray['json_result'] = false;
                    $responseArray['json_data'] = array('action' => 'Notify Failed');
                }
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = null;
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = null;
        }
        //print_r($responseArray);
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    private function Vaca_Notify($headerText, $contentText, $notify_to)
    {
        $appAndroid = 'f3ed969f-6d0f-448a-a1b5-1020d40806cf';
        $appIosid = '19c21f2b-a8a9-4b00-ac5a-aa9a2f351e52';

        $authorizAndroid = 'MzE2ZWFlYjQtNjE5NC00ZDNkLTk3MWUtY2FmYjQxOTZhYjA1';
        $authorizIos = 'NzNkMzBhNjItODI2Yi00MGE5LTg5MWYtMDE5Njg0NmMxYTZh';

        $responseArray = array();

        $heading = array("en" => $headerText);
        $content = array("en" => $contentText);
        $userID = $notify_to;
        $userNotiDetails = $this->getAppDetails($userID);
        if ($userNotiDetails['result']) {
            $notiObj = (object) $userNotiDetails['data'];
            if ($notiObj->notify_operatingsystem == 'Ios') {
                $currentAppID = $appIosid;
                $currentAuthorize = $authorizIos;
            } elseif ($notiObj->notify_operatingsystem == 'Android') {
                $currentAppID = $appAndroid;
                $currentAuthorize = $authorizAndroid;
            }
            $currentMobileID = $notiObj->notify_regid;
            $currentPersonID = $notiObj->notify_loginid;
            $arrHead = explode(' ', $heading["en"]);
            $arrContent = explode(' ', $content["en"]);
            switch ($arrHead[0]) {
                case 'มีการเข้าใช้ระบบ':
                    $logType = '4';
                    $logUrl = null;
                    break;
                case 'เรียนคุณ':
                    if ($arrContent[0] == 'การขอลาบันทึกในระบบแล้ว') {
                        $logType = '25';
                        $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                    } elseif ($arrContent[0] == 'มีการขออนุมัติลาในระบบ') {
                        $logType = '26';
                        $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                    }
                    break;
                case 'มีใบลา':
                    $logType = '26';
                    $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                    break;
                case 'การดำเนินการออนไลน์':
                    $logType = '23';
                    $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                    break;
                case 'อนุมัติแล้ว':
                case 'ไม่อนุมัติ':
                    $logType = '24';
                    $logUrl = 'main.php?cD12YWNhX2xpc3Q=';
                    break;
                default:
                    $logType = '1';
                    $logUrl = $arrHead[0];
                    break;
            }
            $this->notification_model->addLog($heading['en'], $content['en'], '1234567890123', $currentPersonID, $logType, $logUrl);
            $fields = array(
                'app_id' => $currentAppID,
                'include_player_ids' => [$currentMobileID],
                'data' => array("foo" => "bar"),
                //'url' => 'http://www.yoursite.com',
                'contents' => $content,
                'headings' => $heading
            );

            $jsonFields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $currentAuthorize
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonFields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $response = false;
        }

        if ($response) {
            $responseArray['json_result'] = true;
            $responseArray['json_data'] = array('action' => 'Notify Compleate');
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('action' => 'Notify Failed');
        }
        return $responseArray;
    }

    private function getSelectedHead($IDNo, $myDb, $AdminID)
    {
        $personTabel = $myDb . '_Person';
        $multiTable = $myDb . '_MultipleHead';
        $str_sql = "SELECT admi.AdminName, hdr.* FROM "
            . "( "
            . "(SELECT LevelID ,AdminID ,RealDivCode ,RealSectCode ,RealSubsectCode ,RealWorkSiteCode ,RDivName ,RSectName ,RSubsectName ,RWorkSiteName ,IDNo FROM $personTabel) "
            . "UNION "
            . "(SELECT LevelID ,AdminID ,RealDivCode ,RealSectCode ,RealSubsectCode ,RealWorkSiteCode ,RDivName ,RSectName ,RSubsectName ,RWorkSiteName ,IDNo FROM $multiTable) "
            . ") hdr "
            . "INNER JOIN STD_AdminInternal admi ON hdr.AdminID = admi.AdminID "
            . "WHERE hdr.LevelID < 6 AND hdr.IDNo = :IDNo AND hdr.AdminID = :AdmID ";
        $objQuery = $this->mssql_db->prepare($str_sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array(':IDNo' => $IDNo, ':AdmID' => $AdminID));
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            return (object) $dataset[0];
        } else {
            return null;
        }
    }

    private function getSubordinateList($myLevel, $MySubLevel, $myDiv, $mySect, $mySubsect, $myWorksite, $orderx = "")
    {
        $qr_subLevel = substr($MySubLevel, -1);
        $fieldFormat = "xxpe.IDNo,  xxpe.Title, xxpe.FName, xxpe.LName, xxpe.RealDivCode, xxpe.RealSectCode, xxpe.RealSubsectCode, xxpe.RealWorkSiteCode, xxpe.JobClassID, xxpe.Birth, xxpe.EntryDP, xxpe.Salary, xxpo.DisplayName, xxpe.LastPromoteResult, xxpo.PosCodeSTD,  xxpe.LevelID, xxpe.AdminID ";
        $fieldCS = str_replace('xx', 'cs', $fieldFormat);
        $fieldLB = str_replace('xx', 'lb', $fieldFormat);
        $fieldLO = str_replace('xx', 'lo', $fieldFormat);
        $fieldLT = str_replace('xx', 'lt', $fieldFormat);
        $fieldME = str_replace('xx', 'me', $fieldFormat);
        $fieldCSMU = 'csmu.IDNo, cspe.Title, cspe.FName, cspe.LName, csmu.RealDivCode, csmu.RealSectCode, csmu.RealSubsectCode, csmu.RealWorkSiteCode, cspe.JobClassID, cspe.Birth, cspe.EntryDP, cspe.Salary, cspo.DisplayName, cspe.LastPromoteResult, cspo.PosCodeSTD, csmu.LevelID, csmu.AdminID ';
        $strSql = "SELECT J_HRPER.DBType,
                    J_HRPER.IDNo,
                    J_HRPER.Title,
                    J_HRPER.FName,
                    J_HRPER.LName,
                    J_HRPER.RealDivCode,
                    J_HRPER.RealSectCode,
                    J_HRPER.RealSubsectCode,
                    J_HRPER.RealWorkSiteCode,
                    J_HRPER.JobClassName,
                    J_HRPER.Birth,
                    J_HRPER.EntryDP,
                    J_HRPER.Salary,
                     J_HRPER.DisplayName,
                    J_HRPER.LastPromoteResult,
                    J_HRPER.LevelID,
                    J_HRPER.AdminID,
                    stdpc.PosCode,
                    stdpc.POS_NAME1,
                    stdpc.POS_NAMEE
             FROM (
                     (SELECT 'CS' AS DBType, $fieldCS, stdjc.JobClassName FROM CS_Person cspe
                      INNER JOIN CS_Post cspo 
                      ON cspe.Posnum = cspo.Posnum
                      LEFT JOIN STD_JobClass stdjc 
                      ON cspe.JobClassID = stdjc.JobClassID AND stdjc.DBID = '1')
                   UNION
                     (SELECT 'LB' AS DBType, $fieldLB, stdjc.JobClassName FROM LB_Person lbpe
                      INNER JOIN LB_Post lbpo 
                      ON lbpe.Posnum = lbpo.Posnum
                      LEFT JOIN STD_JobClass stdjc  ON lbpe.JobClassID = stdjc.JobClassID AND stdjc.DBID = '2')
                   UNION
                     (SELECT 'LO' AS DBType, $fieldLO, NULL as 'JobClassName' FROM LO_Person lope
                      INNER JOIN LO_Post lopo 
                      ON lope.Posnum = lopo.Posnum)
                   UNION
                     (SELECT 'LT' AS DBType, $fieldLT, NULL as 'JobClassName' FROM LT_Person ltpe
                      INNER JOIN LT_Post ltpo 
                      ON ltpe.Posnum = ltpo.Posnum)
                   UNION
                     (SELECT 'ME' AS DBType, $fieldME, NULL as 'JobClassName' FROM ME_Person mepe
                      INNER JOIN Me_Post mepo 
                      ON mepe.Posnum = mepo.Posnum)
                   
                  ) J_HRPER
             INNER JOIN STD_PostCode stdpc ON J_HRPER.PosCodeSTD = stdpc.PosCode
             WHERE J_HRPER.LevelID = '$qr_subLevel' "
            . "AND J_HRPER.IDNo != '' AND J_HRPER.IDNo IS NOT NULL ";
        if ($myLevel == 1) {
            if ($MySubLevel == 2) {
                $strSql .= "AND J_HRPER.RealDivCode != '000' ";
                $strSql .= "AND J_HRPER.RealSectCode = '000' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
            if ($MySubLevel == "S6") {
                $strSql .= "AND J_HRPER.RealDivCode = '000' ";
                $strSql .= "AND J_HRPER.RealSectCode = '000' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
        } elseif ($myLevel == 2) {
            if ($MySubLevel == 3) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode != '000' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
            if ($MySubLevel == 4) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSubsectCode != '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
            if ($MySubLevel == 5) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode != '00' ";
            }
            if ($MySubLevel == 6) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
            }
            if ($MySubLevel == "S6") {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '000' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
        } elseif ($myLevel == 3) {
            if ($MySubLevel == 4) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode != '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
            if ($MySubLevel == 5) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode != '00' ";
            }
            if ($MySubLevel == 6) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
            }
            if ($MySubLevel == "S6") {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '00' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
        } elseif ($myLevel == 4) {
            if ($MySubLevel == 5) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '$mySubsect' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode != '00' ";
            }
            if ($MySubLevel == 6) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '$mySubsect' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode != '00' ";
            }
            if ($MySubLevel == "S6") {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '$mySubsect' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '00' ";
            }
        } elseif ($myLevel == 5) {
            if ($MySubLevel == 6) {
                $strSql .= "AND J_HRPER.RealDivCode = '$myDiv' ";
                $strSql .= "AND J_HRPER.RealSectCode = '$mySect' ";
                $strSql .= "AND J_HRPER.RealSubsectCode = '$mySubsect' ";
                $strSql .= "AND J_HRPER.RealWorkSiteCode = '$myWorksite' ";
            }
        }
        if ($orderx == "") {
            $strSql .= "ORDER BY J_HRPER.RealDivCode, "
                . "J_HRPER.RealSectCode, "
                . "J_HRPER.RealSubsectCode, "
                . "J_HRPER.RealWorkSiteCode, "
                . "J_HRPER.FName, "
                . "J_HRPER.LName; ";
        } elseif ($orderx == 1) {
            $strSql .= "ORDER BY J_HRPER.DBType, "
                . "J_HRPER.DisplayName ";
        }
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            return $dataset;
        } else {
            return null;
        }
    }

    public function xhrVactionList()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $adminID = filter_input(INPUT_POST, 'txtAdminID', FILTER_SANITIZE_STRING);
            $humanID = $rsSecure->obj_id;
            $personDetails = $this->getPersonal($humanID);
            if (strlen($adminID) > 0 && isset($_POST['txtAdminID'])) {
                $responseArray['json_result'] = true;
                //หา $myQrLevelId, $myQrDiv, $myQrSect, $myQrSubsect, $myQrWorksite จากข้อมูลที่ได้
                $curentDivision = $this->getSelectedHead($humanID, $personDetails->DBType, $adminID);

                $myLevelId = $curentDivision->LevelID;
                $myDiv = $curentDivision->RealDivCode;
                $mySect = $curentDivision->RealSectCode;
                $mySubsect = $curentDivision->RealSubsectCode;
                $myWorksite = $curentDivision->RealWorkSiteCode;
                if (is_null($curentDivision)) {
                    $responseArray['json_result'] = false;
                    $responseArray['json_data'] = null;
                    $responseArray['json_error'] = array('error' => true, 'description' => 'you are not has permission on this admin id.');
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();
                }
                $ix = 1;
                $arrForApprove = array();
                if ($myLevelId == '1') {
                    $arrForApprove[2] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '2') {
                    $arrForApprove[3] = array(1, 2, 5);
                    $arrForApprove[4] = array(5);
                    $arrForApprove[5] = array(5);
                    $arrForApprove[6] = array(5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '3') {
                    $arrForApprove[4] = array(1, 2, 5);
                    $arrForApprove[5] = array(1, 2, 5);
                    $arrForApprove[6] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '4') {
                    $arrForApprove[5] = array(1, 2, 5);
                    $arrForApprove[6] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '5') {
                    $arrForApprove[6] = array(1, 2, 5);
                }
                $tmpArray = array();
                foreach ($arrForApprove as $subLevel => $arrVType) {
                    //echo $myLevelId . $subLevel . $myDiv . $mySect . $mySubsect . $myWorksite.'<br>';
                    $resultMySubordinate = $this->getSubordinateList($myLevelId, $subLevel, $myDiv, $mySect, $mySubsect, $myWorksite);
                    //print_r($resultMySubordinate);
                    if ($subLevel == "S6") {
                        $subSpecial = true;
                    } else {
                        $subSpecial = false;
                    }
                    $arrPerson = $this->prepareArray($resultMySubordinate);
                    if (count($arrPerson[0]) > 0) {
                        $resultOwnSub = $this->getVacAprLst($myLevelId, $arrPerson, $arrVType, $subSpecial, $subLevel);
                        if (count($resultOwnSub) > 0) {
                            if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                                //print_r($resultOwnSub);
                            }
                            $tmpArray = array_merge($tmpArray, $resultOwnSub);
                            //$ix = showVacList($resultOwnSub, $arrPerson[1], $ix, $subLevel, $headArr, $vacationCodeArr, $myLevelId);
                        } else {
                            if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                                //echo 'no body sub ';
                            }
                        }
                    }
                }
                $responseArray['json_data_total'] = count($tmpArray);
                $responseArray['json_data'] = $tmpArray;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = null;
                $responseArray['json_error'] = array('error' => true, 'description' => 'some parameter missing.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_error'] = array('error' => true, 'description' => 'Permission Denied. Login Fails.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    private function chkActingPermiss($idcard, $adminID)
    {
        $strSql = "SELECT * "
            . "FROM v_permission vp "
            . "WHERE vp.pp_idcard = :idcard "
            . "AND vp.admin_id = :adminid "
            . "AND vp.pp_type = 'acv' "
            . "AND vp.pp_sdate <= :sdate "
            . "AND vp.pp_edate >= :edate ";
        $objQuery = $this->hr_mysql_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $arrayBindParam = array(':idcard' => $idcard, ':adminid' => $adminID, ':sdate' => date("Y-m-d"), ':edate' => date("Y-m-d"));
        $result = $objQuery->execute($arrayBindParam);
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            $responseArray['json_result'] = true;
            $responseArray['json_data'] = $dataset;
        } else {
            if (!$result) {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = array('error' => $objQuery->errorInfo());
                exit();
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = $dataset;
            }
        }
        return $responseArray;
    }

    public function xhrActVacLst()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $adminID = filter_input(INPUT_POST, 'txtAdminID', FILTER_SANITIZE_STRING);
            $humanID = $rsSecure->obj_id;
            $personDetails = $this->getPersonal($humanID);
            if (strlen($adminID) > 0 && isset($_POST['txtAdminID'])) {
                $responseArray['json_result'] = true;
                /* ค้นหาในสิทธิ์ ใน database */
                $crPerStat = $this->chkActingPermiss($humanID, $adminID);
                if ($crPerStat['json_result'] == false) {
                    //print_r($crPerStat);
                    $this->takeError("Not permission found.");
                    exit();
                } else {
                    $crPerData = $crPerStat['json_data'][0];
                    //print_r($crPerData);
                }
                /* ค้นหาสิทธิ์ยังเป็นของคนที่มอบอยู่หรือไม่ */
                $grantOwnerData = $this->getPersonal($crPerData['pp_add_by']);
                //print_r($grantOwnerData);

                //$grantOwnerAdminData = $this->getSelectedHead($grantOwnerData->IDNo, $grantOwnerData->DBType, $adminID);
                //print_r($grantOwnerAdminData);
                //
                //หา $myQrLevelId, $myQrDiv, $myQrSect, $myQrSubsect, $myQrWorksite จากข้อมูลที่ได้
                $curentDivision = $this->getSelectedHead($grantOwnerData->IDNo, $grantOwnerData->DBType, $adminID);
                $myLevelId = $curentDivision->LevelID;
                $myDiv = $curentDivision->RealDivCode;
                $mySect = $curentDivision->RealSectCode;
                $mySubsect = $curentDivision->RealSubsectCode;
                $myWorksite = $curentDivision->RealWorkSiteCode;
                if (is_null($curentDivision)) {
                    $responseArray['json_result'] = false;
                    $responseArray['json_data'] = null;
                    $responseArray['json_error'] = array('error' => true, 'description' => 'you are not has permission on this admin id.');
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();
                }
                //$this->getVacAprLst($myLevelId, $arrIDNo, $arrVType, $subSpecial, $subLevel);
                $this->getVacationList($myLevelId, $myDiv, $mySect, $mySubsect, $myWorksite);
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = null;
                $responseArray['json_error'] = array('error' => true, 'description' => 'some parameter missing.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_error'] = array('error' => true, 'description' => 'Permission Denied. Login Fails.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    private function getHasPermiss($idcard, $permissiion, $adminid = null)
    {
        $strSql = "SELECT * FROM v_permission WHERE pp_idcard = :idcard AND pp_type = :permiss ";
        if (!is_null($adminid)) {
            $strSql .= "AND admin_id = '{$adminid}' ";
        }
        $objQuery = $this->hr_mysql_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array(':idcard' => $idcard, ':permiss' => $permissiion));
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            return (object) $dataset[0];
        } else {
            return null;
        }
    }

    private function getVacationList($myLevelId, $myDiv, $mySect, $mySubsect, $myWorksite)
    {
        //สร้างขึ้นให้รักษาการแทนใช้ในการดูรายการใบลาเพื่อนุมัติ
        $responseArray['json_result'] = true;
        $ix = 1;
        $arrForApprove = array();
        if ($myLevelId == '1') {
            $arrForApprove[2] = array(1, 2, 5);
            $arrForApprove["S6"] = array(1, 2, 5);
        }
        if ($myLevelId == '2') {
            $arrForApprove[3] = array(1, 2, 5);
            $arrForApprove[4] = array(5);
            $arrForApprove[5] = array(5);
            $arrForApprove[6] = array(5);
            $arrForApprove["S6"] = array(1, 2, 5);
        }
        if ($myLevelId == '3') {
            $arrForApprove[4] = array(1, 2, 5);
            $arrForApprove[5] = array(1, 2, 5);
            $arrForApprove[6] = array(1, 2, 5);
            $arrForApprove["S6"] = array(1, 2, 5);
        }
        if ($myLevelId == '4') {
            $arrForApprove[5] = array(1, 2, 5);
            $arrForApprove[6] = array(1, 2, 5);
            $arrForApprove["S6"] = array(1, 2, 5);
        }
        if ($myLevelId == '5') {
            $arrForApprove[6] = array(1, 2, 5);
        }
        $tmpArray = array();
        foreach ($arrForApprove as $subLevel => $arrVType) {
            $resultMySubordinate = $this->getSubordinateList($myLevelId, $subLevel, $myDiv, $mySect, $mySubsect, $myWorksite);
            if ($subLevel == "S6") {
                $subSpecial = true;
            } else {
                $subSpecial = false;
            }
            $arrPerson = $this->prepareArray($resultMySubordinate);
            if (count($arrPerson[0]) > 0) {
                $resultOwnSub = $this->getVacAprLst($myLevelId, $arrPerson, $arrVType, $subSpecial, $subLevel);
                if (count($resultOwnSub) > 0) {
                    if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                        //print_r($resultOwnSub);
                    }
                    $tmpArray = array_merge($tmpArray, $resultOwnSub);
                    //$ix = showVacList($resultOwnSub, $arrPerson[1], $ix, $subLevel, $headArr, $vacationCodeArr, $myLevelId);
                } else {
                    if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                        //echo 'no body sub ';
                    }
                }
            }
        }
        $responseArray['json_data_total'] = count($tmpArray);
        $responseArray['json_data'] = $tmpArray;
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    public function xhrVactionViews()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $responseArray['json_result'] = true;
            $adminID = filter_input(INPUT_POST, 'txtAdminID', FILTER_SANITIZE_STRING);
            $humanID = $rsSecure->obj_id;
            $personDetails = $this->getPersonal($humanID);
            //หา $myQrLevelId, $myQrDiv, $myQrSect, $myQrSubsect, $myQrWorksite จากข้อมูลที่ได้
            $curentDivision = $this->getSelectedHead($humanID, $personDetails->DBType, $adminID);
            $myLevelId = $curentDivision->LevelID;
            $myDiv = $curentDivision->RealDivCode;
            $mySect = $curentDivision->RealSectCode;
            $mySubsect = $curentDivision->RealSubsectCode;
            $myWorksite = $curentDivision->RealWorkSiteCode;
            //print_r($curentDivision);
            $ix = 1;
            $arrForApprove = array();
            if ($myLevelId == '1') {
                $arrForApprove[2] = array(1, 2, 5);
                $arrForApprove["S6"] = array(1, 2, 5);
            }
            if ($myLevelId == '2') {
                $arrForApprove[3] = array(1, 2, 5);
                $arrForApprove[4] = array(5);
                $arrForApprove[5] = array(5);
                $arrForApprove[6] = array(5);
                $arrForApprove["S6"] = array(1, 2, 5);
            }
            if ($myLevelId == '3') {
                $arrForApprove[4] = array(1, 2, 5);
                $arrForApprove[5] = array(1, 2, 5);
                $arrForApprove[6] = array(1, 2, 5);
                $arrForApprove["S6"] = array(1, 2, 5);
            }
            if ($myLevelId == '4') {
                $arrForApprove[5] = array(1, 2, 5);
                $arrForApprove[6] = array(1, 2, 5);
                $arrForApprove["S6"] = array(1, 2, 5);
            }
            if ($myLevelId == '5') {
                $arrForApprove[6] = array(1, 2, 5);
            }
            $tmpArray = array();
            foreach ($arrForApprove as $subLevel => $arrVType) {
                //echo $myLevelId . $subLevel . $myDiv . $mySect . $mySubsect . $myWorksite.'<br>';
                $resultMySubordinate = $this->getSubordinateList($myLevelId, $subLevel, $myDiv, $mySect, $mySubsect, $myWorksite);
                //print_r($resultMySubordinate);
                if ($subLevel == "S6") {
                    $subSpecial = true;
                } else {
                    $subSpecial = false;
                }
                $arrPerson = $this->prepareArray($resultMySubordinate);
                if (count($arrPerson[0]) > 0) {
                    $resultOwnSub = $this->getVacViews($arrPerson, $arrVType);
                    if (count($resultOwnSub) > 0) {
                        if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                            //print_r($resultOwnSub);
                        }
                        $tmpArray = array_merge($tmpArray, $resultOwnSub);
                        //$ix = showVacList($resultOwnSub, $arrPerson[1], $ix, $subLevel, $headArr, $vacationCodeArr, $myLevelId);
                    } else {
                        if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                            //echo 'no body sub ';
                        }
                    }
                }
            }
            $responseArray['json_data_total'] = count($tmpArray);
            $responseArray['json_data'] = $tmpArray;
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_error'] = 'Permission Denied. Login Fails.';
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    public function xhrVacAction()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $doc_id = $_POST['document_id'];
            $vacaDetails = $this->getVacationDet($doc_id);
            if (!is_object($vacaDetails) && !$vacaDetails) {
                $responseArray = array('doc_id' => $doc_id, 'result' => false, 'error' => 'No Data change.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                return;
            }
            $app_idcard = $_POST['checked_idcard'];
            $app_details = $_POST['checked_details'];
            $app_action = $_POST['checked_action'];
            $app_level = $_POST['boss_level'];
            $completeField = '';

            if ($app_action == 'accept') {
                if ($app_level == '2' && $vacaDetails->VType == '5') {
                    $completeField = ', CompleteForRec = 1 ';
                } elseif ($app_level == '3' && ($vacaDetails->VType == '1' || $vacaDetails->VType == '2')) {
                    $completeField = ', CompleteForRec = 1 ';
                }
            }

            if ($vacaDetails->VType == '5') {
                $vacaTypeText = 'พักผ่อน';
            } elseif ($vacaDetails->VType == '1' || $vacaDetails->VType == '2') {
                $vacaTypeText = 'กิจ/ป่วย';
            }
            switch ($app_level) {
                case '1':
                    $num_f = '1';
                    $lelevText = 'ผู้อำนวยการ';
                    if ($app_action == 'accept') {
                        $completeField = ', CompleteForRec = 1 ';
                    }
                    break;
                case '2':
                    $num_f = '1';
                    $lelevText = 'รองภารกิจ';
                    break;
                case '3':
                    $num_f = '2';
                    $lelevText = 'หัวหน้ากลุ่มงาน';
                    break;
                case '4':
                    $num_f = '3';
                    $lelevText = 'หัวหน้างาน';
                    break;
                case '5':
                    $num_f = '4';
                    $lelevText = 'หัวหน้าหน่วย/ward';
                    break;
                default:
                    $responseArray['json_result'] = false;
                    $responseArray['json_data'] = array('error' => 'Level Admin Is Invalid');
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();
                    break;
            }
            $Prove = 'Approved' . $num_f;
            $ProveBy = 'ApprovedBy' . $num_f;
            $ProvePos = 'ApprovedPost' . $num_f;
            $ProveDate = 'ApprovedDate' . $num_f;
            if ($app_action == 'accept') {
                $sql = "UPDATE All_VacationDocOnline SET $Prove = 1, $ProveBy = :appidcard , $ProvePos = :appdetails , $ProveDate = GETDATE() $completeField WHERE DocumentID = :docid ";
                $arrHeader = array('h' => 'การดำเนินการออนไลน์', 'u' => 'อนุมัติแล้ว');
                $arrContent = array('h' => 'คุณได้ทำการอนุมัติใบลา' . $vacaTypeText . ' ในระดับ' . $lelevText, 'u' => 'การลา' . $vacaTypeText . ' ของคุณผ่านการอนุมัติแล้วในระดับ ' . $lelevText);
            } elseif ($app_action == 'raject') {
                $sql = "UPDATE All_VacationDocOnline SET $Prove = 0, $ProveBy = :appidcard , $ProvePos = :appdetails ,Status = '2' , $ProveDate = GETDATE() WHERE DocumentID = :docid ";
                $arrHeader = array('h' => 'การดำเนินการออนไลน์', 'u' => 'ไม่อนุมัติ');
                $arrContent = array('h' => 'คุณได้ทำการไม่อนุมัติใบลา' . $vacaTypeText . ' ในระดับ' . $lelevText, 'u' => 'การลา' . $vacaTypeText . ' ของคุณไม่ได้รับการอนุมัติในระดับ ' . $lelevText);
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = array('doc_id' => $doc_id, 'error' => 'No Data change. Not have action value.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
                return;
            }
            $objQuery = $this->mssql_db->prepare($sql);
            $result = $objQuery->execute(array(':appidcard' => $app_idcard, ':appdetails' => $app_details, ':docid' => $doc_id));
            if ($result) {
                $responseArray['json_result'] = true;
                $responseArray['json_data'] = array('doc_id' => $doc_id);
                /* ส่ง Notification กลับไปยังคนขอ และตัวเอง */
                $this->Vaca_Notify($arrHeader['u'], $arrContent['u'], md5($vacaDetails->IDNo));
                $this->Vaca_Notify($arrHeader['h'], $arrContent['h'] . ' หากไม่ใช่คุณ กรุณาแจ้งผู้ดูแลระบบ', md5($app_idcard));
                $this->putNotifyNext($vacaDetails, $app_level);
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = array('doc_id' => $doc_id, 'error' => $objQuery->errorInfo());
            }
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    /* List รายการใบลาของหน้าอนุมัติ S */

    private function getVacAprLst($myLevelId, $arrIDNo, $arrVType, $subSpecial, $subLevel)
    {
        //$strSql = $this->getSql_toAprv($myLevelId);
        $strSql = sprintf($this->getSql_toAprv($myLevelId, $subSpecial, $subLevel), "'" . implode("','", $arrIDNo) . "'", implode(', ', $arrVType));
        if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
            //echo $strSql.'<br><br>';
        }
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            return $dataset;
        } else {
            if (!$result) {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['getVacAprLst'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                //if (date("Y-m-d") == "2021-02-15" && $_SERVER['REMOTE_ADDR'] == '172.168.4.14') {
                //$responseArray['sql'] =  $strSql;
                //}
                echo json_encode($responseArray);
                exit();
            }
            return null;
        }
    }

    /* List รายการใบลาของหน้าสถานะใบลา S */

    private function getVacViews($arrIDNo, $arrVType)
    {
        $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag "
            . ",HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate "
            . ",Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 "
            . ",Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 "
            . ",Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType "
            . ",SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate "
            . ",Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate "
            . ",AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,CompleteForRec ,Medical_File "
            . "FROM All_VacationDocOnline "
            . "WHERE IDNo IN (%s) AND VType IN (%s) "
            . "AND HRISRec != 1 ORDER BY OrderDate DESC;";
        $strSql = sprintf($format, implode(', ', $arrIDNo), implode(', ', $arrVType));
        if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
            //echo $strSql.'<br><br>';
        }
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        $dataset = $objQuery->fetchAll();
        if ($result && count($dataset) >= 1) {
            return $dataset;
        } else {
            if (!$result) {
                $errorDetail = $objQuery->errorInfo();
                $responseArray['getVacAprLst_details'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
                echo json_encode($responseArray);
                exit();
            }
            return null;
        }
    }

    private function getSql_toAprv($myLevelId, $subSpecial, $subLevel)
    {
        switch ($myLevelId) {
            case 1:
            case '1':
                $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,Medical_File FROM All_VacationDocOnline "
                    . "WHERE IDNo IN (%s) AND VType IN (%s) AND CompleteForRec != 1 "
                    . "AND (Approved1 IS NULL OR Approved1 = 0 ) AND ApprovedByBy1 IS NULL ;";
                break;
            case 2:
            case '2':
                $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,Medical_File FROM All_VacationDocOnline "
                    . "WHERE IDNo IN (%s) AND VType IN (%s) AND CompleteForRec != 1 ";
                if (!$subSpecial) {
                    if ($subLevel == 3) {
                        $where_cause = "AND Approved2 != 1 "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    } else {
                        $where_cause = "AND Approved2 = 1 "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    }
                } elseif ($subSpecial) {
                    $where_cause = "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                }
                $format .= $where_cause;
                break;
            case 3:
            case '3':
                $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,Medical_File FROM All_VacationDocOnline "
                    . "WHERE IDNo IN (%s) AND VType IN (%s) AND CompleteForRec != 1 ";
                if (!$subSpecial) {
                    if ($subLevel == 4) {
                        $where_cause = "AND Approved3 != 1 "
                            . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    } elseif ($subLevel == 5 || $subLevel == 6) {
                        $where_cause = "AND Approved3 = 1 "
                            . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    }
                } elseif ($subSpecial) {
                    $where_cause = "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                        . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                }
                $format .= $where_cause;
                break;
            case 4:
            case '4':
                $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,Medical_File FROM All_VacationDocOnline "
                    . "WHERE IDNo IN (%s) AND VType IN (%s) AND CompleteForRec != 1 ";
                if (!$subSpecial) {
                    if ($subLevel == 5) {
                        $where_cause = "AND Approved4 != 1  "
                            . "AND (Approved3 IS NULL OR Approved3 = 0) AND ApprovedBy3 IS NULL "
                            . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    } elseif ($subLevel == 6) {
                        $where_cause = "AND Approved4 = 1  "
                            . "AND (Approved3 IS NULL OR Approved3 = 0) AND ApprovedBy3 IS NULL "
                            . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                            . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                    }
                } elseif ($subSpecial) {
                    $where_cause = "AND (Approved3 IS NULL OR Approved3 = 0) AND ApprovedBy3 IS NULL "
                        . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                        . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                }
                $format .= $where_cause;
                break;
            case 5:
            case '5':
                $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,Medical_File FROM All_VacationDocOnline "
                    . "WHERE IDNo IN (%s) AND VType IN (%s) AND CompleteForRec != 1 ";
                //. "AND CheckedDate IS NOT NULL "
                $where_cause = "AND (Approved4 IS NULL OR Approved4 = 0) AND ApprovedBy4 IS NULL "
                    . "AND (Approved3 IS NULL OR Approved3 = 0) AND ApprovedBy3 IS NULL "
                    . "AND (Approved2 IS NULL OR Approved2 = 0) AND ApprovedBy2 IS NULL "
                    . "AND (Approved1 IS NULL OR Approved1 = 0) AND ApprovedBy1 IS NULL;";
                $format .= $where_cause;
                break;
            default:
                break;
        }
        return $format;
    }

    private function getMySubVac_toView()
    {
        $format = "SELECT DocumentID ,IDNo ,SDate ,EDate ,VType ,Reason ,VDay ,VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M ,HalfDay1E ,HalfDate1 ,OrderDate ,Approved1 ,ApprovedBy1 ,ApprovedPost1 ,ApprovedDate1 ,Approved2 ,ApprovedBy2 ,ApprovedPost2 ,ApprovedDate2 ,Approved3 ,ApprovedBy3 ,ApprovedPost3 ,ApprovedDate3 ,Approved4 ,ApprovedBy4 ,ApprovedPost4 ,ApprovedDate4 ,CAST(Remark AS TEXT) AS Remark ,Status ,CancelDocID ,Medical ,MedicalDay ,HalfAllFlag ,ModifiedBy ,ModifiedDate ,DocID1 ,PersonType ,SLastDate ,ELastDate ,LastVDay ,Address ,Attention ,FulllName ,Position ,Division ,CheckedBy ,CheckedDate ,Comment ,Command ,SumVacDay ,SickDayPast ,BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate ,AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay ,CompleteForRec ,Medical_File "
            . "FROM All_VacationDocOnline "
            . "WHERE IDNo IN (%s) AND VType IN (%s) "
            //. "AND CheckedDate IS NOT NULL "
            . "AND HRISRec != 1;";
        return $format;
    }

    private function prepareArray($dataset)
    {
        $arrIDNo = array();
        if (!is_null($dataset)) {
            foreach ($dataset as $key => $data) {
                array_push($arrIDNo, $data['IDNo']);
            }
        }
        return $arrIDNo;
    }

    private function getBetweenDate($pType, $vacType, $fisYear, $fullYear)
    {
        $zeroTime = " 00:00:00";
        //วันที่ปัจจุบัน
        $cDate = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        if (($pType == 'CS' || $pType == 'LB') && ($vacType == '1' || $vacType == '2')) {
            $qHalf = mktime(0, 0, 0, 4, 1, $fisYear);
            if ($cDate >= $qHalf) {
                if ($fullYear == true) {
                    $qStart = ($fisYear - 1) . "-01-10";
                } else {
                    $qStart = $fisYear . "-01-04";
                }
                $qEnd = $fisYear . "-30-09";
            } else {
                $qStart = ($fisYear - 1) . "-01-10";
                $qEnd = $fisYear . "-31-03";
            }
        } else {
            $StartFisYear = mktime(0, 0, 0, 10, 1, $fisYear);
            //เช็ควันที่ปัจจุบัน เกินวันที่ 10 ตุลาในปีนี้หรือยัง
            if ($cDate >= $StartFisYear) {
                $qStart = $fisYear . "-01-10";
                $qEnd = ($fisYear + 1) . "-30-09";
            } else {
                $qStart = ($fisYear - 1) . "-01-10";
                $qEnd = $fisYear . "-30-09";
            }
        }
        $qStart .= $zeroTime;
        $qEnd .= $zeroTime;
        return array('qStart' => $qStart, 'qEnd' => $qEnd);
    }

    private function getCurrentFisYear($ddate = '')
    { //yyyy-mm-dd
        $rdate = strlen($ddate) == 0 ? date('Y-m-d') : $ddate;
        list($year, $month, $day) = explode("-", $rdate);
        // วันที่ที่ส่งมา (mktime)
        $cDate = mktime(0, 0, 0, $month, $day, $year);
        // เริ่มปีงบประมาณตามปีที่ส่งมา (mktime)
        $dStart = mktime(0, 0, 0, 10, 1, $year);
        // วันสุดท้ายปีงบประมาณตามปีที่ส่งมา (mktime)
        $dEnd = mktime(0, 0, 0, 9, 30, $year + 1);
        if ($cDate >= $dStart && $cDate <= $dEnd) {
            // 1 ตค. -  30 กย.
            $year++;
        }
        return $year;
    }

    private function getPType_Code($DBType)
    {
        $arrType['CS'] = 1;
        $arrType['LB'] = 2;
        $arrType['LO'] = 3;
        $arrType['LT'] = 4;
        $arrType['ME'] = 5;
        return $arrType[$DBType];
    }

    private function getSumVac($iDNo, $pType, $fisYear, $vacType, $fullYear = '')
    {
        $vacHisTable = $pType . "_VacationHistory";
        $perTable = $pType . "_Person";
        $arrBetDate = $this->getBetweenDate($pType, $vacType, $fisYear, $fullYear);
        $this->between_date = $arrBetDate;
        $sql = "SELECT vh.VType, "
            . "SUM( CASE vh.VFlag "
            . "WHEN 0 THEN 1 "
            . "WHEN 1 THEN 0.5 "
            . "END) as Vdays "
            . "FROM $vacHisTable vh "
            . "INNER JOIN $perTable mp ON mp.EMPID = vh.EmpID "
            . "WHERE mp.IDNo = :IDNo "
            . "AND vh.VType = :VType "
            . "AND (vh.SDate >= :SDate) "
            . "AND (vh.SDate <= :EDate) "
            . "GROUP BY vh.VType";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(
            array(
                ':IDNo' => $iDNo,
                ':VType' => $vacType,
                ':SDate' => $arrBetDate['qStart'],
                ':EDate' => $arrBetDate['qEnd']
            )
        );
        if ($result) {
            $dataset = $objQuery->fetchAll();
            if (count($dataset) == 1) {
                $tmpArray = $dataset[0];
                return (object) array('VType' => $vacType, 'Vdays' => $tmpArray['Vdays'] + 0.0);
            } else {
                return (object) array('VType' => $vacType, 'Vdays' => 0);
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo());
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function getHistoryVacDays($iDNo, $pType, $fisYear)
    {
        $vacHisTable = $pType . "_VacationHistory";
        $perTable = $pType . "_Person";
        $arrBetDate = $this->getBetweenDate($pType, '5', $fisYear, true);
        $this->between_date = $arrBetDate;
        $sql = "SELECT CONVERT(varchar,vh.SDate, 23) AS SDate "
            . "FROM $vacHisTable vh "
            . "INNER JOIN $perTable mp ON mp.EMPID = vh.EmpID "
            . "WHERE mp.IDNo = :IDNo "
            . "AND (vh.SDate >= :SDate) "
            . "AND (vh.SDate <= :EDate) ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(
            array(
                ':IDNo' => $iDNo,
                ':SDate' => $arrBetDate['qStart'],
                ':EDate' => $arrBetDate['qEnd']
            )
        );
        if ($result) {
            $dataset = $objQuery->fetchAll();
            if (count($dataset) >= 1) {
                $arrTmp = array();
                foreach ($dataset as $key => $value) {
                    $arrTmp[] = $value['SDate'];
                }
                return $arrTmp;
            } else {
                return null;
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo());
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function getVacTransfer($iDNo, $DBType)
    {
        $typeCode = $this->getPType_Code($DBType);
        $fisYear = $this->getCurrentFisYear();
        $sql = "SELECT IDNo
                    ,PType
                    ,VacationYear
                    ,VacationTransfer
                    ,ModifiedBy
                    ,ModifiedDate
                FROM All_VacationTransfer
                WHERE IDNo = :IDNo
                AND PType = :PType 
                AND VacationYear = :FisYear ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute(array(':IDNo' => $iDNo, ':PType' => $typeCode, ':FisYear' => ($fisYear + 543)));
        if ($result) {
            $dataset = $objQuery->fetchAll();
            if (count($dataset) == 1) {
                return $dataset[0]['VacationTransfer'];
            } else {
                return 0;
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo());
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function getVacPrivilege($DBType)
    {
        //echo $DBType;
        $arrPriv['CS'] = array('sick_day' => 23, 'vacation_day' => 10);
        $arrPriv['LB'] = array('sick_day' => 23, 'vacation_day' => 10);
        $arrPriv['LO'] = array('sick_day' => 46, 'vacation_day' => 10);
        $arrPriv['LT'] = array('sick_day' => 45, 'vacation_day' => 10);
        $arrPriv['ME'] = array('sick_day' => 45, 'vacation_day' => 10);
        $this->arrVacPrivilege = $arrPriv[$DBType];
        return $this->arrVacPrivilege;
    }

    private function getVacationBalance($crn_idCard, $DBType)
    {
        $myVacationPrivilege = $this->getVacPrivilege($DBType);
        $myVacationPrivilege['vacation_tranf'] = $this->getVacTransfer($crn_idCard, $DBType);
        return (object) $myVacationPrivilege;
    }

    public function xhrGetBalance()
    {
        $rsSecure = $this->checkSecure();
        $responseArray = array();
        if ($rsSecure->obj_result) {
            if (isset($_POST['prm_fisyear'])) {
                $fisyear = substr(filter_input(INPUT_POST, 'prm_fisyear', FILTER_SANITIZE_STRING), 0, 13);
            } else {
                $fisyear = $this->getCurrentFisYear();
            }
            $crn_idCard = substr(filter_input(INPUT_POST, 'subid', FILTER_SANITIZE_STRING), 0, 13);
            $personDetials = $this->getPersonal($crn_idCard);
            $holidayTrans = $this->getVacationBalance($crn_idCard, $personDetials->DBType);
            $quota = array(
                'sick_days' => $holidayTrans->sick_day + 0.0, 'holiday_days' => $holidayTrans->vacation_day + 0.0, 'holiday_tranfer' => $holidayTrans->vacation_tranf + 0.0, 'holiday_all' => ($holidayTrans->vacation_day + $holidayTrans->vacation_tranf) + 0.0
            );
            $objSickPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '1');
            $objMissPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '2');
            $objVacationPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '5');
            $currentRound = array(
                1 => $objSickPass->Vdays + 0.0, 2 => $objMissPass->Vdays + 0.0, 5 => $objVacationPass->Vdays + 0.0
            );
            $objSickPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '1', true);
            $objVacationPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '2', true);
            $allFisYear = array(
                1 => $objSickPassAllYear->Vdays + 0.0, 2 => $objVacationPassAllYear->Vdays + 0.0
            );
            $used = array('current_round' => $currentRound, 'all_year' => $allFisYear);
            $responseArray['json_result'] = true;
            $responseArray['json_data'] = array('Type' => $personDetials->DBType, 'fisyear' => $fisyear + 543, 'quota' => $quota, 'used' => $used);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    public function xhrGetMyVaction()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $responseArray = array();
            $humanID = $rsSecure->obj_id;
            //$personDetails = $this->getPersonal($humanID);
            $sql = "SELECT DocumentID ,IDNo "
                . ",CONVERT(varchar,SDate, 103) AS SDate "
                . ",CONVERT(varchar,EDate, 103) AS EDate "
                . ",VType ,Reason ,VDay "
                . ",VFlag ,HalfDayM ,HalfDayE ,HalfDate ,VFlag1 ,HalfDay1M "
                . ",HalfDay1E ,HalfDate1 ,OrderDate "
                . ",Approved1 ,ApprovedBy1 ,ApprovedPost1 "
                . ",CONVERT(varchar,ApprovedDate1, 103) AS ApprovedDate1 "
                . ",Approved2 ,ApprovedBy2 ,ApprovedPost2 "
                . ",CONVERT(varchar,ApprovedDate2, 103) AS ApprovedDate2 "
                . ",Approved3 ,ApprovedBy3 ,ApprovedPost3 "
                . ",CONVERT(varchar,ApprovedDate3, 103) AS ApprovedDate3 "
                . ",Approved4 ,ApprovedBy4 ,ApprovedPost4 "
                . ",CONVERT(varchar,ApprovedDate4, 103) AS ApprovedDate4 "
                . ",Remark ,Status ,CancelDocID ,Medical ,MedicalDay "
                . ",HalfAllFlag ,DocID1 "
                . ",PersonType ,SLastDate ,ELastDate ,LastVDay ,Address "
                . ",Attention ,FulllName ,Position ,Division ,CheckedBy "
                . ",CONVERT(varchar,CheckedDate, 103) AS CheckedDate "
                . ",Comment ,Command ,SumVacDay ,SickDayPast "
                . ",BusyDayPast ,VacDayPast ,HRISRec ,HRISRecBy ,HRISRecDate "
                . ",AllSumVacDay ,AllVacDay ,AllSickDay ,AllBusyDay "
                . ",Medical_File "
                . "FROM All_VacationDocOnline "
                . "WHERE IDNo = :idcard "
                . "ORDER BY All_VacationDocOnline.SDate DESC";
            $objQuery = $this->mssql_db->prepare($sql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':idcard' => $humanID));
            if ($result) {
                $responseArray['json_result'] = true;
                $dataset = $objQuery->fetchAll();
                if (count($dataset) >= 1) {
                    $responseArray['json_data_total'] = count($dataset);
                    $responseArray['json_data'] = $this->prepareMyVacation($dataset);
                } else {
                    $responseArray['json_data_total'] = 0;
                    $responseArray['json_data'] = null;
                }
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = array('error' => $objQuery->errorInfo());
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_error'] = 'Permission Denied. Login Fails.';
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    private function prepareMyVacation($allDataArray)
    {
        $returnArray = array();
        $allHead = $this->getAllHead();
        $vaType = array('1' => 'ลาป่วย', '2' => 'ลากิจ', '5' => 'ลาพักผ่อน');
        foreach ($allDataArray as $key => $value) {
            $stat_text = '';
            if ($value['HRISRec']) {
                $stat_text = 'HRIS';
            } else {
                if ($value['Status'] == '1') {
                    $stat_text = 'NORM';
                } elseif ($value['Status'] == '2') {
                    $stat_text = 'RJCT';
                }
            }
            if (strlen($value['CheckedBy']) > 0) {
                $checkedStat = array('approve' => true, 'date' => $value['CheckedDate']);
            } else {
                $checkedStat = array('approve' => false, 'date' => null);
            }
            /* approved4 */
            if ($value['Approved4']) {
                $approvedStat4 = array('approve' => true, 'by' => $allHead[$value['ApprovedBy4']], 'date' => $value['ApprovedDate4']);
            } else {
                if (strlen($value['ApprovedBy4']) == 13 && !is_null($value['ApprovedDate4'])) {
                    $approvedStat4 = array('approve' => false, 'by' => $allHead[$value['ApprovedBy4']], 'date' => $value['ApprovedDate4']);
                } else {
                    $approvedStat4 = array('approve' => false, 'by' => null, 'date' => null);
                }
            }
            /* approved3 */
            if ($value['Approved3']) {
                $approvedStat3 = array('approve' => true, 'by' => $allHead[$value['ApprovedBy3']], 'date' => $value['ApprovedDate3']);
            } else {
                if (strlen($value['ApprovedBy3']) == 13 && !is_null($value['ApprovedDate3'])) {
                    $approvedStat3 = array('approve' => false, 'by' => $allHead[$value['ApprovedBy3']], 'date' => $value['ApprovedDate3']);
                } else {
                    $approvedStat3 = array('approve' => false, 'by' => null, 'date' => null);
                }
            }
            /* approved2 */
            if ($value['Approved2']) {
                $approvedStat2 = array('approve' => true, 'by' => $allHead[$value['ApprovedBy2']], 'date' => $value['ApprovedDate2']);
            } else {
                if (strlen($value['ApprovedBy2']) == 13 && !is_null($value['ApprovedDate2'])) {
                    $approvedStat2 = array('approve' => false, 'by' => $allHead[$value['ApprovedBy2']], 'date' => $value['ApprovedDate2']);
                } else {
                    $approvedStat2 = array('approve' => false, 'by' => null, 'date' => null);
                }
            }
            /* approved1 */
            if ($value['Approved1']) {
                $approvedStat1 = array('approve' => true, 'by' => $allHead[$value['ApprovedBy1']], 'date' => $value['ApprovedDate1']);
            } else {
                if (strlen($value['ApprovedBy1']) == 13 && !is_null($value['ApprovedDate1'])) {
                    $approvedStat1 = array('approve' => false, 'by' => $allHead[$value['ApprovedBy1']], 'date' => $value['ApprovedDate1']);
                } else {
                    $approvedStat1 = array('approve' => false, 'by' => null, 'date' => null);
                }
            }
            $returnArray[] = array(
                'start' => $value['SDate'], 'end' => $value['EDate'],
                'days' => $value['VDay'], 'type' => $vaType[$value['VType']],
                'checked' => $checkedStat, 'approved4' => $approvedStat4,
                'approved3' => $approvedStat3, 'approved2' => $approvedStat2,
                'approved1' => $approvedStat1, 'status' => $stat_text
            );
        }
        return $returnArray;
    }

    private function getAllHead()
    {
        $strField = 'IDNo, FName, LName, EMPID, RealDivCode, RealSectCode, RealSubsectCode, RealWorkSiteCode, LevelID, AdminID ';
        $templateField = 'MUL.IDNo, PST.FName, PST.LName, MUL.EMPID, MUL.RealDivCode, MUL.RealSectCode, MUL.RealSubsectCode, MUL.RealWorkSiteCode, MUL.LevelID, MUL.AdminID';
        $sql = "SELECT * FROM (
                    SELECT 'CS' AS DBType, $strField FROM CS_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'CSM' AS DBType, " . str_replace('PST.', 'CSP.', str_replace('MUL.', 'CSM.', $templateField)) . " FROM CS_MultipleHead CSM
                     INNER JOIN CS_Person CSP ON CSP.IDNo = CSM.IDNo) 
                    UNION
                    SELECT 'LB' AS DBType, $strField FROM LB_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'LBM' AS DBType, " . str_replace('PST.', 'LBP.', str_replace('MUL.', 'LBM.', $templateField)) . " FROM LB_MultipleHead LBM
                     INNER JOIN LB_Person LBP ON LBP.IDNo = LBM.IDNo)  
                    UNION
                    SELECT 'LO' AS DBType, $strField FROM LO_Person WHERE AdminID IS NOT NULL 
                    UNION
                    (SELECT 'LOM' AS DBType, " . str_replace('PST.', 'LOP.', str_replace('MUL.', 'LOM.', $templateField)) . " FROM LO_MultipleHead LOM
                     INNER JOIN LO_Person LOP ON LOP.IDNo = LOM.IDNo)  
                  ) Person
                  WHERE LevelID <= 5 ";
        $objQuery = $this->mssql_db->prepare($sql);
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetchAll();
            foreach ($dataset as $key => $value) {
                $arrDataset[$value['IDNo']] = $value['FName'];
            }
            return $arrDataset;
        } else {
            $responseArray = array();
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo(), 'at' => 'getAllHead() fnc.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }



    public function xhrGetVacationOnline()
    {
        /*
        $aa = getVaDays($sumSickPassedObj); //ลาป่วย
        $all_aa = getVaDays($sumAllYearSickPassedObj); //ลาป่วยทั้งปี
        $bb = getVaDays($sumMissPassedObj); //ลากิจ
        $all_bb = getVaDays($sumAllYearMissPassedObj); //ลากิจทั้งปี
        $cc = getVaDays($sumVacPassedObj); //ลาพักผ่อน
        $holidayBalance = ($vacationBalance->vacation_day + $vacationBalance->vacation_tranf) - $cc;//สิทธิ์ลาพักร้อนคงเหลือ
        $vacationBalance_next = $vacationClass->getVacBalanceDay($vacationIDNo, $nextFistYear);
        */
        $rsSecure = $this->checkSecure();
        $responseArray = array();
        if ($rsSecure->obj_result) {
            if (isset($_POST['prm_fisyear'])) {
                $fisyear = substr(filter_input(INPUT_POST, 'prm_fisyear', FILTER_SANITIZE_STRING), 0, 13);
            } else {
                $fisyear = $this->getCurrentFisYear();
            }

            $crn_idCard = $rsSecure->obj_id;
            $personDetials = $this->getPersonal($crn_idCard);
            $holidayTrans = $this->getVacationBalance($crn_idCard, $personDetials->DBType);
            $quota = array(
                'sick_days' => $holidayTrans->sick_day, 'holiday_days' => $holidayTrans->vacation_day, 'holiday_tranfer' => (float) $holidayTrans->vacation_tranf, 'holiday_all' => ($holidayTrans->vacation_day + $holidayTrans->vacation_tranf)
            );
            //print_r($quota);
            $objSickPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '1');
            $objMissPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '2');
            $objVacationPass = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '5');
            $currentRound = array(
                1 => (float) $objSickPass->Vdays, 2 => (float) $objMissPass->Vdays, 5 => (float) $objVacationPass->Vdays
            );
            $objSickPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '1', true);
            $objVacationPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, $fisyear, '2', true);
            $allFisYear = array(
                1 => (float) $objSickPassAllYear->Vdays, 2 => (float) $objVacationPassAllYear->Vdays
            );
            $used = array('current_round' => $currentRound, 'all_year' => $allFisYear);

            $aa = ($sumSickPassedObj->Vdays > 0 ? $sumSickPassedObj->Vdays : 0); //ลาป่วย
            $all_aa = ($sumAllYearSickPassedObj->Vdays > 0 ? $sumAllYearSickPassedObj->Vdays : 0); //ลาป่วยทั้งปี
            $bb = ($sumMissPassedObj->Vdays > 0 ? $sumMissPassedObj->Vdays : 0); //ลากิจ
            $all_bb = ($sumAllYearMissPassedObj->Vdays > 0 ? $sumAllYearMissPassedObj->Vdays : 0); //ลากิจทั้งปี
            $cc = ($sumVacPassedObj->Vdays > 0 ? $sumVacPassedObj->Vdays : 0); //ลาพักผ่อน
            $responseArray['json_result'] = true;
            $responseArray['json_data'] = array('Type' => $personDetials->DBType, 'fisyear' => ($fisyear + 543), 'quota' => $quota, 'used' => $used);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    // * สร้าง Function ชื่อ xhrGetSumVacation ใน model vacation เพื่อให้ controller เรียกเข้ามา 
    public function xhrGetSumVacation()
    {
        // ! ตรวจสอบความปลอดภัยของการเข้าใช้ API ใน function checkSecure ที่เรียไว้ใน Model หลัก
        $rsSecure = $this->checkSecure();
        $responseArray = array();
        $arrBetweenDate = array();
        // TODO: ตรวจสอบค่าที่ได้รับกลับมาจากการตรวจสอบความปลอดภัย ว่าเป็น true หรือไม่
        if ($rsSecure->obj_result) {
            // TODO: เก็บค่าเลขบัตรประชาชนที่ได้จากการตรวจสอบความปลอดภัยไว้ในตัวแปร
            $crn_idCard = $rsSecure->obj_id;
            // TODO: สืบค้นข้อมูลบุคคลจากเลขบัตรที่ได้มา โดยส่ง param ไปที่ function getPersonal ที่ได้เขียนไว้
            $personDetials = $this->getPersonal($crn_idCard);
            // TODO: สืบค้นข้อมูลวันลาพักผ่อนที่สะสมมา โดยส่ง param ไปที่ function getVacationBalance ที่ได้เขียนไว้ เก็บไว้ในตัวแปร holidayTrans
            $holidayTrans = $this->getVacationBalance($crn_idCard, $personDetials->DBType);
            // TODO: นำข้อมูลที่ return จาก getVacationBalance เก็บเข้าตัวแปรแบบ array ชื่อ quota
            $quota = array(
                'sick_days' => $holidayTrans->sick_day + 0.0, 'holiday_days' => $holidayTrans->vacation_day + 0.0, 'holiday_tranfer' => $holidayTrans->vacation_tranf + 0.0, 'holiday_all' => ($holidayTrans->vacation_day + $holidayTrans->vacation_tranf) + 0.0
            );
            // TODO: สืบค้นข้อมูลวันลาป่วย โดยส่ง Parameter ไปที่ function getSumVac เป็น เลขบัตรประชาชน ประเภทการจ้าง ปีปัจจุบัน ในรอบประเมินปัจจุบัน
            $objSickPass = $this->getSumVac($crn_idCard, $personDetials->DBType, date("Y"), '1');
            $arrBetweenDate['vac_1_round'] = $this->between_date;
            // TODO: สืบค้นข้อมูลวันลากิจ โดยส่ง Parameter ไปที่ function getSumVac เป็น เลขบัตรประชาชน ประเภทการจ้าง ปีปัจจุบัน ในรอบประเมินปัจจุบัน
            $objMissPass = $this->getSumVac($crn_idCard, $personDetials->DBType, date("Y"), '2');
            $arrBetweenDate['vac_2_round'] = $this->between_date;

            // TODO: นำข้อมูลที่ return จาก getSumVac เก็บเข้าตัวแปรแบบ array ชื่อ currentRound (รอบการประเมินปัจจุบัน เฉพาะข้าราชการ ลูกจ้างประจำ)
            $currentRound = array(
                1 => $objSickPass->Vdays + 0.0, 2 => $objMissPass->Vdays + 0.0
            );
            // TODO: สืบค้นข้อมูลวันลาป่วย โดยส่ง Parameter ไปที่ function getSumVac เป็น เลขบัตรประชาชน ประเภทการจ้าง ปีงบปัจจุบัน
            $objSickPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, date("Y"), '1', true);
            $arrBetweenDate['vac_1_full'] = $this->between_date;
            // TODO: สืบค้นข้อมูลวันลากิจ โดยส่ง Parameter ไปที่ function getSumVac เป็น เลขบัตรประชาชน ประเภทการจ้าง ปีงบปัจจุบัน
            $objVacationPassAllYear = $this->getSumVac($crn_idCard, $personDetials->DBType, date("Y"), '2', true);
            $arrBetweenDate['vac_2_full'] = $this->between_date;
            // TODO: สืบค้นข้อมูลวันลาพักผ่อน โดยส่ง Parameter ไปที่ function getSumVac เป็น เลขบัตรประชาชน ประเภทการจ้าง ปีปัจจุบัน
            $objVacationPass = $this->getSumVac($crn_idCard, $personDetials->DBType, date("Y"), '5', true);
            $arrBetweenDate['vac_5'] = $this->between_date;
            // TODO: นำข้อมูลที่ return จาก getSumVac เก็บเข้าตัวแปรแบบ array ชื่อ allFisYear (การลาตลอดปีงบประมาณ)
            $allFisYear = array(
                1 => $objSickPassAllYear->Vdays + 0.0, 2 => $objVacationPassAllYear->Vdays + 0.0, 5 => $objVacationPass->Vdays + 0.0
            );
            // TODO: นำข้อมูลที่ ทั้งรอบการประเมิน และตลอดปีงบประมาณ เก็บใน array used หมายถึงการลาที่ได้ใช้ไปแล้ว
            $used = array('current_round' => $currentRound, 'all_year' => $allFisYear);

            /**
             * * ทำการสืบค้นข้อมูลวันลา (วันที่ลา ทุกวัน เป็นวันที่ เดือน ปี เพื่อใช้ในการปิดการเลือก ในตาราง calendar ในหน้าจอของลาออนไลน์)
             */
            // TODO: สืบค้นข้อมูลวันที่ ที่ลาทุกชนิด โดยส่ง Parameter ไปที่ function getHistoryVacDays เป็น เลขบัตรประชาชน ประเภทการจ้าง และปีปัจจุบัน
            $objVacationDays = $this->getHistoryVacDays($crn_idCard, $personDetials->DBType, date("Y"));
            // TODO: เตรียมข้อมูลเพื่อส่งออก API ในรูปแบบ Array ชื่อ responseArray
            $responseArray['json_result'] = true;
            //$responseArray['json_cond'] = $arrBetweenDate;
            $responseArray['json_data'] = array('Type' => $personDetials->DBType, 'quota' => $quota, 'used' => $used, 'used_days' => $objVacationDays);
            // TODO: ส่งออกข้อมูล ชื่อ responseArray ในรูปแบบ JSON
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }


    // * สร้าง Function ชื่อ getVacationedDays ใน model vacation เพื่อเรียกใช้ใน model vacation เองจึงตั้งเป็น private
    // * พร้อมรับค่า บัตรประชาชน และประเภทการจ้าง
    private function getVacationedDays($idcard, $pType)
    {
        // TODO: รับข้อมูลรูปแบบการจ้าง แล้วนำมากำหนดตารางในการสืบค้นข้อมูล ประวัติการลา
        $vacHisTable = $pType . "_VacationHistory";
        // TODO: รับข้อมูลรูปแบบการจ้าง แล้วนำมากำหนดตารางในการสืบค้นข้อมูล บุคคล
        $perTable = $pType . "_Person";
        // TODO: เขียนคำสืบค้นข้อมูลในตารางวันลา
        $sql = "SELECT vh.VType, "
            . "convert(varchar, vh.SDate, 23) AS SDate, vh.SDate AS SSDate "
            . "FROM {$vacHisTable} vh "
            . "INNER JOIN {$perTable} mp ON mp.EMPID = vh.EmpID "
            . "WHERE mp.IDNo = :idcard ";
        $objQuery = $this->mssql_db->prepare($sql);
        // TODO: แทนค่า การสืบค้นข้อมูลด้วยบัตรประชาชน
        $objQuery->bindParam(":idcard", $idcard, PDO::PARAM_STR);
        // TODO: กำหนดคำสั่งการ fetch ข้อมูล
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        // TODO: คำสั่งการสืบค้นข้อมูล
        $result = $objQuery->execute();
        // TODO: หากสืบค้นสำเร็จ ให้ทำการ fetch ข้อมูล
        if ($result) {
            // TODO: fetch ข้อมูล
            $dataset = $objQuery->fetchAll();
            // TODO: สร้าง array เพื่อในในการส่งกลับข้อมูลไปยัง function หลัก
            $arrDataset = array();
            foreach ($dataset as $key => $row) {
                // TODO: นำวันที่ ที่ลา ในทุกๆ row ของข้อมูล ใส่ในตัวแปร array
                $arrDataset[] = $row['SDate'];
            }
            //print_r($arrDataset);exit();
            return $arrDataset;
        } else {
            // TODO: หากสืบค้นไม่สำเร็จ ทำการส่งกลับข้อมูลไปยังต้นทาง พร้อมทั้งบอกสาเหตุของการ  error
            $responseArray = array();
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo(), 'at' => 'getVacationedDays() fnc.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            // TODO: หยุดการทำงานหลังจากส่งข้อมูลกลับ
            exit();
        }
    }

    // * สร้าง Function ชื่อ getHolidayDate ใน model vacation เพื่อเรียกใช้ใน model vacation เองจึงตั้งเป็น private
    // * พร้อมรับค่า วันที่เริ่มต้นลา วันที่สิ้นสุดการลา
    private function getHolidayDate($startDate, $endDate = null)
    {
        // TODO: เขียนคำสืบค้นข้อมูลในตารางวันหยุดราชการ
        $sql = "SELECT convert(varchar, HDate, 23) AS HDate ,Description "
            . "FROM STD_Holidays "
            . "WHERE HDate >= CONVERT(datetime, :sDate , 23)";
        if (!is_null($endDate)) {
            $sql .= "AND HDate <= CONVERT(datetime, :eDate , 23) ";
        }
        $objQuery = $this->mssql_db->prepare($sql);
        // TODO: แทนค่าเงื่อนไขวันที่ ด้วยวันที่ที่เริ่มต้นลา
        $objQuery->bindParam(":sDate", str_replace('-', '', $startDate), PDO::PARAM_STR);
        if (!is_null($endDate)) {
            // TODO: แทนค่าเงื่อนไขวันที่ ด้วยวันที่สิ้นสุดการลา
            $objQuery->bindParam(":eDate", str_replace('-', '', $endDate), PDO::PARAM_STR);
        }
        // TODO: กำหนดคำสั่งการ fetch ข้อมูล
        $objQuery->setFetchMode(PDO::FETCH_ASSOC);
        // TODO: คำสั่งการสืบค้นข้อมูล
        $result = $objQuery->execute();
        // TODO: หากสืบค้นสำเร็จ ให้ทำการ fetch ข้อมูล
        if ($result) {
            // TODO: fetch ข้อมูล
            $dataset = $objQuery->fetchAll();
            // TODO: สร้าง array เพื่อในในการส่งกลับข้อมูลไปยัง function หลัก
            $arrDataset = array();
            foreach ($dataset as $key => $row) {
                // TODO: นำวันที่ในทุกๆ row ของข้อมูล ใส่ในตัวแปร array
                //echo $row['HDate'].$row['Description'];
                $arrDataset[] = $row['HDate'];
            }
            //exit();
            // TODO: ทำการส่งข้อมูลวันหยุดราชการกลับ
            return $arrDataset;
        } else {
            // TODO: หากสืบค้นไม่สำเร็จ ทำการส่งกลับข้อมูลไปยังต้นทาง พร้อมทั้งบอกสาเหตุของการ  error
            $responseArray = array();
            $responseArray['json_result'] = false;
            $responseArray['json_data'] = array('error' => $objQuery->errorInfo(), 'at' => 'getHolidayDate() fnc.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    // * สร้าง Function ชื่อ getCalDayBetween ใน model vacation เพื่อเรียกใช้ใน model vacation เองจึงตั้งเป็น private
    // * พร้อมรับค่า วันที่เริ่มต้นลา วันที่สิ้นสุดการลา บัตรประชาชน ประเภทการจ้าง และรูปแบบการทำงาน(เวลาปกติ หรือขึ้นเวร)
    private function getCalDayBetween($sDate, $eDate, $idcaard, $pType, $pShift)
    {
        // TODO: สืบค้นวันหยุดราชการ โดยส่งค่าวันที่เริ่มต้นลา และวันที่สิ้นสุดการลาไปเพื่อสืบค้น ในฟังก์ชัน getHolidayDate
        $holidayList = $this->getHolidayDate($sDate, $eDate);
        //print_r($holidayList);exit();
        // TODO: สืบค้นวันลาที่เคยลามาทั้งหมด โดยส่งค่าเลขบัตรประชาชน และประเภทการจ้าง ในฟังก์ชัน getVacationedDays
        $vacationList = $this->getVacationedDays($idcaard, $pType);
        // TODO: ทำการรวมวันหยุดราชการ ปละวันลาที่เคยลามาแล้ว เป็นชุดข้อมูลเดียวกัน
        $mergeHoliday = array_merge($holidayList, $vacationList);
        // TODO: กำหนดตัวแปร จำนวนวันที่ลา โดยให้เริ่มต้นที่ 0
        $dateDiff = 0;
        // TODO: กำหนดตัวแปร โดยแปลงค่าวันที่เริ่มต้นลาเป็น pointer ในการระบุ ใน loop while
        $pointerDate = strtotime($sDate);
        // TODO: กำหนดตัวแปร โดยแปลงค่าวันที่สิ้นสุดการลา เป็นตัวเทียบค่า ใน loop while
        $mktEDate = strtotime($eDate);
        // TODO: ให้ loop while ทำงาน โดยมีเงื่อนไข ทำงานตั้งแต่วันที่เริ่มต้นลา จนถึงวันที่สิ้นสุดการลา โดยทำการบวก 1 วัน ทุกรอบของ loop
        while ($pointerDate <= $mktEDate) {
            // TODO: ตรวจสอบว่าทำงานแบบเป็นเวร
            if ($pShift == 1 || $pShift == '1') {
                // TODO: ตรวจสอบว่าวันนี้ไม่ตรงกับวันที่ ที่เคยลามาแล้ว
                if (!in_array(date('Y-m-d', $pointerDate), $vacationList)) {
                    // TODO: บวกจำนวนวันลาขึ้นไป 1 วัน
                    $dateDiff++;
                }
                // TODO: ตรวจสอบว่าทำงานเวลาราชการปกติ
            } elseif ($pShift == 0 || $pShift == '0') {
                // TODO: หาวันของสัปดาห์ 0-6
                $weekDay = date('w', $pointerDate);
                // TODO: หากเป็นวันเสาร์ อาทิตย์ จะไม่ทำการบวกวันลาเพิ่ม
                if ($weekDay != 0 && $weekDay != 6) {
                    // TODO: หากไม่ใช่วันเสาร์ อาทิตย์ ทำการตรวจสอบว่า ไม่ตรงกับวันหยุดราชการ หรือวันลาที่เคยลามาแล้ว
                    if (!in_array(date('Y-m-d', $pointerDate), $mergeHoliday)) {
                        // TODO: หากไม่ตรงกับวันหยุดราชการ วันเสาร์-อาทิตย์ หรือวันลาที่เคยลามา บวกจำนวนวันลาขึ้นไป 1 วัน
                        $dateDiff++;
                    }
                }
                //echo date('Y-m-d', $pointerDate);
            }
            // TODO: ทำการบวก pointer ในการเทียบค่าเพิ่ม 1 วัน
            $pointerDate = strtotime('+1 days', $pointerDate);
        }
        //echo $dateDiff; exit();
        return $dateDiff;
    }

    // * สร้าง Function ชื่อ xhrGetSumVacation ใน model vacation เพื่อให้ controller เรียกเข้ามา เพื่อใช้่คำนวนจำนวนวันที่ลา
    public function xhrCalDayVacation()
    {
        // ! ตรวจสอบความปลอดภัยของการเข้าใช้ API ใน function checkSecure ที่สร้างไว้ใน Model หลัก
        $rsSecure = $this->checkSecure();
        $responseArray = array();
        $arrBetweenDate = array();
        // TODO: ตรวจสอบค่าที่ได้รับกลับมาจากการตรวจสอบความปลอดภัย ว่าเป็น true หรือไม่
        if ($rsSecure->obj_result) {
            // TODO: รับข้อมูลวันที่เริ่มต้น และวันที่สิ้นสุดของการลา
            $sDate = substr(filter_input(INPUT_POST, 'prmSDate', FILTER_SANITIZE_STRING), 0, 10);
            $eDate = substr(filter_input(INPUT_POST, 'prmEDate', FILTER_SANITIZE_STRING), 0, 10);
            $dFlag = substr(filter_input(INPUT_POST, 'prmFlag', FILTER_SANITIZE_STRING), 0, 3);

            // TODO: สืบค้นข้อมูลบุคคลจากเลขบัตรที่ได้มา โดยส่ง param ไปที่ function getPersonal ที่ได้เขียนไว้
            $personDetials = $this->getPersonal($rsSecure->obj_id);
            //print_r($personDetials);exit();
            // TODO: ตรวจสอบข้อมูลว่า ได้ส่ง parameter ที่ต้องการมาครบหรือไม่ทั้งวันที่เริ่มต้น วันที่สิ้นสุด และการลาเป็นแบบเต็มวันหรือครึ่งวัน
            if (isset($_POST['prmSDate']) && isset($_POST['prmEDate']) && isset($_POST['prmFlag'])) {
                //! หากส่ง parameter มาครบ ทำการคำนวนวัน
                // TODO: ส่ง parameter ที่รับมา เข้า function นับจำนวนวัน โดยตัดวันหยุดราชออก และรับข้อมูลกลับมา
                $resultDay = $this->getCalDayBetween($sDate, $eDate, $rsSecure->obj_id, $personDetials->DBType, $personDetials->Shift);
                // TODO: ตรวจสอบว่าเป็นการลาแบบเต็มวันหรือครึ่งวัน หากเป็นการลาครึ่งวันต้องลบจำนวนวันที่ลาออก
                if ($dFlag == 'hm' || $dFlag == 'he') {
                    $resultDay = $resultDay - 0.5;
                }
                // TODO: เตรียม array เพื่อทำการส่ง response ตอบกลับ
                $responseArray['json_result'] = true;
                $responseArray['json_data'] = array('day_diff' => $resultDay);
            } else {
                //! หากส่ง parameter มาไม่ครบ ทำหารส่ง response กลับพร้อมระบุปัญหา
                // TODO: เตรียม array เพื่อทำการส่ง response ตอบกลับ
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = null;
                // * ระบุปัญหาตอบกลับ หากไม่สามารถทำการคำนวนจำนวนวันที่ลาได้
                $responseArray['json_details'] = "ระบุ parameter ไม่ครบ";
            }
            // TODO: ส่งออกข้อมูล ชื่อ responseArray ในรูปแบบ JSON
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    public function xhrVactionOnDay()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            //exit();
            $adminID = filter_input(INPUT_POST, 'prmAdminID', FILTER_SANITIZE_STRING);
            $userType = filter_input(INPUT_POST, 'prmUType', FILTER_SANITIZE_STRING);
            $humanID = $rsSecure->obj_id;
            $personDetails = $this->getPersonal($humanID);
            if (strlen($adminID) > 0 && isset($_POST['prmAdminID']) && isset($_POST['prmUType'])) {
                // TODO: ตรวจสอบสิทธิธุรการ
                if ($userType == 'clerical') {
                    $rsPermission = $this->getHasPermiss($humanID, 'chv', $adminID);
                    if (!is_null($rsPermission)) {
                        $bossDetails = $this->getPersonal($rsPermission->pp_add_by);

                        $curentDivision = $this->getSelectedHead($bossDetails->IDNo, $bossDetails->DBType, $adminID);
                    }
                } elseif ($userType == 'manager') {
                    $curentDivision = $this->getSelectedHead($humanID, $personDetails->DBType, $adminID);
                }

                if (is_null($curentDivision)) {
                    $responseArray['json_result'] = false;
                    $responseArray['json_data'] = null;
                    $responseArray['json_error'] = array('error' => true, 'description' => 'you are not has permission on this admin id.');
                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                    exit();
                } else {
                    $responseArray['json_result'] = true;
                    $myLevelId = $curentDivision->LevelID;
                    $myDiv = $curentDivision->RealDivCode;
                    $mySect = $curentDivision->RealSectCode;
                    $mySubsect = $curentDivision->RealSubsectCode;
                    $myWorksite = $curentDivision->RealWorkSiteCode;
                }
                print_r($curentDivision);
                exit();
                $ix = 1;
                $arrForApprove = array();
                if ($myLevelId == '1') {
                    $arrForApprove[2] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '2') {
                    $arrForApprove[3] = array(1, 2, 5);
                    $arrForApprove[4] = array(5);
                    $arrForApprove[5] = array(5);
                    $arrForApprove[6] = array(5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '3') {
                    $arrForApprove[4] = array(1, 2, 5);
                    $arrForApprove[5] = array(1, 2, 5);
                    $arrForApprove[6] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '4') {
                    $arrForApprove[5] = array(1, 2, 5);
                    $arrForApprove[6] = array(1, 2, 5);
                    $arrForApprove["S6"] = array(1, 2, 5);
                }
                if ($myLevelId == '5') {
                    $arrForApprove[6] = array(1, 2, 5);
                }
                $tmpArray = array();
                foreach ($arrForApprove as $subLevel => $arrVType) {
                    //echo $myLevelId . $subLevel . $myDiv . $mySect . $mySubsect . $myWorksite.'<br>';
                    $resultMySubordinate = $this->getSubordinateList($myLevelId, $subLevel, $myDiv, $mySect, $mySubsect, $myWorksite);
                    //print_r($resultMySubordinate);
                    if ($subLevel == "S6") {
                        $subSpecial = true;
                    } else {
                        $subSpecial = false;
                    }
                    $arrPerson = $this->prepareArray($resultMySubordinate);
                    if (count($arrPerson[0]) > 0) {
                        $resultOwnSub = $this->getVacAprLst($myLevelId, $arrPerson, $arrVType, $subSpecial, $subLevel);
                        if (count($resultOwnSub) > 0) {
                            if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                                //print_r($resultOwnSub);
                            }
                            $tmpArray = array_merge($tmpArray, $resultOwnSub);
                            //$ix = showVacList($resultOwnSub, $arrPerson[1], $ix, $subLevel, $headArr, $vacationCodeArr, $myLevelId);
                        } else {
                            if ($_SERVER['REMOTE_ADDR'] == '192.168.25.30') {
                                //echo 'no body sub ';
                            }
                        }
                    }
                }
                $responseArray['json_data_total'] = count($tmpArray);
                $responseArray['json_data'] = $tmpArray;
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
            } else {
                $responseArray['json_result'] = false;
                $responseArray['json_data'] = null;
                $responseArray['json_error'] = array('error' => true, 'description' => 'some parameter missing.');
                echo json_encode($responseArray, JSON_PRETTY_PRINT);
            }
        } else {
            $responseArray['json_result'] = false;
            $responseArray['json_error'] = array('error' => true, 'description' => 'Permission Denied. Login Fails.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
        }
    }

    /* 
     * //! เริ่ม Code บันทึกลาออนไลน์ 
     * 
    */

    private function get_vacation_next_id()
    {
        $strSql = "SELECT max(DocumentID) AS maxid FROM All_VacationDocOnline";
        $objQuery = $this->mssql_db->prepare($strSql);
        //$objQuery->bindParam(":sDate", str_replace('-', '', $startDate), PDO::PARAM_STR);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            return (int) $dataset->maxid;
        } else {
            return false;
        }
    }
    //หาวันสุดท้ายที่เคยลา ตามประเภท
    private function getLastVac($objPerson, $vType, $fisYear)
    {
        $vacDocTable = $objPerson->DBType . "_VacationDoc";
        //YYYY-MM-DD
        //$qStart = ($fisYear - 1) . "-10-01 00:00:00";
        //$qEnd = $fisYear . "-09-30 00:00:00";
        //YYYY-DD-MM
        $qStart = ($fisYear - 1) . "-01-10 00:00:00";
        $qEnd = $fisYear . "-30-09 00:00:00";
        $strSql = "SELECT convert(varchar, SDate, 120) AS SDate, convert(varchar, EDate, 120) AS EDate, Vday, VType, rn FROM 
                    (
                    SELECT SDate,
                             EDate, 
                             Vday, 
                             VType,
                             row_number() over(partition by EmpID order by SDate desc) as rn
                    FROM {$vacDocTable}
                    WHERE (EmpID = :eMpID1) 
                    AND (CancelDocID IS NULL) 
                    AND (SDate >= :sDate1) 
                    AND (EDate <= :eDate1) 
                    AND (VType = :vType1)
                    AND (DocID NOT IN 
                                (
                               SELECT CancelDocID 
                               FROM {$vacDocTable}
                               WHERE (EmpID = :eMpID2) 
                               AND (CancelDocID IS NOT NULL) 
                               AND (SDate >= :sDate2) 
                               AND (EDate <= :eDate2)
                               AND (VType = :vType2)
                                )
                            )
                    ) tbl_Vacation WHERE rn ='1'";
        //$EMP_ID, $qStart, $qEnd, $vType, $EMP_ID, $qStart, $qEnd, $vType
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->bindParam(":eMpID1", $objPerson->EMPID, PDO::PARAM_STR);
        $objQuery->bindParam(":sDate1", $qStart, PDO::PARAM_STR);
        $objQuery->bindParam(":eDate1", $qEnd, PDO::PARAM_STR);
        $objQuery->bindParam(":vType1", $vType, PDO::PARAM_STR);

        $objQuery->bindParam(":eMpID2", $objPerson->EMPID, PDO::PARAM_STR);
        $objQuery->bindParam(":sDate2", $qStart, PDO::PARAM_STR);
        $objQuery->bindParam(":eDate2", $qEnd, PDO::PARAM_STR);
        $objQuery->bindParam(":vType2", $vType, PDO::PARAM_STR);

        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                return $dataset;
            } else {
                return null;
            }
        } else {
            return $objQuery->errorInfo();
            //return false;
        }
    }

    private function getAddress($objPerson)
    {
        $addressTable = $objPerson->DBType . "_Address";
        $strSql = "SELECT EMPID 
                        ,CAST(adr.Address1 AS Text) As Address1 
                        ,CAST(adr.Address2 AS Text) As Tumbon 
                        ,CAST(adr.ZipCode AS Text) As ZipCode 
                        ,CAST(adr.TelHome AS Text) As TelHome 
                        ,CAST(adr.TelMobile AS Text) As TelMobile 
                        ,CAST(adr.email AS Text) As email 
                        ,CAST(adr.Amphur AS Text) As Amphur 
                        ,stdd.DName AS Province 
                  FROM {$addressTable} adr INNER JOIN STD_District stdd 
                  ON adr.PCode = stdd.PCode 
                  WHERE adr.EMPID = :eMpID AND adr.SeqNo ='1' 
                        AND stdd.CCode='140' AND stdd.DCode = '00';";
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->bindParam(":eMpID", $objPerson->EMPID, PDO::PARAM_STR);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                return $dataset;
            } else {
                return null;
            }
        } else {
            return $objQuery->errorInfo();
            //return false;
        }
    }

    private function getPosition($objPerson)
    {
        $personTable = $objPerson->DBType . "_Person";
        $postTable = $objPerson->DBType . "_POST";
        $strSql = "SELECT pstb.EMPID, postb.DisplayName,
                    postb.Posnum, postb.PosCodeSTD, stdpc.POS_NAME1, stdpc.POS_NAMEE,
                    stdjc.JobClassID, stdjc.JobClassName,
                    pstb.P4PGroup, stdpc.P4PGroup1
                    ,stdpc.NeedDegree, stdpc.P4PGroup2
                FROM {$personTable} pstb
                INNER JOIN {$postTable} postb
                ON pstb.Posnum = postb.Posnum
                INNER JOIN STD_PostCode stdpc
                ON postb.PosCodeSTD = stdpc.PosCode
                LEFT JOIN STD_JobClass stdjc
                ON pstb.JobClassID = stdjc.JobClassID WHERE pstb.EMPID = :eMpID;";
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->bindParam(":eMpID", $objPerson->EMPID, PDO::PARAM_STR);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                return $dataset;
            } else {
                return null;
            }
        } else {
            return $objQuery->errorInfo();
            //return false;
        }
    }

    private function getDivision($objPerson)
    {
        $strSql = "SELECT div_table.DIV, div_table.DIV_NAME, 
                    sect_table.SECT, sect_table.SECT_NAME, 
                    subsect_table.SUBSECT, subsect_table.SUBSECT_NAME, 
                    worksite_table.WORKSITE, worksite_table.WORKSITE_NAME
                    FROM 
                    (
                        (SELECT DIV, NAME1 AS DIV_NAME FROM STD_Divisions WHERE DIV IN ('001','002','003','004','005','006') AND SECT = '000' AND SUBSECT = '00' AND WORKSITE = '00') div_table
                        LEFT JOIN 
                        (SELECT DIV, SECT, CASE WHEN SECT = '000' THEN NULL ELSE NAME1 END AS SECT_NAME FROM STD_Divisions WHERE DIV IN ('001','002','003','004','005','006') AND SUBSECT = '00' AND WORKSITE = '00') sect_table
                        ON div_table.DIV = sect_table.DIV
                        LEFT JOIN
                        (SELECT DIV, SECT, SUBSECT, CASE WHEN SECT = '000' THEN NULL WHEN SUBSECT = '00' THEN NULL ELSE NAME1 END AS SUBSECT_NAME FROM STD_Divisions WHERE DIV IN ('001','002','003','004','005','006') AND WORKSITE = '00') subsect_table
                        ON sect_table.DIV = subsect_table.DIV AND sect_table.SECT = subsect_table.SECT 
                        LEFT JOIN
                        (SELECT DIV, SECT, SUBSECT, WORKSITE,  CASE WHEN SECT = '000' THEN NULL WHEN SUBSECT = '00' THEN NULL WHEN WORKSITE = '00' THEN NULL ELSE NAME1 END AS WORKSITE_NAME FROM STD_Divisions WHERE DIV IN ('001','002','003','004','005','006')) worksite_table 
                        ON subsect_table.DIV = worksite_table.DIV AND subsect_table.SECT = worksite_table.SECT AND subsect_table.SUBSECT = worksite_table.SUBSECT 
                    ) WHERE div_table.DIV = :div AND sect_table.SECT = :sect AND subsect_table.SUBSECT = :subsect AND worksite_table.WORKSITE = :worksite ;";

        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->bindParam(":div", $objPerson->RealDivCode, PDO::PARAM_STR);
        $objQuery->bindParam(":sect", $objPerson->RealSectCode, PDO::PARAM_STR);
        $objQuery->bindParam(":subsect", $objPerson->RealSubsectCode, PDO::PARAM_STR);
        $objQuery->bindParam(":worksite", $objPerson->RealWorkSiteCode, PDO::PARAM_STR);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                return $dataset;
            } else {
                return null;
            }
        } else {
            return $objQuery->errorInfo();
            //return false;
        }
    }

    private function getDateFormat($date)
    {
        if (strlen($date) == 10) {
            return $date . ' 00:00:00';
        } else {
            return null;
        }
    }



    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    private function putUploadMedicalFiles()
    {
        $input_filename = 'prmMedicalFile';

        $fileName  =  $_FILES[$input_filename]['name'];
        $tempPath  =  $_FILES[$input_filename]['tmp_name'];
        $fileSize  =  $_FILES[$input_filename]['size'];

        if (empty($fileName)) {
            $this->takeError("please select medical file.");
        } else {
            $upload_path = 'public/medic/'; // set upload folder path 

            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'pdf');

            // allow valid image file formats
            if (in_array($fileExt, $valid_extensions)) {
                //check file not exist our upload folder path
                $tempfile = uniqid() . "_" . date("Y-m-d") . '.' . $fileExt;
                if (!file_exists($upload_path . $tempfile)) {
                    // check file size '5MB'
                    if ($fileSize < 5000000) {
                        $ruselt_uplode = move_uploaded_file($tempPath, $upload_path . $tempfile);
                        if ($ruselt_uplode) {
                            return $tempfile;
                        }
                    } else {
                        $this->takeError("Sorry, your file is too large, please upload 3 MB size.");
                    }
                } else {
                    $this->takeError("Sorry, file already exists check upload folder.");
                }
            } else {
                $this->takeError("Sorry, only JPG, JPEG, PNG & PDF files are allowed.");
            }
        }
    }

    public function xhrVacationOnline()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $responseArray = array();
            $personDetails = $this->getPersonal($rsSecure->obj_id);
            $inputJSON = file_get_contents('php://input');
            $inputBody = json_decode($inputJSON, TRUE); //convert JSON into array
            $header = $this->getAuthorizationHeader();
            // $responseArray['CONTENT_TYPE'] = $_SERVER['CONTENT_TYPE'];
            // $responseArray['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
            // $responseArray['AUTHORIZATION_HEADER'] = $header;
            // $responseArray['BODY'] = $inputBody;
            //getLastVac
            $passedVal = true;
            if (!isset($_POST['prmFisyear'])) {
                $passedVal = false;
                $msg = 'prmFisyear';
            }
            if (!isset($_POST['prmVType'])) {
                $passedVal = false;
                $msg = 'prmVType';
            }
            if (!isset($_POST['prmSDate'])) {
                $passedVal = false;
                $msg = 'prmSDate';
            }
            if (!isset($_POST['prmEDate'])) {
                $passedVal = false;
                $msg = 'prmEDate';
            }
            if (!isset($_POST['prmVDay'])) {
                $passedVal = false;
                $msg = 'prmVDay';
            }
            if (!isset($_POST['prmDayType'])) {
                $passedVal = false;
                $msg = 'prmDayType';
            }
            if (!isset($_POST['prmHalfDate'])) {
                $passedVal = false;
                $msg = 'prmHalfDate';
            }
            if (!isset($_POST['prmMedical'])) {
                $passedVal = false;
                $msg = 'prmMedical';
            }
            if (!isset($_POST['prmMedicalDay'])) {
                $passedVal = false;
                $msg = 'prmMedicalDay';
            }
            if (!isset($_POST['prmReason'])) {
                $passedVal = false;
                $msg = 'prmReason';
            }

            if ($passedVal) {
                $responseArray['json_result'] = true;
                $arrPersonPointer = array('CS' => 1, 'LB' => 2, 'LO' => 3, 'LT' => 4, 'ME' => 5);
                $varFisyear = filter_input(INPUT_POST, 'prmFisyear', FILTER_VALIDATE_INT);
                $varVType = filter_input(INPUT_POST, 'prmVType', FILTER_VALIDATE_INT);
                $objposition = $this->getPosition($personDetails);
                $objLastVacation = $this->getLastVac($personDetails, $varVType, $varFisyear);
                $objAddress = $this->getAddress($personDetails);
                $objDivision = $this->getDivision($personDetails);
                $objSickPass = $this->getSumVac($personDetails->IDNo, $personDetails->DBType, $varFisyear, '1');
                $objMissPass = $this->getSumVac($personDetails->IDNo, $personDetails->DBType, $varFisyear, '2');
                $objVacationPass = $this->getSumVac($personDetails->IDNo, $personDetails->DBType, $varFisyear, '5');

                $personDetails->Position = $objposition->POS_NAME1 . $objposition->JobClassName;
                // $responseArray['PERSON'] = $personDetails;
                // $responseArray['POSITION'] = $objposition;
                $responseArray['LASTVACATION'] = $objLastVacation;
                // $responseArray['ADDRESS'] = $objAddress;
                // $responseArray['DIVISION'] = $objDivision;
                // $responseArray['SICK_PASS'] = $objSickPass;
                // $responseArray['MISS_PASS'] = $objMissPass;
                // $responseArray['VACATION_PASS'] = $objVacationPass;

                $varSDate = $this->getDateFormat(filter_input(INPUT_POST, 'prmSDate', FILTER_SANITIZE_STRING));
                $varEDate = $this->getDateFormat(filter_input(INPUT_POST, 'prmEDate', FILTER_SANITIZE_STRING));
                $varVDay = filter_input(INPUT_POST, 'prmVDay', FILTER_VALIDATE_INT);
                $varDayType = filter_input(INPUT_POST, 'prmDayType', FILTER_SANITIZE_STRING);
                if ($varVType == '1' && $varVDay >= 3) {
                    if (!isset($_FILES['prmMedicalFile'])) {
                        $msg = 'prmMedicalFile';
                        $this->takeError($msg . ' Param missing.');
                    }
                    $fileName = $_FILES['prmMedicalFile']['name'];
                    if (empty($fileName)) {
                        $this->takeError('No Medical file selected.');
                    }
                    $result_filename = $this->putUploadMedicalFiles();
                } else {
                    $result_filename = null;
                }

                if ($varDayType == 'all') {
                    $varVFlag = null;
                    $varHalfDayM = null;
                    $varHalfDayE = null;
                    $varHalfDate = null;
                } elseif ($varDayType == 'hm') {
                    $varVFlag = 1;
                    $varHalfDayM = 1;
                    $varHalfDayE = null;
                    $varHalfDate = $this->getDateFormat(filter_input(INPUT_POST, 'prmHalfDate', FILTER_SANITIZE_STRING));
                } elseif ($varDayType == 'he') {
                    $varVFlag = 1;
                    $varHalfDayM = null;
                    $varHalfDayE = 1;
                    $varHalfDate = $this->getDateFormat(filter_input(INPUT_POST, 'prmHalfDate', FILTER_SANITIZE_STRING));
                }
                $varMedical = filter_input(INPUT_POST, 'prmMedical', FILTER_VALIDATE_INT);
                $varMedicalDay = filter_input(INPUT_POST, 'prmMedicalDay', FILTER_VALIDATE_INT);
                $varHalfAllFlag = filter_input(INPUT_POST, 'prmHalfAllFlag', FILTER_VALIDATE_INT);


                $varPersonType = $arrPersonPointer[$personDetails->DBType];
                if (is_null($objLastVacation)) {
                    $varSLastDate = null;
                    $varELastDate = null;
                    $varLastVDay = null;
                } else {
                    $varSLastDate = $objLastVacation->SDate;
                    $varELastDate = $objLastVacation->EDate;
                    $varLastVDay = $objLastVacation->Vday;
                }

                $fullAddress = $objAddress->Address1 . ' ' . $objAddress->Amphur . ' ' . $objAddress->Tumbon . ' ' . $objAddress->Province . ' ' . $objAddress->ZipCode;
                $varAddress =  $fullAddress;
                $varAttention = '';
                switch ($personDetails->LevelID) {
                    case '6':
                        $varAttention_sick = 'เรียนหัวหน้า ' . $objDivision->SECT_NAME . ' (ผ่านหัวหน้า' . $objDivision->SUBSECT_NAME . ')';
                        $varAttention_vac = 'เรียน รองผู้อำนวยการ' . $objDivision->DIV_NAME . ' (ผ่านหัวหน้า' . $objDivision->SECT_NAME . ')';
                        break;
                    case '5':
                        $varAttention_sick = 'เรียนหัวหน้า ' . $objDivision->SECT_NAME;
                        $varAttention_vac = 'เรียน รองผู้อำนวยการ' . $objDivision->DIV_NAME;
                        break;
                    case '4':
                        $varAttention_sick = 'เรียนหัวหน้า ' . $objDivision->SECT_NAME;
                        $varAttention_vac = 'เรียน รองผู้อำนวยการ' . $objDivision->DIV_NAME;
                        break;
                    case '3':
                        $varAttention_sick = 'เรียน รองผู้อำนวยการ' . $objDivision->DIV_NAME;
                        $varAttention_vac = 'เรียน รองผู้อำนวยการ' . $objDivision->DIV_NAME;
                        break;
                    case '2':
                        $varAttention_sick = 'เรียน ผู้อำนวยการโรงพยาบาลราชวิถี';
                        $varAttention_vac = 'เรียน ผู้อำนวยการโรงพยาบาลราชวิถี';
                        break;
                    default:
                        $varAttention_sick = 'เรียน ผู้บังคับบัญชา';
                        $varAttention_vac = 'เรียน ผู้บังคับบัญชา';
                        break;
                }
                if ($varVType == '1' || $varVType == '2') {
                    $varAttention = $varAttention_sick;
                } elseif ($varVType == '5') {
                    $varAttention = $varAttention_vac;
                }
                $varDivision = $objDivision->WORKSITE_NAME . ' ' . $objDivision->SUBSECT_NAME . ' ' . $objDivision->SECT_NAME . ' ' . $objDivision->DIV_NAME;
                $varSickDayPast = $objSickPass->Vdays;
                $varBusyDayPast = $objMissPass->Vdays;
                $varVacDayPast = $objVacationPass->Vdays;
                $varReason = filter_input(INPUT_POST, 'prmReason', FILTER_SANITIZE_STRING);
                $varmedical_file = $result_filename;
                //echo 'varSDate=' . $this->getDateFormat($varSDate) . 'varEDate=' . $varEDate . 'varSLastDate=' . $varSLastDate . 'varELastDate=' . $varELastDate;
                $vacationResult = $this->putVacation($personDetails, $varSDate, $varEDate, $varVType, $varVDay, $varVFlag, $varHalfDayM, $varHalfDayE, $varHalfDate, $varMedical, $varMedicalDay, $varHalfAllFlag, $varPersonType, $varSLastDate, $varELastDate, $varLastVDay, $varAddress, $varAttention, $varDivision, $varSickDayPast, $varBusyDayPast, $varVacDayPast, $varReason, $varmedical_file);
                $vacationResult['result'] = true;
                if ($vacationResult['result']) {
                    $responseArray['json_data'] = array('vacation_id' => $vacationResult['vacation_id']);
                }
            } else {
                $this->takeError($msg . ' Param missing.');
            }
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        exit();
    }
    private function putVacation($objPerson, $SDate, $EDate, $VType, $VDay, $VFlag, $HalfDayM, $HalfDayE, $HalfDate, $Medical, $MedicalDay, $HalfAllFlag, $PersonType, $SLastDate, $ELastDate, $LastVDay, $Address, $Attention, $Division, $SickDayPast, $BusyDayPast, $VacDayPast, $Reason, $medical_file)
    {
        //! บันทึกลาออนไลน์
        $max_vacation_id = $this->get_vacation_next_id();
        if ($max_vacation_id !== false) {
            $next_vacation_id = $max_vacation_id + 1;
        }
        $prmStatus = '1';
        $prmFullname = $objPerson->FName . ' ' . $objPerson->LName;
        if ($VFlag == '1') {
            $strHalfDate = 'CONVERT(DATETIME, :HalfDate, 120) ';
        } else {
            $strHalfDate = ":HalfDate ";
        }
        if (is_null($SLastDate)) {
            $strSLastDate = ':SLastDate ';
        } else {
            $strSLastDate = 'CONVERT(DATETIME, :SLastDate, 120) ';
        }
        if (is_null($ELastDate)) {
            $strELastDate = ':ELastDate ';
        } else {
            $strELastDate = 'CONVERT(DATETIME, :ELastDate, 120) ';
        }
        $strSql = "INSERT INTO All_VacationDocOnline "
            . "(DocumentID, IDNo, SDate, "
            . "EDate,"
            . "VType, VDay, "
            . "VFlag, HalfDayM, HalfDayE, "
            . "HalfDate, "
            . "Status, Medical, MedicalDay, "
            . "HalfAllFlag, PersonType, "
            . "SLastDate, ELastDate, "
            . "LastVDay, Address, "
            . "Attention, FulllName, Position, Division, "
            . "SickDayPast, BusyDayPast, VacDayPast, "
            . "Reason, Medical_File ,IPAddress) "
            . "VALUES "
            . "(:DocumentID, :IDNo, CONVERT(DATETIME, :SDate, 120), "
            . "CONVERT(DATETIME, :EDate, 120), "
            . ":VType, :VDay, "
            . ":VFlag, :HalfDayM, :HalfDayE, "
            . "{$strHalfDate}, "
            . ":Status, :Medical, :MedicalDay, "
            . ":HalfAllFlag, :PersonType, "
            . "{$strSLastDate}, {$strELastDate}, "
            . ":LastVDay, :Address, "
            . ":Attention, :FulllName, :Position, :Division, "
            . ":SickDayPast, :BusyDayPast, :VacDayPast, "
            . ":Reason, :Medical_File, :IPAddress)";
        $objQuery = $this->mssql_db->prepare($strSql);
        $objQuery->bindParam(":DocumentID", $next_vacation_id, PDO::PARAM_STR);
        $objQuery->bindParam(":IDNo", $objPerson->IDNo, PDO::PARAM_STR);
        $objQuery->bindParam(":SDate", $SDate, PDO::PARAM_STR);
        $objQuery->bindParam(":EDate", $EDate, PDO::PARAM_STR);
        $objQuery->bindParam(":VType", $VType, PDO::PARAM_STR);
        $objQuery->bindParam(":VDay", $VDay, PDO::PARAM_STR);
        $objQuery->bindParam(":VFlag", $VFlag, PDO::PARAM_INT);
        if ($VFlag == '1') {
            $objQuery->bindParam(":HalfDayM", $HalfDayM, PDO::PARAM_STR);
            $objQuery->bindParam(":HalfDayE", $HalfDayE, PDO::PARAM_STR);
            $objQuery->bindParam(":HalfDate", $HalfDate, PDO::PARAM_STR);
        } else {
            $objQuery->bindParam(":HalfDayM", $HalfDayM, PDO::PARAM_INT);
            $objQuery->bindParam(":HalfDayE", $HalfDayE, PDO::PARAM_INT);
            $objQuery->bindParam(":HalfDate", $HalfDate, PDO::PARAM_INT);
        }
        // 
        $objQuery->bindParam(":Status", $prmStatus, PDO::PARAM_STR);
        $objQuery->bindParam(":Medical", $Medical, PDO::PARAM_STR);
        $objQuery->bindParam(":MedicalDay", $MedicalDay, PDO::PARAM_STR);
        $objQuery->bindParam(":HalfAllFlag", $HalfAllFlag, PDO::PARAM_STR);
        $objQuery->bindParam(":PersonType", $PersonType, PDO::PARAM_STR);
        if (is_null($SLastDate)) {
            $objQuery->bindParam(":SLastDate", $SLastDate, PDO::PARAM_INT);
        } else {
            $objQuery->bindParam(":SLastDate", $SLastDate, PDO::PARAM_STR);
        }
        if (is_null($ELastDate)) {
            $objQuery->bindParam(":ELastDate", $ELastDate, PDO::PARAM_INT);
        } else {
            $objQuery->bindParam(":ELastDate", $ELastDate, PDO::PARAM_STR);
        }
        $objQuery->bindParam(":LastVDay", $LastVDay, PDO::PARAM_STR);
        $objQuery->bindParam(":Address", $Address, PDO::PARAM_STR);
        $objQuery->bindParam(":Attention", $Attention, PDO::PARAM_STR);
        $objQuery->bindParam(":FulllName", $prmFullname, PDO::PARAM_STR);
        $objQuery->bindParam(":Position", $objPerson->Position, PDO::PARAM_STR);
        $objQuery->bindParam(":Division", $Division, PDO::PARAM_STR);
        $objQuery->bindParam(":SickDayPast", $SickDayPast, PDO::PARAM_STR);
        $objQuery->bindParam(":BusyDayPast", $BusyDayPast, PDO::PARAM_STR);
        $objQuery->bindParam(":VacDayPast", $VacDayPast, PDO::PARAM_STR);
        $objQuery->bindParam(":Reason", $Reason, PDO::PARAM_STR);
        $objQuery->bindParam(":Medical_File", $medical_file, PDO::PARAM_STR);
        $localIP = $_SERVER['REMOTE_ADDR'];
        $objQuery->bindParam(":IPAddress", $localIP, PDO::PARAM_STR);
        $result = $objQuery->execute();
        if ($result) {
            return array('result' => true, 'vacation_id' => $next_vacation_id);
        } else {
            $errorDetail = $objQuery->errorInfo();
            $responseArray['putVacation'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray);
            exit();
        }
    }
    public function xhrTestUpload()
    {
        $rsSecure = $this->checkSecure();
        if ($rsSecure->obj_result) {
            $responseArray = array();
            $dataArray = array();
            //$personDetails = $this->getPersonal($rsSecure->obj_id);
            //$inputJSON = file_get_contents('php://input');
            //$inputBody = json_decode($inputJSON, TRUE); //convert JSON into array
            //$header = $this->getAuthorizationHeader();
            $dataArray['CONTENT_TYPE'] = $_SERVER['CONTENT_TYPE'];
            //$dataArray['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
            //$dataArray['AUTHORIZATION_HEADER'] = $header;
            //$dataArray['BODY'] = $inputBody;
            if (!isset($_FILES['prmMedicalFile'])) {
                $msg = 'prmMedicalFile';
                $this->takeError($msg . ' Param missing.');
            }

            $responseArray['json_result'] = true;
            $fileName = $_FILES['prmMedicalFile']['name'];
            if (empty($fileName)) {
                $this->takeError('No Medical file selected.');
            }
            $result_filename = $this->putUploadMedicalFiles();
            $varmedical_file = $result_filename;
            $vacationResult['result'] = true;
            if ($vacationResult['result']) {
                $dataArray['uploaded'] = true;
                $dataArray['file'] = $varmedical_file;
                $dataArray['path'] = 'https://hrws.rajavithi.go.th/mvc/public/medic/' . $varmedical_file;
                $responseArray['json_data'] = $dataArray;
            }
        }
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        exit();
    }
}
