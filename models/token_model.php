<?php

/**
 * normal
 * * fdfdsfdsfsf
 * ! sdfdsfsdf
 * ?dfsdfs
 * TODO: fdkfkdjsf
 * @param myparam is 
 */
class Token_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }



    private function abs_verify($user, $pass)
    {
        // $resultPointer = 'ID_CARDResult';
        // $client = new SoapClient("http://192.168.1.26/ws_his.asmx?WSDL");
        // $params = array(
        //     'id' => $user,
        //     'pass' => $pass
        // );
        // $result = $client->ID_CARD($params);

        $resultPointer = 'ChkLoginResult';
        // $client = new SoapClient("http://192.168.1.135:8088/webServiceReg/?wsdl");
        $client = new SoapClient(
            "http://192.168.1.135:8088/webServiceReg/?wsdl",
            array(
                "trace"      => 1,        // enable trace to view what is happening
                "exceptions" => 0,        // disable exceptions
                "cache_wsdl" => 0
            )         // disable any caching on the wsdl, encase you alter the wsdl server
        );
        $code = "7a785ee2b0cc44a43dda527c6e569ed4";
        $params = array(
            'userid' => $user,
            'password' => $pass,
            'code' => $code
        );
        $result = $client->ChkLogin($params);
        $unbox = get_object_vars($result);
        $objResult = $unbox[$resultPointer];
        $arrayResult = $objResult->string;
        //var_dump($arrayResult);
        // exit();

        $response = array();
        if ($arrayResult[0] === "true") {
            $response[0] = TRUE;
            $response[1] = $arrayResult[2]; //บัตรประชาชน
            $response[2] = $arrayResult[3]; //เลข จนท ABS
        } else {
            $responseArray = array('json_result' => false, 'json_details' => 'Login failed. User or Password Incorrect.');
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }

        return $response;
    }

    private function putLog($user_id)
    {
        $maxSaltID = $this->getMaxSalt();
        $nextSaltID = $maxSaltID + 1;
        $saltString = uniqid();
        $strSql = "INSERT INTO ENT_SALT (SALT_ID, SALT_USER, SALT_STRING) VALUES (:salt_id , :salt_user, :salt_string)";
        $objQuery = $this->oracle_db->prepare($strSql);
        $objQuery->bindParam(":salt_id", $nextSaltID, PDO::PARAM_STR);
        $objQuery->bindParam(":salt_string", $saltString, PDO::PARAM_STR);
        $result = $objQuery->execute();
        if ($result) {
            return array($nextSaltID, $saltString);
        } else {
            $errorDetail = $objQuery->errorInfo();
            $responseArray = array('json_result' => false);
            $responseArray['putSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function getMaxSalt()
    {
        $strSql = "SELECT MAX(SALT_ID) AS SALT_ID FROM ENT_SALT";
        $objQuery = $this->oracle_db->prepare($strSql);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                if ($dataset->SALT_ID === NULL) {
                    return 0;
                } else {
                    return $dataset->SALT_ID;
                }
            } else {
                return 0;
            }
        } else {
            $errorDetail = $objQuery->errorInfo();
            $responseArray = array('json_result' => false);
            $responseArray['getMaxSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function putSalt($his_user)
    {
        $maxSaltID = $this->getMaxSalt();
        $nextSaltID = $maxSaltID + 1;
        $saltString = uniqid();
        $strSql = "INSERT INTO ENT_SALT (SALT_ID, SALT_USER, SALT_STRING) VALUES (:salt_id , :salt_user, :salt_string)";
        $objQuery = $this->oracle_db->prepare($strSql);
        $objQuery->bindParam(":salt_id", $nextSaltID, PDO::PARAM_STR);
        $objQuery->bindParam(":salt_user", $his_user, PDO::PARAM_STR);
        $objQuery->bindParam(":salt_string", $saltString, PDO::PARAM_STR);
        $result = $objQuery->execute();
        if ($result) {
            return array($nextSaltID, $saltString);
        } else {
            $errorDetail = $objQuery->errorInfo();
            $responseArray = array('json_result' => false);
            $responseArray['putSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    private function getHasSalt($his_user)
    {
        $strSql = "SELECT * FROM ENT_SALT WHERE SALT_USER = :hisuser";
        $objQuery = $this->oracle_db->prepare($strSql);
        $objQuery->bindParam(":hisuser", $his_user, PDO::PARAM_STR);
        $objQuery->setFetchMode(PDO::FETCH_OBJ);
        $result = $objQuery->execute();
        if ($result) {
            $dataset = $objQuery->fetch();
            if ($dataset !== false) {
                return array($dataset->SALT_ID, $dataset->SALT_STRING);
            } else {
                $saltArray = $this->putSalt($his_user);
                return $saltArray;
            }
        } else {
            $errorDetail = $objQuery->errorInfo();
            $responseArray = array('json_result' => false);
            $responseArray['getHasSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    // private function getSalt($salt_id)
    // {
    //     $strSql = "SELECT * FROM ENT_SALT WHERE SALT_ID = :salt_id";
    //     $objQuery = $this->oracle_db->prepare($strSql);
    //     $objQuery->bindParam(":salt_id", $salt_id, PDO::PARAM_STR);
    //     $objQuery->setFetchMode(PDO::FETCH_OBJ);
    //     $result = $objQuery->execute();
    //     if ($result) {
    //         $dataset = $objQuery->fetch();
    //         if ($dataset !== false) {
    //             return $dataset;
    //         } else {
    //             $errorDetail = $objQuery->errorInfo();
    //             $responseArray = array('json_result' => false);
    //             $responseArray['getSalt'] = array('title' => 'get salt error', 'description' => 'can\'t find salt in system.');
    //             echo json_encode($responseArray, JSON_PRETTY_PRINT);
    //             exit();
    //         }
    //     } else {
    //         $errorDetail = $objQuery->errorInfo();
    //         $responseArray = array('json_result' => false);
    //         $responseArray['getSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
    //         echo json_encode($responseArray, JSON_PRETTY_PRINT);
    //         exit();
    //     }
    // }

    private function getName($staffId)//แก้ข้อมูลที่ส่งไปกับ Token
    {
        $strSql = "select STAFF, LCT , DSPNAME, wrkdivnm, POSNM, idcrd from rjvt.staff where STAFF = :staffid and canceldate is null";
        $objQuery = $this->oracle_his->prepare($strSql);
        $objQuery->bindParam(":staffid", $staffId, PDO::PARAM_STR);
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
            $errorDetail = $objQuery->errorInfo();
            $responseArray = array('json_result' => false);
            $responseArray['getHasSalt'] = array('title' => 'execute error', 'description' => $errorDetail[2]);
            echo json_encode($responseArray, JSON_PRETTY_PRINT);
            exit();
        }
    }

    public function login()
    {
        $responseArray = array();
        $logParam = $this->getBody();
        // $responseArray['body'] = $logParam;
        if (count($logParam) == 0) {
            $this->takeError('No body JSON Param found.');
        }
        if (!isset($logParam['user'])) {
            $this->takeError('No some JSON Param found.');
        }
        if (!isset($logParam['pwd'])) {
            $this->takeError('No some JSON Param found.');
        }
        $loginResult = $this->abs_verify($logParam['user'], $logParam['pwd']);
        if ($loginResult[0] === TRUE) {
            //print_r($loginResult);
            $resultSalt = $this->getHasSalt($logParam['user']);
        }
        $responseArray['json_result'] = $loginResult[0];
        // $responseArray['id'] = $resultSalt[0];
        // $responseArray['salt'] = $resultSalt[1];
        $objName = $this->getName($loginResult[2]);
        //print_r($objName);
        $result_jwt = $this->genToken('ByShadoWoARM', $resultSalt[0], $resultSalt[1], $loginResult[2], $objName);
        $responseArray['access_token'] = $result_jwt;

        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }

    public function chkToken()
    {
        $responseArray = array();
        $currentToken = $this->getBearerToken();
        //$responseArray['access_token'] = $currentToken;
        $arrToken = explode('.', $currentToken);
        $payload = json_decode(base64_decode($arrToken[1]));
        //$responseArray['payload'] = $payload;
        $responseArray['exp'] = date("Y-m-d H:i:s", $payload->exp);
        echo json_encode($responseArray, JSON_PRETTY_PRINT);
    }
}
