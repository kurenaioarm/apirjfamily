<?php

/**
 * normal
 * * fdfdsfdsfsf
 * ! sdfdsfsdf
 * ?dfsdfs
 * TODO: fdkfkdjsf
 * @param myparam is 
 */
class Doc_Model extends Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function appointment()
  {
    $objCheck = $this->checkToken();
    if ($objCheck->check_result === true) {
      $userdata = $objCheck->check_data;
      //print_r($userdata);
      $staff_id = $userdata->staff;
      $responseArray = array();
      $responseArray['json_result'] = true;
      //cast(pt.dspname AS varchar2(128) ) as ptnm,
      $strSql = "select oapp.hn,
            cast(pt.dspname AS varchar2(128) ) as ptnm,
            oappdate,
            oapptime,
            oapplct,
            oapp.oappst,
            cast(oappst.name AS varchar2(128) ) as statusapp, 
            oapp.dct,
            dct.dspname as dctnm,
            nextlct,
            lct.dspname as lctnm,
            nextdate,
            nexttime,
            TO_CHAR(nextdate,
                    'DD/MM/YYYY',
                    'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS NEXTDATETH,
            TO_CHAR(nexttime, 'fm00G00G00', 'NLS_NUMERIC_CHARACTERS=''.:''') AS NEXTTIMETH,
            note,
            oappno
       from rjvt.oapp@rjvtdb_webout
       left join rjvt.oappst@rjvtdb_webout
         on oapp.oappst = oappst.oappst
       left join rjvt.dct@rjvtdb_webout
         on oapp.dct = dct.dct
        and dct.canceldate is null
       left join rjvt.lct@rjvtdb_webout
         on oapp.nextlct = lct.lct
        and lct.canceldate is null
       left join webout.pt
         on oapp.hn = pt.hn
        and pt.canceldate is null
      where nextdate between to_date(sysdate - 30) and to_date(sysdate + 30)
        and oapp.dct = :staff_id 
        and oapp.oappst in ('1', '2', '3', '4', '9') 
      order by nextdate asc, nexttime asc";
      $objQuery = $this->oracle_db->prepare($strSql);
      //$staff_id = '3039';
      $objQuery->bindParam(":staff_id", $staff_id, PDO::PARAM_STR);
      $objQuery->setFetchMode(PDO::FETCH_OBJ);
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

  public function visit($hn)
  {
    $objCheck = $this->checkToken();
    if ($objCheck->check_result === true) {
      $userdata = $objCheck->check_data;
      //print_r($userdata);
      $hn = filter_var($hn, FILTER_SANITIZE_NUMBER_INT);
      if (is_null($hn) || strlen($hn) != 8) {
        $this->takeError('ไม่มี  HN หรือ รูปแบบ HN ไม่ถูกต้อง');
      }
      $responseArray = array();
      $responseArray['json_result'] = true;
      //cast(pt.dspname AS varchar2(128) ) as ptnm,
      //Query ข้อมูล Visit ก่อนเลือกรายการตรวจ
      $strSql = "SELECT 
                    row_number() OVER (order by vstdate DESC,VSTTIME DESC) as no_ovst,
                    to_date(VSTDATE,'dd/mm/yyyy') as VSTDATE,
                    to_char(VSTDATE,'dd/mm/yyyy') as VSTDATERS,
                    VSTTIME,
                    TO_CHAR(VSTDATE, 'DD/MM/YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS VSTDATE2,
                    TO_CHAR(VSTTIME, 'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') AS VSTTIME2,
                    HN,
                    ovst.CLINICLCT,
                    VN,
                    FN,
                    ovst.DCT,
                    cast(cliniclct.DSPNAME AS varchar2(256) ) as CLINM,
                    cast(dct.DSPNAME AS varchar2(128) ) as DCTNM
                  from rjvt.ovst
                  left join rjvt.cliniclct
                    on ovst.cliniclct = cliniclct.CLINICLCT
                  left join rjvt.dct
                  on ovst.dct = dct.dct
                  where ovst.hn = :pt_hn
                    and ovst.CANCELDATE is null
                  order by vstdate DESC ,VSTTIME DESC";
      $objQuery = $this->oracle_his->prepare($strSql);
      $objQuery->bindParam(":pt_hn", $hn, PDO::PARAM_STR);
      $objQuery->setFetchMode(PDO::FETCH_OBJ);
      $result = $objQuery->execute();
      if ($result) {
        $dataset = $objQuery->fetchAll();
        $responseArray['json_total'] =  count($dataset);
        $responseArray['json_body'] =  $this->getBody();
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

  public function doclist()
  {
    $objCheck = $this->checkToken();
    if ($objCheck->check_result === true) {
      $userdata = $objCheck->check_data;
      $responseArray = array();
      $responseArray['json_result'] = true;
      $strSql = "SELECT dct, 
      prefix, 
      cast(fname AS varchar2(128)) as fname, 
      cast(lname AS varchar2(128)) as lname, 
      prefix || ' ' || cast(fname AS varchar2(128)) || ' ' || cast(lname AS varchar2(128)) as Fullname 
      from rjvt.dct 
      where canceldate is null 
      order by fname, lname";
      $objQuery = $this->oracle_his->prepare($strSql);
      $objQuery->setFetchMode(PDO::FETCH_OBJ);
      $result = $objQuery->execute();
      if ($result) {
        $dataset = $objQuery->fetchAll();
        // while ($row = $objQuery->fetch()) {
        //   $dataset[] = $row;
        // }
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
