<?php

class Index_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
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
    private function getBearerToken()
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
    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function index()
    {
        $arrResponse = array();
        $arrResponse['json_result'] = true;

        $currentToken = $this->getBearerToken();
        if (!is_null($currentToken)) {
            $arrToken = explode('.', $currentToken);
            $arrTokenDecode = array();
            $arrTokenDecode['Header'] = json_decode(base64_decode($arrToken[0]));
            $arrTokenDecode['Payload'] = json_decode(base64_decode($arrToken[1]));
            $arrTokenDecode['Signature'] = $arrToken[2];
        }


        $arrTokenHeader = array(
            "alg" => 'HS256', "typ" => "JWT"
        );
        $header = $this->base64url_encode(json_encode($arrTokenHeader));

        $arrTokenPayload = array(
            "app" => '01', "name" => "test user"
        );
        $payload = $this->base64url_encode(json_encode($arrTokenPayload));

        $serverSecretKey = 'rjvt@api@app01';
        $signature = $this->base64url_encode(hash_hmac("SHA256", $header . '.' . $payload, $serverSecretKey, true));
        $reToken = $header . '.' . $payload . '.' . $signature;

        $arrDatabase_Details = array(
            'mysql' => $this->hr_mysql_db ? 'OK' : 'NO',
            'sqlsrv' => $this->mssql_db ? 'OK' : 'NO',
            'Ora_demo' => $this->oracle_db ? 'OK' : 'NO',
            'Ora_pro' => $this->oracle_pro_db ? 'OK' : 'NO'
        );


        $arrResponse['json_data'] = array(
            'header' => 'Resful API',
            'details' => 'Rajavithi Computer Center',
            'SERVER_PORT' => $_SERVER['SERVER_PORT'],
            'Database Stat' => $arrDatabase_Details
            //'token'=>$arrTokenDecode,
            //'return_tokenx' => $reToken
        );
        echo json_encode($arrResponse, JSON_PRETTY_PRINT);
        exit();
    }

    public function test()
    {
        $arrResponse = array();
        $arrResponse['json_result'] = true;
        $arrResponse['json_data'] = array(
            'header' => 'Resful API Test',
            'details' => 'Rajavithi Computer Center_Test'
        );
        echo json_encode($arrResponse, JSON_PRETTY_PRINT);
        exit();
    }
}
