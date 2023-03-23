<?php

class Model
{

    function __construct()
    {
        try {
            $this->oracle_db = new Oracle_Database();
        } catch (PDOException $ex) {
            echo 'Connection Oracle 90 failed: ' . $ex->getMessage();
            exit();
        }

        try {
            $this->oracle_his = new Oracle_His();
        } catch (PDOException $ex) {
            echo 'Connection Oracle HIS failed: ' . $ex->getMessage();
            exit();
        }

        try {
            $this->oracle_webintra = new Oracle_WEBINTRA();
        } catch (PDOException $ex) {
            echo 'Connection Oracle WEBINTRA failed: ' . $ex->getMessage();
            exit();
        }

        try {
            $this->oracle_webout = new Oracle_WEBOUT();
        } catch (PDOException $ex) {
            echo 'Connection Oracle WEBOUT failed: ' . $ex->getMessage();
            exit();
        }
    }

    function takeError($errorText)
    {
        $responseArray = array();
        $responseArray['json_result'] = FALSE;
        $responseArray['json_data'] = array('error' => $errorText);
        echo json_encode($responseArray);
        exit();
    }

    function getBody()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') {
            $inputJSON = file_get_contents('php://input');
            return json_decode($inputJSON, TRUE);
        }
    }

    /** 
     * Get header Authorization
     * */
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

    /**
     * get access token from header
     * */
    protected function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    private function base64url_encode($data)//แก้ข้อมูลที่ส่งไปกับ Token
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function genToken($appName, $user_id, $user_salt, $staff_id, $objStaffDetail)
    {
        $arrTokenHeader = array(
            "alg" => 'HS256', "typ" => "JWT"
        );
        $header = $this->base64url_encode(json_encode($arrTokenHeader));

        if(is_null($objStaffDetail)){
            $DSPNAME = '';
            $WRKDIVNM = '';
            $POSNM = '';
            $LCTID = '';
            $IDCRD = '';
        }else{
            $DSPNAME = $objStaffDetail->DSPNAME;
            $WRKDIVNM = $objStaffDetail->WRKDIVNM;
            $POSNM = $objStaffDetail->POSNM;
            $LCTID =   $objStaffDetail->LCT;
            $IDCRD =   $objStaffDetail->IDCRD;
        }
        $arrTokenPayload = array(
            "app" => $appName, 
            "user_id" => $user_id, 
            "staff" => $staff_id, 
            "staff_name" => $DSPNAME, 
            "staff_div" => $WRKDIVNM,
            "staff_posnm" => $POSNM,
            "staff_lct" => $LCTID,
            "staff_idcrd" => $IDCRD,
            "user_uid" => $user_salt, 
            "remote_ip" => $_SERVER['REMOTE_ADDR'], 
            "exp" => strtotime("+1 hour"),
//            "exp" => strtotime("+30 minutes")
        );
        $payload = $this->base64url_encode(json_encode($arrTokenPayload));

        $data = $header . '.' . $payload;
        $serverSecretKey = $user_salt . 'rjvt_ent@ws01JWT';
        $signature = $this->base64url_encode(hash_hmac("SHA256", $data, $serverSecretKey, true));
        $reToken = $header . '.' . $payload . '.' . $signature;
        return $reToken;
    }

    function genSysToken($header, $payload, $user_salt)
    {
        $data = $header . '.' . $payload;
        $serverSecretKey = $user_salt . 'rjvt_ent@ws01JWT';
        $signature = $this->base64url_encode(hash_hmac("SHA256", $data, $serverSecretKey, true));
        $reToken = $header . '.' . $payload . '.' . $signature;
        return $reToken;
    }

    protected function checkToken()
    {
        $currentToken = $this->getBearerToken();
        if ($currentToken == null) {
            $this->takeError("Not Authorization access.");
        } else {
            $arrToken = explode('.', $currentToken);
            $payload = json_decode(base64_decode($arrToken[1]));
            //$payload->exp
            if (!isset($payload->user_uid)) {
                $this->takeError("Your Token is not up to date.");
            }
            $sysToken = $this->genSysToken($arrToken[0], $arrToken[1], $payload->user_uid);
            $arrSysToken = explode('.', $sysToken);
            //compare signature
            if ($arrSysToken[2] === $arrToken[2]) {
                if (strtotime("now") > $payload->exp) {
                    $this->takeError("Access Token has Expired.");
                } else {
                    $returnArray = array('check_result' => true, 'check_data' => $payload);
                    return (object)$returnArray;
                }
            } else {
                return FALSE;
            }
        }
    }


    // ------------------------------------------------------------------------------------------------------------------------------------------------------

    /**Get Token API_TEST */    
    function getTokenRJFamily()
    {
        /**เริ่มส่งค่าออก */
        $arrTokenHeader = array(
            "alg" => 'HS256', "typ" => "JWT"
        );
        $header = $this->base64url_encode(json_encode($arrTokenHeader));

        $arrTokenPayload = array(
            "app" => 'API_RJFamily', "username" => "RJFamily-API", "expdate" => date("Y-m-d H:i:s", strtotime('+5 minutes'))
        );
        $payload = $this->base64url_encode(json_encode($arrTokenPayload));

        $serverSecretKey = 'rjvt@apiRJFamily@11472';
        $signature = $this->base64url_encode(hash_hmac("SHA256", $header . '.' . $payload, $serverSecretKey, true));
        $reToken = $header . '.' . $payload . '.' . $signature;

        return $reToken;
        //echo 'ทดสอบ';
        /**จบส่งค่าออก */
    }

    function checkJWT()
    {
        /**เริ่ม check ค่าที่รับมา */
        $currentToken = $this->getBearerToken();
        $arrToken = explode('.', $currentToken);
        $arrTokenDecode = array();
        $arrTokenDecode['Header'] = $arrToken[0];
        $arrTokenDecode['Payload'] = $arrToken[1];
        $arrTokenDecode['Signature'] = $arrToken[2];

        $server_sign = $this->generatesignature($arrTokenDecode['Header'], $arrTokenDecode['Payload']);
        /** check ค่า Signature ที่รับมากับค่า Signature ที่ส่งไปให้ */
        if ($arrTokenDecode['Signature'] == $server_sign) {

            $payload = json_decode(base64_decode($arrTokenDecode['Payload']));
            $chk_expdate = $payload->expdate;

            /** เวลาปัจจุบัน */
            $t1 = strtotime(date("Y-m-d H:i:s"));
            /** เวลาที่รับจาก gettoken */
            $t2 = strtotime($chk_expdate);

            /** Check expdate  */
            if ($chk_expdate != '') {/** Check Time ถ้าส่งเวลา (chk_expdate) มา */
                /** Check Time เวลาปัจจุบันต้องน้อยกว่าเวลาที่รับจาก gettoken */
                if ($t1 < $t2) {

                    return true;
                } else {
                    $responseArray = array();
                    $responseArray['result'] = FALSE;
                    $responseArray['jsondata'] = 'Token is expired';

                    echo json_encode($responseArray, JSON_PRETTY_PRINT);
                }
            } else { /** Check Time ถ้าไม่มีส่งเวลา (chk_expdate) */
                return true;
            }
            //echo $currentToken;
        } else {
            return false;
        }

        /**จบ check ค่าที่รับมา */
    }

    function generatesignature($header, $payload)
    {
        $serverSecretKey = 'rjvt@apiRJFamily@11472';
        //echo hash_hmac("SHA256", $header . '.' . $payload, $serverSecretKey, true);

        $signature = $this->base64url_encode(hash_hmac("SHA256", $header . '.' . $payload, $serverSecretKey, true));
        //$reToken = $header . '.' . $payload . '.' . $signature;

        //echo $reToken;
        return $signature;
    }
}
