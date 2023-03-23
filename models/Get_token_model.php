<?php
date_default_timezone_set('Asia/Bangkok');

class Get_token_Model extends Model
{

    function __construct()
    {
        parent::__construct();
    }

/**Get Token gettoken_test */
    function gettoken_RJFamily()
    {
        $objPerson = $this->getTokenRJFamily();
        $responseArray = array();
        $responseArray['result'] = TRUE;
        $responseArray['token'] = $objPerson;

        echo json_encode($responseArray, JSON_PRETTY_PRINT);
        //echo $objPerson;
    }



}
