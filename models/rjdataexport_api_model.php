<?php

class rjdataexport_api_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function ods_uc_permissions() //หาสิทธิ์ ODS UC ตามวันที่
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];
            $pttype = $_POST['PTTYPE'];
            $hnid = $_POST['HNID'];

            if($pttype == '0'){
                $pttype_data = '';
            }else{
                $pttype_data = 'and INCPRVLG.PTTYPE = '.$_POST['PTTYPE'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "SELECT OVST.HN, OVST.VN, cast(PT.DSPNAME AS varchar2(2000)) as HNNAME, PTNO.CARDNO
                            ,dtoage(pt.brthdate,sysdate,3) as age, MALE.NAME as MALEN, NTNLTY.NAME as NTNLTYN, MRTLST.NAME as MRTLST
                            ,cast(OCCPTN.NAME AS varchar2(2000)) as OCCPTN
                            ,to_char(PT.BRTHDATE,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as BRTHDATE
                            ,to_char(ovst.VSTDATE,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as ddate      
                            ,to_char(ovst.VSTTIME,'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as dtime
                            ,OVST.CLINICLCT , cast(LCT.DSPNAME AS varchar2(2000)) as namegrp  
                            ,OVST.DCT, DCT.DSPNAME as DCTNAME
                            ,CASE WHEN INCPRVLG.PTTYPE IS NULL THEN 101
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN INCPRVLG.PTTYPE END PTTYPE1
                            ,CAST(CASE WHEN INCPRVLG.PTTYPE IS NULL THEN 'ชำระเงินเอง'
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN PTTYPE.NAME END AS VARCHAR2(2000)) PTTYPE_NM
                             ,INCPRVLG.CLAIMLCT, CLAIMLCT.NAME as CLAIMLCTN     
                            
                            from RJVT.OVST
                            left outer join RJVT.LCT
                                 on OVST.CLINICLCT = LCT.LCT
                                 
                            left outer join RJVT.INCPRVLG
                                 ON OVST.HN = INCPRVLG.HN
                                 AND OVST.VSTDATE = INCPRVLG.ISSDATE
                                 AND INCPRVLG.PTTYPEST != 99
                                 AND INCPRVLG.SUBTYPE = 10
                                 
                            LEFT OUTER JOIN PTTYPE
                                 ON INCPRVLG.PTTYPE = PTTYPE.PTTYPE
                                 
                            LEFT OUTER JOIN PT
                                  ON ovst.hn = pt.hn
                                  
                            LEFT OUTER JOIN MALE
                                  ON PT.MALE = MALE.MALE
                            
                            LEFT OUTER JOIN NTNLTY     
                                  ON PT.NTNLTY = NTNLTY.NTNLTY
                                  
                            LEFT OUTER JOIN MRTLST     
                                  ON PT.MRTLST = MRTLST.MRTLST 
                                  
                            LEFT OUTER JOIN OCCPTN     
                                  ON PT.OCCPTN = OCCPTN.OCCPTN 
     
                            LEFT OUTER JOIN PTNO
                                 ON PT.HN = PTNO.HN
                                 and pt.NOTYPE = PTNO.NOTYPE
                                 
                            LEFT OUTER JOIN RJVT.PT
                                 ON OVST.HN = PT.HN
                                 AND PT.CANCELDATE IS NULL
     
                            LEFT OUTER JOIN DCT 
                                 ON OVST.DCT = DCT.DCT
                                 AND DCT.CANCELDATE IS NULL
                                 
                            LEFT OUTER JOIN CLAIMLCT 
                                  ON INCPRVLG.CLAIMLCT = CLAIMLCT.CLAIMLCT 
                                 
                            where OVST.VSTDATE BETWEEN TO_DATE(:SDATE,'dd/mm/yyyy')
                            and TO_DATE(:EDATE,'dd/mm/yyyy')
                            $pttype_data --สิทธิ์
                            and OVST.HN = :HNID
                            and OVST.CLINICLCT in (303011100 ,302030001)
                            and OVST.CANCELDATE is null";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':SDATE' => $sdate,':EDATE' => $edate,':HNID' => $hnid));

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

    public function ods_uc_permissionsV2() //หาสิทธิ์ ODS UC ตามวันที่ V2 เพิ่มรายละเอียด
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];
            $pttype = $_POST['PTTYPE'];

            if($pttype == '0'){
                $pttype_data =  'and INCPRVLG.PTTYPE = 7085';
            }else{
                $pttype_data = 'and INCPRVLG.PTTYPE = '.$_POST['PTTYPE'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "SELECT 
                                incprvlg.pttype
                                ,incprvlg.hn
                                ,incprvlg.an
                                ,incprvlg.prvno
                                ,pttype.name as pname
                                ,pttype.drgcode
                                ,pttype.progexport
                                ,to_char(incprvlg.issdate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as incdate  
                                ,ipt.rgtdate as rgtdate
                                ,ipt.rgttime as rgttime
                                ,to_char(incprvlg.expdate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as maxdate  
                                ,ipt.dchdate as idchdate
                                ,ipt.dchtime as idchtime
                                ,ipt.dchconfdate
                                ,ipt.dchconftime
                                ,dct.dspname
                                ,pt.dspname as dspnamept
                                ,ipt.prediag
                                ,ward.dspname
                                ,ipt.room
                                ,ipt.bedno
                                ,incprvlg.crdtown
                                ,nvl(incprvlg.claimlct,-99) as claimlct
                                ,incprvlg.pttypest
                                ,prsnrlt.name
                                ,crdtorg.name
                                ,claimlct.name
                                ,ipt.homemeddate
                                ,ipt.homemedtime
                                ,pttype.pttypegrp
                                , 0 chk
                                ,arpt.arno
                                ,arpt.invno
                                ,arpt.arstf
                                ,arpt.trnstf
                                 ,to_char(arpt.trndate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as trndate  
                                ,arpt.trntime
                                ,arpt.invno
                                ,arpt.chkdate
                                ,1 as ptday,incprvlg.isstime as  inctime  
                                ,incprvlg.expdate
                                ,incprvlg.exptime
                                ,incprvlg.issdate
                                ,incprvlg.isstime
                                ,incprvlg.claimlct
                                ,arpt.hc
                                ,incprvlg.approvstf
                                ,staff.dspname             
                                ,arpt.trnstf
                                ,GET_OPD_INCPT(130, incprvlg.hn, case when arpt.old_prvno is null then incprvlg.prvno else arpt.old_prvno end, 1) as s_incpt
                                ,GET_OPD_ARPTINC(130, arpt.arno, 1) as s_arpt
                                ,(select nvl2(min(an),'มีการ Admid ( ' || min(an) || ' )','') from ipt   where ipt.rgtdate = incprvlg.issdate  and ipt.hn = incprvlg.hn and ipt.canceldate is null) as annn
                                ,arinv.invdate
                                ,invstf.dspname as invstf
                                ,arpt.arnotopup
                                ,arpt.old_prvno
                                ,arpt.old_pttype
                                    FROM incprvlg   
                                        left outer join prsnrlt on(incprvlg.prsnrlt = prsnrlt.prsnrlt)
                                        left outer join crdtorg on(incprvlg.crdtorg = crdtorg.crdtorg)
                                        left outer join claimlct on(incprvlg.claimlct = claimlct.claimlct)
                                       
                                        left outer join ipt on(incprvlg.issdate = ipt.rgtdate and incprvlg.hn = ipt.hn) and ipt.canceldate is null
                                        left outer join ward on(ipt.ward = ward.ward )
                                        left outer join dct on(ipt.dct = dct.dct )
                                        left outer join pttype on(incprvlg.pttype = pttype.pttype )
                                      --  left outer join arpt  on arpt.hn = incprvlg.hn  and ((arpt.prvno = incprvlg.prvno and arpt.pttype = incprvlg.pttype and arpt.old_prvno is null) or (arpt.old_prvno = incprvlg.prvno and arpt.old_pttype = incprvlg.pttype)) and arpt.canceldate is null
                                  left outer join arpt on arpt.hn = incprvlg.hn and arpt.prvno = incprvlg.prvno and arpt.pttype = incprvlg.pttype and arpt.canceldate is null
                                  left outer join staff on arpt.trnstf = staff.staff
                                left outer join arinv on arinv.invno = arpt.invno
                                left outer join staff invstf on arinv.invstf = invstf.staff
                                ,pt
                                WHERE incprvlg.canceldate is null
                                and incprvlg.hn = pt.hn
                                and GET_OPD_INCPT(130, incprvlg.hn, case when arpt.old_prvno is null then incprvlg.prvno else arpt.old_prvno end, 1) <> 0
                                --and GET_OPD_INCPT(130, incprvlg.hn, incprvlg.prvno, 1) <>0
                                --and (pttype.PROGEXPORT in ('paper') or pttype.PROGEXPORT is null)
                                --and ( (pttype.pttypegrp in('30','40','46','70','25','65','61','50') and incprvlg.an is null) OR (pttype.pttypegrp = 20))
                                  $pttype_data --สิทธิ์
                                --and arpt.hn = 63002871
                                and arpt.vstdate BETWEEN TO_DATE(:SDATE,'dd/mm/yyyy')
                                and TO_DATE(:EDATE,'dd/mm/yyyy')
                                ORDER BY incprvlg.issdate";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
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


    public function check_ods_uc90() //เทียบ HN จาก API ods_uc_permissions กับ RJDATAMAX ของน้องนัทว่ามีตรงกันไหม
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $hndate = $_POST['HNDATE'];
            $vndate = $_POST['VNDATE'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, VN from Webintra.res_ods 
                            where HN = :HNDATE
                            and VN = :VNDATE";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HNDATE' => $hndate,':VNDATE' => $vndate));

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

    public function check_service_charge() //รายการค่าบริการทางการแพทย์
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $hndate = $_POST['HNDATE'];
            $vndate = $_POST['VNDATE'];
            $cliniclct = $_POST['CLINICLCT'];
            $pttype = $_POST['PTTYPE'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ovst.hn 
                                ,ovst.vstdate 
                                ,ovst.cliniclct 
                                ,incprvlg.pttype
                                ,cast(pttype.name AS varchar2(2000)) as name
                                ,pttype.pttypegrp
                                 ,GET_OPD_INCPTGRP(130, 10, incprvlg.hn, incprvlg.prvno, 1) as grp10
                                        ,GET_OPD_INCPTGRP(130, 10, incprvlg.hn, incprvlg.prvno, 2) as grp11
                                        ,GET_OPD_INCPTGRP(130, 20, incprvlg.hn, incprvlg.prvno, 1) as grp20
                                        ,GET_OPD_INCPTGRP(130, 20, incprvlg.hn, incprvlg.prvno, 2) as grp21
                                        ,GET_OPD_INCPTGRP(130, 30, incprvlg.hn, incprvlg.prvno, 1) as grp30
                                        ,GET_OPD_INCPTGRP(130, 30, incprvlg.hn, incprvlg.prvno, 2) as grp31
                                        ,GET_OPD_INCPTGRP(130, 40, incprvlg.hn, incprvlg.prvno, 1) as grp40
                                        ,GET_OPD_INCPTGRP(130, 40, incprvlg.hn, incprvlg.prvno, 2) as grp41
                                        ,GET_OPD_INCPTGRP(130, 50, incprvlg.hn, incprvlg.prvno, 1) as grp50
                                        ,GET_OPD_INCPTGRP(130, 50, incprvlg.hn, incprvlg.prvno, 2) as grp51
                                        ,GET_OPD_INCPTGRP(130, 60, incprvlg.hn, incprvlg.prvno, 1) as grp60
                                        ,GET_OPD_INCPTGRP(130, 60, incprvlg.hn, incprvlg.prvno, 2) as grp61
                                        ,GET_OPD_INCPTGRPCHEMO(130, 70, incprvlg.hn, incprvlg.prvno, 1) as grp70
                                        ,GET_OPD_INCPTGRPCHEMO(130, 70, incprvlg.hn, incprvlg.prvno, 2) as grp71
                                        ,GET_OPD_INCPTGRP(130, 80, incprvlg.hn, incprvlg.prvno, 1) as grp80
                                        ,GET_OPD_INCPTGRP(130, 80, incprvlg.hn, incprvlg.prvno, 2) as grp81
                                        ,GET_OPD_INCPTGRP_DRUG(130, 90, incprvlg.hn, incprvlg.prvno, 1) as grp90
                                        ,GET_OPD_INCPTGRP_DRUG(130, 90, incprvlg.hn, incprvlg.prvno, 2) as grp91
                                        ,GET_OPD_INCPTGRP(130, 100, incprvlg.hn, incprvlg.prvno, 1) as grp100
                                        ,GET_OPD_INCPTGRP(130, 100, incprvlg.hn, incprvlg.prvno, 2) as grp101
                                        ,GET_OPD_INCPTGRP(130, 110, incprvlg.hn, incprvlg.prvno, 1) as grp110
                                        ,GET_OPD_INCPTGRP(130, 110, incprvlg.hn, incprvlg.prvno, 2) as grp111
                                
                                from rjvt.ovst
                                left outer join rjvt.incprvlg
                                     ON OVST.HN = INCPRVLG.HN
                                     AND OVST.VSTDATE = INCPRVLG.ISSDATE
                                left outer join pttype
                                     on incprvlg.pttype = pttype.pttype
                                
                                where ovst.hn = :HNDATE
                                and ovst.vstdate = to_date(:VNDATE,'ddmmyyyy')
                                and ovst.cliniclct = :CLINICLCT
                                and incprvlg.pttype = :PTTYPE
                                and ovst.canceldate is null
                                and incprvlg.canceldate is null";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HNDATE' => $hndate,':VNDATE' => $vndate,':CLINICLCT' => $cliniclct,':PTTYPE' => $pttype));

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

    public function check_pttype_name() //สิทธิ์
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $check1 = $_POST['CHECK1'];
            $check2 = $_POST['CHECK2'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "SELECT CASE WHEN pttype .PTTYPE IS NULL THEN 101
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN INCPRVLG.PTTYPE END PTTYPE1
                            ,CAST(CASE WHEN INCPRVLG.PTTYPE IS NULL THEN 'ชำระเงินเอง'
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN PTTYPE.NAME END AS VARCHAR2(2000)) PTTYPE_NM
                            
                            from RJVT.OVST
                            left outer join RJVT.LCT
                                 on OVST.CLINICLCT = LCT.LCT
                            
                            left outer join RJVT.INCPRVLG
                                 ON OVST.HN = INCPRVLG.HN
                                 AND OVST.VSTDATE = INCPRVLG.ISSDATE
                                 AND INCPRVLG.PTTYPEST != 99
                                 AND INCPRVLG.SUBTYPE = 10
                                 
                            LEFT OUTER JOIN PTTYPE
                                 ON INCPRVLG.PTTYPE = PTTYPE.PTTYPE
                                     
                            where OVST.CLINICLCT in (:CHECK1 ,:CHECK2)
                            and OVST.CANCELDATE is null
                            group by CASE WHEN pttype .PTTYPE IS NULL THEN 101
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN INCPRVLG.PTTYPE END 
                            ,CAST(CASE WHEN INCPRVLG.PTTYPE IS NULL THEN 'ชำระเงินเอง'
                                  WHEN INCPRVLG.PTTYPE IS NOT NULL THEN PTTYPE.NAME END AS VARCHAR2(2000))";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':CHECK1' => $check1,':CHECK2' => $check2));

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

    public function icd10icd9_one() //ICD10 (แพทย์ผู้สรุปผล)
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $hnid = $_POST['HNID'];
            $vndate = $_POST['VNDATE'];

            if($_POST['CLINICLCTID'] == ""){
                $cliniclctid  = " ";
            }else {
                $cliniclctid  = "and ovst.cliniclct =".$_POST['CLINICLCTID'];
            }


            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "SELECT pt.hn as inthn
                             ,to_char(incprvlg.issdate,'dd/mm/yyyy') as issdate  
                             ,(substr(pt.hn, 3, 6) || '-' || substr(pt.hn, 0, 2)) AS HN
                              ,cast(pt.dspname AS varchar2(2000)) as ptdspname
                             ,dct.dct
                             ,dct.lcno
                             ,cast(dct.dspname AS varchar2(2000)) as dctdspname
                             ,ARICD10ICD9.flag
                             ,to_char(ARICD10ICD9.vstdate,'dd/mm/yyyy') as vstdate  
                             ,to_char(ARICD10ICD9.vsttime,'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as dtime
                             ,ARICD10ICD9.DIAG
                            FROM pt, incprvlg
                              left outer join ARICD10ICD9 on  incprvlg.hn = ARICD10ICD9.hn and incprvlg.issdate = ARICD10ICD9.vstdate
                              left outer join ovst on ovst.hn = ARICD10ICD9.hn and ovst.vstdate = ARICD10ICD9.vstdate and ovst.vsttime = ARICD10ICD9.vsttime
                              left outer join dct on ARICD10ICD9.dct = dct.dct
                            WHERE incprvlg.hn = pt.hn 
                            and incprvlg.hn = :HNID
                            and ARICD10ICD9.vstdate = to_date(:VNDATE,'ddmmyyyy')
                            and ARICD10ICD9.dct != '-99'
                           $cliniclctid
                            
                            ORDER BY ARICD10ICD9.vsttime ,dct.dspname";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':HNID' => $hnid,':VNDATE' => $vndate));

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


    public function icd10icd9_all() //ICD10 (แพทย์ผู้สรุปผล)
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $hnid = $_POST['HNID'];
            $vndate = $_POST['VNDATE'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "SELECT pt.hn as inthn
                               ,to_char(incprvlg.issdate,'dd/mm/yyyy') as issdate  
                             ,(substr(pt.hn, 3, 6) || '-' || substr(pt.hn, 0, 2)) AS HN
                              ,cast(pt.dspname AS varchar2(2000)) as ptdspname
                             ,dct.dct
                             ,dct.lcno
                             ,cast(dct.dspname AS varchar2(2000)) as dctdspname
                             ,ARICD10ICD9.flag
                             ,to_char(ARICD10ICD9.vstdate,'dd/mm/yyyy') as vstdate  
                             ,to_char(ARICD10ICD9.vsttime,'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as dtime
                             ,ARICD10ICD9.DIAG
                            FROM pt, incprvlg
                              left outer join ARICD10ICD9 on  incprvlg.hn = ARICD10ICD9.hn and incprvlg.issdate = ARICD10ICD9.vstdate
                              left outer join dct on ARICD10ICD9.dct = dct.dct
                            WHERE incprvlg.hn = pt.hn 
                            and incprvlg.subtype = 10
                            and incprvlg.pttypest != 99
                            
                            and incprvlg.hn = :HNID
                            and ARICD10ICD9.vstdate = to_date(:VNDATE,'ddmmyyyy')
                            ORDER BY dct.dspname";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':HNID' => $hnid,':VNDATE' => $vndate));

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

    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function data_ins()//1 มาตรฐานแฟ้มข้อมูลผู้มีสิทธิการรักษาพยาบาล***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, INSCL, SUBTYPE, CID, HCODE, DATEIN, DATEEXP, HOSPMAIN, HOSPSUB, GOVCODE, GOVNAME, PERMITNO, 
                            DOCNO, OWNRIGHTID, OWNRPID, OWNNAME, AN, SEQ, SUBINSCL, RELINSCL, HTYPE, 
                            to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP, 
                            to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO 
                            from rjvt.eclaim_opbkk01_ins where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT TO_CHAR (arpt.hn) AS HN,pttype.inscl AS INSCL,'00' AS SUBTYPE,LPAD (ptno.cardno, 13, 0) AS CID,
//                              (select val from phisenv where section = 'HPTENV' and varcode like 'HPTCODE') as HCODE ,
//                                TO_CHAR (incprvlg.refissdate, 'yyyymmdd') AS DATEIN,
//                                TO_CHAR (incprvlg.refexpdate, 'yyyymmdd') AS DATEEXP,
//                                LPAD (TO_CHAR (NVL (CASE WHEN LENGTH (incprvlg.mainhpt) = 4 THEN '0'ELSE ''END || incprvlg.mainhpt,11472)),5,0)AS HOSPMAIN,
//                                LPAD (TO_CHAR (NVL (CASE WHEN LENGTH (incprvlg.referhpt) = 4 THEN '0'ELSE ''END || incprvlg.referhpt,11472)),5,0)AS HOSPSUB,
//                                case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then to_char(nvl(incprvlg.crdtorg,incprvlg.claimlct)) else '' end) end AS GOVCODE,
//                                case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then nvl(crdtorg.name,claimlct.name) else '' end) end  AS GOVNAME,
//                                case when  arpt.an is null then
//                                case when pttype.pttypegrp = 40 then (case when incprvlg.claimcodebkk is not null then incprvlg.claimcodebkk else incprvlg.claimno end)
//                                else (case when nvl(incprvlg.claimno,incprvlg.refno) is not null then '' else incprvlg.sheetno end) end
//                                else (case when incprvlg.claimno is not null then incprvlg.claimno else (case when pttype.pttypegrp = 40 then '' else incprvlg.sheetno end) end)end AS PERMITNO,
//                                '' AS DOCNO,incprvlg.crdtownid AS OWNRIGHTID,
//                                case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then incprvlg.crdtownid else '' end) end AS OWNRPID,
//                                case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then incprvlg.crdtown else '' end) end AS OWNNAME,
//                                CASE
//                                       WHEN arpt.an > 9000000
//                                       THEN
//                                          'N' || TO_CHAR (arpt.an)
//                                       WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                       THEN
//                                          '0' || TO_CHAR (arpt.an)
//                                       WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                       THEN
//                                          '00' || TO_CHAR (arpt.an)
//                                       ELSE
//                                          TO_CHAR (arpt.an)
//                                END
//                                       AS AN,
//                                TO_CHAR (arpt.arno) AS SEQ ,
//                                TO_CHAR (case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then '' else '' end) end) as SUBINSCL ,
//                                TO_CHAR (case when pttype.pttypegrp = 40 then '' else (case when incprvlg.sheetno is not null then to_char(incprvlg.benfrel) else '' end) end) as RELINSCL ,
//                                TO_CHAR (case when pttype.pttypegrp = 30 then '1' else '' end) as HTYPE ,
//                                to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,arpt.argrp AS ARGRP,
//                                to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,arpt.arno AS ARNO
//                                FROM incprvlg
//                                LEFT OUTER JOIN crdtorg ON crdtorg.crdtorg = incprvlg.crdtorg
//                                LEFT OUTER JOIN claimlct ON claimlct.claimlct = incprvlg.claimlct,arpt
//                                LEFT OUTER JOIN ptno ON (arpt.hn = ptno.hn AND ptno.notype = 10),pttype
//                                WHERE incprvlg.hn = arpt.hn
//                                AND incprvlg.prvno = arpt.prvno
//                                AND arpt.pttype = pttype.pttype
//                                --AND arpt.an IS NULL
//                                AND arpt.canceldate IS NULL
//                                AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                                AND ARNO = :ARNO";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_pat()//2 มาตรฐานแฟ้มข้อมูลผู้ป่วยกลาง***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HCODE, HN, CHANGWAT, AMPHUR, DOB, SEX, MARRIAGE, OCCUPA, NATION, PERSON_ID, 
                            cast(NAMEPAT AS varchar2(2000)) AS NAMEPAT, 
                            cast(TITLE AS varchar2(2000)) AS TITLE, 
                            cast(FNAME AS varchar2(2000)) AS FNAME, 
                            cast(LNAME AS varchar2(2000)) AS LNAME, IDTYPE,
                            to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                            to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO
                            from rjvt.eclaim_opbkk02_pat
                            where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                              '11472' AS HCODE,
//                              TO_CHAR (arpt.hn) AS HN,
//                              TO_CHAR (ptaddr.changwat) AS CHANGWAT,
//                              SUBSTR (TO_CHAR (ptaddr.ampur), 3, 2) AS AMPHUR,
//                              TO_CHAR (pt.brthdate, 'yyyymmdd') AS DOB,
//                              CASE WHEN pt.male = 1 THEN 1 WHEN pt.male = 2 THEN 2 ELSE 0 END
//                                 AS SEX,
//                              CASE
//                                 WHEN pt.mrtlst = 1 THEN 1
//                                 WHEN pt.mrtlst = 2 THEN 2
//                                 WHEN pt.mrtlst = 3 THEN 3
//                                 WHEN pt.mrtlst = 4 THEN 4
//                                 WHEN pt.mrtlst = 5 THEN 5
//                                 WHEN pt.mrtlst = 6 THEN 6
//                                 ELSE 9
//                              END
//                                 MARRIAGE,
//                              nvl(occptn.drgcode,'000') AS OCCUPA,
//                              ntnlty.eclaim AS NATION,
//                              LPAD (ptno.cardno, 13, 0) AS PERSON_ID,
//                              cast((pt.fname || ' ' || pt.lname || ',' || pname.name) AS varchar2(2000)) AS NAMEPAT,
//                              cast(pname.eclaim AS varchar2(2000)) AS TITLE,
//                              cast(pt.fname AS varchar2(2000)) AS FNAME,
//                              cast(pt.lname AS varchar2(2000)) AS LNAME,
//                              1 AS IDTYPE,
//                              to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                              arpt.argrp AS ARGRP,
//                              to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                              arpt.arno AS ARNO
//                         FROM arpt,
//                              pt
//                              LEFT OUTER JOIN pname ON (pname.pname = pt.pname)
//                              LEFT OUTER JOIN ptno ON (pt.hn = ptno.hn AND ptno.notype = 10)
//                              LEFT OUTER JOIN ptaddr
//                                 ON (    ptaddr.hn = pt.hn
//                                     AND ptaddr.addrtype = 20
//                                     AND ptaddr.addrflag = 1)
//                              LEFT OUTER JOIN occptn ON (occptn.occptn = pt.occptn)
//                              LEFT OUTER JOIN ntnlty ON (ntnlty.ntnlty = pt.ntnlty)
//                        WHERE     arpt.hn = pt.hn
//                              --AND arpt.an IS NULL
//                              AND arpt.canceldate IS NULL
//                              AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                              AND ARNO = :ARNO";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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

    public function data_opd()//3 มาตรฐานแฟ้มข้อมูลการมารับบริการผู้ป่วยนอก
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, CLINIC, DATEOPD, TIMEOPD, SEQ, UUC, REFER_NO, DETAIL,
                             to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                             to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO,
                             BTEMP, SBP, DBP, PR, RR, OPTYPE, TYPEIN, TYPEOUT
                             from rjvt.eclaim_opbkk03_opd
                             where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                                TO_CHAR (arpt.hn) AS HN,
//                                MAX (NVL (lct.nhso, '0120')) AS CLINIC,
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEOPD,
//                                SUBSTR (LPAD (arpt.vsttime, 6, '0'), 1, 4) AS TIMEOPD,
//                                TO_CHAR (arpt.arno) AS SEQ,
//                                CASE
//                                   WHEN pttype.pttypegrp IN (30,
//                                                             40,
//                                                             50,
//                                                             55,
//                                                             60,61)
//                                   THEN
//                                      1
//                                   ELSE
//                                      1
//                                END
//                                   AS UUC,
//                                incprvlg.sheetno AS REFER_NO,
//                                CASE
//                                   WHEN pttype.inscl = 'UCS'
//                                   THEN
//                                      TRIM (
//                                         REPLACE (REPLACE (ovst.ccp, CHR (10), ' '), CHR (13), ' '))
//                                   ELSE
//                                      ''
//                                END
//                                   AS DETAIL,
//                                to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                                arpt.argrp AS ARGRP,
//                                to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                                arpt.arno AS ARNO
//                             ,case when pttype.inscl = 'UCS' then get_opd_opbkk(arpt.hn,arpt.vstdate,1) end as BTEMP
//                             ,case when pttype.inscl = 'UCS' then get_opd_opbkk_bp(arpt.hn,arpt.vstdate,1) end as SBP
//                             ,case when pttype.inscl = 'UCS' then get_opd_opbkk_bp(arpt.hn,arpt.vstdate,2) end as DBP
//                             ,case when pttype.inscl = 'UCS' then get_opd_opbkk(arpt.hn,arpt.vstdate,2) end as PR
//                             ,case when pttype.inscl = 'UCS' then get_opd_opbkk(arpt.hn,arpt.vstdate,3) end as RR
//                    ,case when pttype.inscl = 'UCS' and incprvlg.pttype <> 7095 then
//                           case when incprvlg.pttype in (4338,4341,4344,4347) and incprvlg.mainhpt = 11472 and incprvlg.acer in (1,2,3) then 2                                         -- create 23/03/64
//                                  when incprvlg.pttype in (4336,4339,4342,4345,4082) and incprvlg.mainhpt <> 11472 and incprvlg.changwat = 10 and incprvlg.acer in (1,2,3) then 3        -- create 23/03/64
//                                  when incprvlg.pttype in (4281,4291) and incprvlg.mainhpt = 11472 and incprvlg.acer = 4 then 0                                                          -- create 23/03/64
//                                  when incprvlg.pttype in (4041,4121) and incprvlg.mainhpt <> 11472 and incprvlg.acer = 4 then 1                                                         -- create 23/03/64
//                                  when incprvlg.acer = 4 and incprvlg.regularhpt in (24968,24929,24930,24931,24840,24841,24902,21716,24969,24970,22094,23851,23557,24555,24551,24552,24554,23884,13670,21748) then 0
//                                  when incprvlg.acer = 4 and incprvlg.regularhpt not in (24968,24929,24930,24931,24840,24841,24902,21716,24969,24970,22094,23851,23557,24555,24551,24552,24554,23884) then 1
//                                  when incprvlg.acer in (1,2) and incprvlg.regularhpt in (24968,24929,24930,24931,24840,24841,24902,21716,24969,24970,22094,23851,23557,24555,24551,24552,24554,23884) then 2
//                                  when incprvlg.acer in (1,2) and incprvlg.regularhpt not in (24968,24929,24930,24931,24840,24841,24902,21716,24969,24970,22094,23851,23557,24555,24551,24552,24554,23884) then 3
//                                  when incprvlg.pttype in (4071,4072,4074,4084) then 4
//                                  when incprvlg.acer = 3 then 5 else 7 end
//                    end as OPTYPE
//                            ,case when pttype.inscl = 'UCS' then 1 end as TYPEIN
//                            ,case when pttype.inscl = 'UCS' then 1 end as TYPEOUT
//                           FROM arpt
//                                LEFT OUTER JOIN incprvlg
//                                   ON (incprvlg.hn = arpt.hn AND incprvlg.prvno = arpt.prvno)
//                                LEFT OUTER JOIN pttype ON (arpt.pttype = pttype.pttype),
//                                ovst
//                                LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct)
//                          WHERE     arpt.hn = ovst.hn
//                                AND arpt.vstdate = ovst.vstdate
//                                AND ovst.canceldate IS NULL
//                                AND arpt.an IS NULL
//                                AND arpt.canceldate IS NULL
//                                AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                                AND arpt.arno = :ARNO
//                    --and arpt.arno = 6500667772
//
//                       GROUP BY arpt.hn,
//                                arpt.vstdate,
//                                SUBSTR (LPAD (arpt.vsttime, 6, '0'), 1, 4),
//                                TO_CHAR (arpt.arno),
//                                CASE WHEN pttype.pttypegrp = 40 THEN 1 ELSE 2 END,
//                                incprvlg.sheetno,
//                                CASE
//                                   WHEN pttype.inscl = 'UCS'
//                                   THEN
//                                      TRIM (
//                                         REPLACE (REPLACE (ovst.ccp, CHR (10), ' '),
//                                                  CHR (13),
//                                                  ' '))
//                                   ELSE
//                                      ''
//                                END,
//                                arpt.argrp,
//                                arpt.trndate,
//                                arpt.arno,
//                                pttype.pttypegrp,
//                                incprvlg.acer ,
//                                incprvlg.regularhpt ,
//                                incprvlg.pttype ,
//                                incprvlg.changwat ,
//                                incprvlg.mainhpt ,
//                                pttype.inscl";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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

    public function data_orf()//4 มาตรฐานแฟ้มข้อมูลผู้ป่วยนอกที่ต้องส่งต่อ
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, DATEOPD, CLINIC, REFER, REFERTYPE, SEQ, REFERDATE, 
                             to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                             to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO
                             from rjvt.eclaim_opbkk04_orf
                             where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                              TO_CHAR (arpt.hn) AS HN,
//                              TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEOPD,
//                              NVL (lct.nhso, '0120') AS CLINIC --,LPAD(to_char(NVL( incprvlg.claimlct , 11472)),5,0) as REFER
//                                                              ,
//                              LPAD (TO_CHAR (NVL (incprvlg.referhpt, '')), 5, 0) AS REFER --,'1' as REFERTYPE
//                                                                                         ,
//                              CASE WHEN pttype.pttypegrp IN (50, 55, 60) THEN '' ELSE '1' END
//                                 AS REFERTYPE,
//                              TO_CHAR (arpt.arno) AS SEQ,
//                              TO_CHAR (arpt.vstdate, 'yyyymmdd') AS REFERDATE,
//                              arpt.vstdate AS VSTDATE,
//                              arpt.argrp AS ARGRP,
//                              arpt.trndate AS TRNDATE,
//                              arpt.arno AS ARNO
//                         FROM arpt,
//                              incpt
//                              LEFT OUTER JOIN ovst
//                                 ON (    ovst.hn = incpt.hn
//                                     AND incpt.vn = ovst.vn
//                                     AND incpt.fn = ovst.fn)
//                              LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct)
//                              LEFT OUTER JOIN pttype ON (incpt.pttype = pttype.pttype),
//                              incprvlg
//                        WHERE     arpt.arno = incpt.arno
//                              --AND arpt.an IS NULL
//                              AND incprvlg.hn = incpt.hn
//                              AND incprvlg.prvno = incpt.prvno
//                              AND incprvlg.pttype = incpt.pttype
//                              AND incpt.itemno > 0
//                              AND incpt.incamt <> 0
//                              AND arpt.canceldate IS NULL
//                              AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                              AND incprvlg.pttype <> 7095
//                              AND arpt.arno = :ARNO
//
//                    --and arpt.arno = 6500667772
//                       UNION
//                       SELECT DISTINCT TO_CHAR (incpt.hn) AS HN,
//                                       TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEOPD,
//                                       NVL (lct.nhso, '0120') AS CLINIC,
//                                       LPAD (TO_CHAR (ovst.rfrolct), 5, 0) AS REFER,
//                                       '2' AS REFERTYPE,
//                                       TO_CHAR (arpt.arno) AS SEQ,
//                                       TO_CHAR (arpt.vstdate, 'yyyymmdd') AS REFERDATE,
//                                       arpt.vstdate AS VSTDATE,
//                                       arpt.argrp AS ARGRP,
//                                       arpt.trndate AS TRNDATE,
//                                       arpt.arno AS ARNO
//                         FROM arpt,
//                              incpt
//                              LEFT OUTER JOIN incprvlg
//                                 ON (    incprvlg.hn = incpt.hn
//                                     AND incprvlg.prvno = incpt.prvno
//                                     AND incprvlg.pttype = incpt.pttype)
//                              LEFT OUTER JOIN pttype ON (incpt.pttype = pttype.pttype),
//                              ovst
//                              LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct)
//                        WHERE     arpt.arno = incpt.arno
//                              AND arpt.an IS NULL
//                              AND ovst.hn = incpt.hn
//                              AND incpt.vn = ovst.vn
//                              AND incpt.fn = ovst.fn
//                              AND ovst.ovstost = 40
//                              AND incpt.itemno > 0
//                              AND incpt.incamt <> 0
//                              AND arpt.canceldate IS NULL
//                              AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                    and incprvlg.pttype <> 7095";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_odx()//5 มาตรฐานแฟ้มข้อมูลวินิจฉัยโรคผู้ป่วยนอก
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, DATEDX, CLINIC, DIAG, DXTYPE, DRDX, PERSON_ID, SEQ,
                             to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                             to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO
                             from rjvt.eclaim_opbkk05_odx
                             where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                                TO_CHAR (arpt.hn) AS HN,
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEDX,
//                                MAX (NVL (lct.nhso, '0120')) AS CLINIC,
//                                NVL ( (icd10.icd10who), ptdiag.icd10) AS DIAG,
//                                ptdiag.diagtype AS DXTYPE,
//                                NVL2 (
//                                   ptdiag.icd10,
//                                   REPLACE (
//                                      REPLACE (
//                                         (CASE
//                                             WHEN dct.lcno IS NULL THEN '999999'
//                                             ELSE dct.lcno
//                                          END),
//                                         '?.',
//                                         ''),
//                                      '? ',
//                                      ''),
//                                   '')
//                                   AS DRDX,
//                                LPAD (ptno.cardno, 13, 0) AS PERSON_ID,
//                                TO_CHAR (arpt.arno) AS SEQ,
//                                to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                                arpt.argrp AS ARGRP,
//                                to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                                arpt.arno AS ARNO
//                           FROM arpt
//                                LEFT OUTER JOIN ptno ON (arpt.hn = ptno.hn AND ptno.notype = 10)
//                                LEFT OUTER JOIN ovst
//                                   ON (ovst.hn = arpt.hn AND arpt.vstdate = ovst.vstdate)
//                                LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct),
//                                ptdiag
//                                LEFT OUTER JOIN dct ON (dct.dct = ptdiag.dct),
//                                icd10
//                          WHERE     arpt.hn = ptdiag.hn
//                                AND arpt.vstdate = ptdiag.vstdate
//                                AND ptdiag.an IS NULL
//                                AND arpt.an IS NULL
//                                AND arpt.canceldate IS NULL
//                                AND ptdiag.icd10 = icd10.icd10
//                                AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                                AND arpt.arno = :ARNO
//                                AND ptdiag.vsttime =
//                                       (SELECT MIN (vsttime)
//                                          FROM ptdiag A
//                                         WHERE A.hn = ptdiag.hn AND A.vstdate = ptdiag.vstdate)
//                       GROUP BY TO_CHAR (arpt.hn),
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd'),
//                                NVL ( (icd10.icd10who), ptdiag.icd10),
//                                ptdiag.diagtype,
//                                NVL2 (
//                                   ptdiag.icd10,
//                                   REPLACE (
//                                      REPLACE (
//                                         (CASE
//                                             WHEN dct.lcno IS NULL THEN '999999'
//                                             ELSE dct.lcno
//                                          END),
//                                         '?.',
//                                         ''),
//                                      '? ',
//                                      ''),
//                                   ''),
//                                LPAD (ptno.cardno, 13, 0),
//                                TO_CHAR (arpt.arno),
//                                arpt.vstdate,
//                                arpt.argrp,
//                                arpt.trndate,
//                                arpt.arno";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_oop()//6 มาตรฐานแฟ้มข้อมูลหัตถการผู้ป่วยนอก
    {

    }


    public function data_ipd()//7 มาตรฐานแฟ้มข้อมูลผู้ป่วยใน***
    {

    }


    public function data_irf()//8 มาตรฐานแฟ้มข้อมูลผู้ป่วยในที่ต้องส่งต่อ
    {

    }


    public function data_idx()//9 มาตรฐานแฟ้มข้อมูลวินิจฉัยโรคผู้ป่วยใน***
    {

    }

    public function data_iop()//10 มาตรฐานแฟ้มข้อมูลหัตถการผู้ป่วยใน***
    {

    }


    public function data_cht()//11 มาตรฐานแฟ้มข้อมูลการเงิน (แบบสรุป)***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, AN, DATE_, TOTAL, PAID, PTTYPE, PERSON_ID, SEQ, 
                              to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP, 
                              to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO, 
                              OPD_MEMO, INVOICE_NO, INVOICE_LT 
                              from rjvt.eclaim_opbkk11_cht 
                              where ARNO = :ARNO";

//            $strSql = "SELECT TO_CHAR (arpt.hn) AS HN,
//                             CASE
//                                WHEN arpt.an > 9000000
//                                THEN
//                                   'N' || TO_CHAR (arpt.an)
//                                WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                THEN
//                                   '0' || TO_CHAR (arpt.an)
//                                WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                THEN
//                                   '00' || TO_CHAR (arpt.an)
//                                ELSE
//                                   TO_CHAR (arpt.an)
//                             END
//                                AS AN,
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATE_,
//                                SUM (arptinc.incamt) AS TOTAL,
//                                0 AS PAID,
//                                --'UC' as PTTYPE
//                                CASE
//                                   WHEN pttype.pttypegrp = 40 THEN 'UC'
//                                   ELSE TO_CHAR (pttypegrp)
//                                END
//                                   AS PTTYPE,
//                                LPAD (ptno.cardno, 13, 0) AS PERSON_ID,
//                                TO_CHAR (arpt.arno) AS SEQ,
//                                to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                                arpt.argrp AS ARGRP,
//                                to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                                arpt.arno AS ARNO ,
//                                '' as OPD_MEMO ,
//                                arpt.arno as INVOICE_NO ,
//                                '' as INVOICE_LT
//                          FROM arpt
//                                LEFT OUTER JOIN ptno ON arpt.hn = ptno.hn AND ptno.notype = 10,
//                                arptinc,
//                                pttype
//                          WHERE                                                  --arpt.an IS NULL
//                               arpt .arno = arptinc.arno
//                                AND arpt.canceldate IS NULL
//                                AND arpt.pttype = pttype.pttype
//                                AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                                AND arpt.arno = :ARNO
//                       GROUP BY TO_CHAR (arpt.hn),
//                                CASE
//                                 WHEN arpt.an > 9000000
//                                 THEN
//                                    'N' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                 THEN
//                                    '0' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                 THEN
//                                    '00' || TO_CHAR (arpt.an)
//                                 ELSE
//                                    TO_CHAR (arpt.an)
//                              END,
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd'),
//                                LPAD (ptno.cardno, 13, 0),
//                                TO_CHAR (arpt.arno),
//                                arpt.vstdate,
//                                arpt.argrp,
//                                arpt.trndate,
//                                arpt.arno,
//                                arpt.arno,
//                                pttype.pttypegrp";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_cha()//12 มาตรฐานแฟ้มข้อมูลการเงิน (แบบรายละเอียด)***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, AN, DATE_, CHRGITEM, AMOUNT, PERSON_ID, SEQ, 
                              to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP, 
                              to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO 
                              from rjvt.eclaim_opbkk12_cha 
                              where ARNO = :ARNO";

//            $strSql = "SELECT TO_CHAR (arpt.hn) AS HN,
//                               CASE
//                                     WHEN arpt.an > 9000000
//                                     THEN
//                                        'N' || TO_CHAR (arpt.an)
//                                     WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                     THEN
//                                        '0' || TO_CHAR (arpt.an)
//                                     WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                     THEN
//                                        '00' || TO_CHAR (arpt.an)
//                                     ELSE
//                                        TO_CHAR (arpt.an)
//                                END
//                                      AS AN,
//                                TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATE_,
//                                CASE
//                                        WHEN arpt.an is null then
//                                               CASE
//                                                  WHEN arincgrp.cscd IN ('1', 'F', 'G')
//                                                  THEN
//                                                     'J'
//                                                  ELSE
//                                                     CASE
//                                                        WHEN arincgrp.cscd = '3' THEN '4'
//                                                        ELSE TRIM (arincgrp.cscd)
//                                                     END
//                                               END
//                                            || '1'
//                                ELSE
//                                             TRIM (arincgrp.cscd) || '1'
//                                END
//                                    AS CHRGITEM,
//                                    NVL (SUM (arptinc.invamt), 0) AS AMOUNT,
//                                    LPAD (ptno.cardno, 13, 0) AS PERSON_ID,
//                                    TO_CHAR (arpt.arno) AS SEQ,
//                                     to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                                    arpt.argrp AS ARGRP,
//                                     to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                                    arpt.arno AS ARNO
//                               FROM arpt
//                                    LEFT OUTER JOIN ptno ON arpt.hn = ptno.hn AND ptno.notype = 10,
//                                    arptinc,
//                                    arincome,
//                                    arincgrp
//                              WHERE                                                  --arpt.an IS NULL
//                                   arpt .arno = arptinc.arno
//                                    AND arptinc.income = arincome.income
//                                    AND arincome.inctype = 10
//                                    AND arincgrp.inctype = arincome.inctype
//                                    AND arincgrp.incgrp = arincome.incgrp
//                                    AND arpt.canceldate IS NULL
//                                    AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                                    AND arpt.arno = :ARNO
//                           --and arptinc.laststf <> -99
//                           GROUP BY TO_CHAR (arpt.hn),
//                                    CASE
//                                     WHEN arpt.an > 9000000
//                                     THEN
//                                        'N' || TO_CHAR (arpt.an)
//                                     WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                     THEN
//                                        '0' || TO_CHAR (arpt.an)
//                                     WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                     THEN
//                                        '00' || TO_CHAR (arpt.an)
//                                     ELSE
//                                        TO_CHAR (arpt.an)
//                                  END,
//                                    TO_CHAR (arpt.vstdate, 'yyyymmdd'),
//                        case when arpt.an is null then
//                                       CASE
//                                          WHEN arincgrp.cscd IN ('1', 'F', 'G')
//                                          THEN
//                                             'J'
//                                          ELSE
//                                             CASE
//                                                WHEN arincgrp.cscd = '3' THEN '4'
//                                                ELSE TRIM (arincgrp.cscd)
//                                             END
//                                       END
//                                    || '1'
//                        else
//                                     TRIM (arincgrp.cscd) || '1'
//                        end,
//                                    LPAD (ptno.cardno, 13, 0),
//                                    TO_CHAR (arpt.arno),
//                                    arpt.vstdate,
//                                    arpt.argrp,
//                                    arpt.trndate,
//                                    arpt.arno
//                              having NVL (SUM (arptinc.invamt), 0) > 0";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_aer()//13 มาตรฐานแฟ้มข้อมูลอุบัติเหตุ ฉุกเฉิน และรับส่งเพื่อรักษา
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, AN, DATEOPD, AUTHAE, AEDATE, AETIME, AETYPE, REFER_NO, REFMAINI, IREFTYPE, REFMAINO, OREFTYPE, UCAE, EMTYPE, SEQ,
                              to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                              to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO,
                              AESTATUS, DALERT, TALERT
                              from rjvt.eclaim_opbkk13_aer
                              where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                              TO_CHAR (arpt.hn) AS HN,
//                              CASE
//                                 WHEN arpt.an > 9000000
//                                 THEN
//                                    'N' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                 THEN
//                                    '0' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                 THEN
//                                    '00' || TO_CHAR (arpt.an)
//                                 ELSE
//                                    TO_CHAR (arpt.an)
//                              END
//                                 AS AN,
//                              TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEOPD,
//                              '' AS AUTHAE,
//                              '' AS AEDATE,
//                              '' AS AETIME,
//                              '' AS AETYPE,
//                              --incprvlg.sheetno AS REFER_NO,
//                              CASE
//                                 WHEN arpt.an IS NULL and incprvlg.pttype <> 7095
//                                 THEN
//                                    incprvlg.sheetno
//                                 ELSE
//                                    ''
//                              END
//                                 AS REFER_NO,
//                              case when incprvlg.pttype <> 7095 then LPAD (
//                                 CASE
//                                    WHEN arpt.argrp IN (2010, 2020, 2025)
//                                    THEN
//                                       TO_CHAR (NVL (incprvlg.mainhpt, incprvlg.supphpt))
//                                    ELSE
//                                       TO_CHAR (
//                                          NVL (NVL (incprvlg.mainhpt, incprvlg.referhpt), 10662))
//                                 END,
//                                 5,
//                                 0) end
//                                 AS REFMAINI,
//                              case when incprvlg.pttype <> 7095 then '0100' end AS IREFTYPE,
//                              '' AS REFMAINO,
//                              '' AS OREFTYPE,
//                              CASE
//                                 WHEN arpt.argrp IN (2010, 2020, 2025)
//                                 THEN
//                                    'A'
//                                 WHEN arpt.argrp IN (2040,
//                                                     2060,
//                                                     2061,
//                                                     2062)
//                                 THEN
//                                    ''
//                                 WHEN incprvlg.acer = 1
//                                 THEN
//                                    'A'
//                                 WHEN incprvlg.acer = 2
//                                 THEN
//                                    'E'
//                                 ELSE
//                                    ''
//                              END
//                                 AS UCAE,
//                              '' AS EMTYPE,
//                              TO_CHAR (arpt.arno) AS SEQ,
//                              to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                              arpt.argrp AS ARGRP,
//                              to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                              arpt.arno AS ARNO
//                              , '' as AESTATUS
//                              , '' as DALERT
//                              , '' as TALERT
//                         FROM arpt,
//                              incpt
//                              LEFT OUTER JOIN ovst
//                                 ON (    ovst.hn = incpt.hn
//                                     AND incpt.vn = ovst.vn
//                                     AND incpt.fn = ovst.fn)
//                              LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct)
//                              LEFT OUTER JOIN incprvlg
//                                 ON (    incprvlg.hn = incpt.hn
//                                     AND incprvlg.prvno = incpt.prvno
//                     --                AND incprvlg.pttype = incpt.pttype --เอาออกเพราะ ระบบต้นทางยิงสิทธิมามั่วตลอดเลยทำให้rowเบิ้ล rpsd 08/12/60
//                                     )
//                              LEFT OUTER JOIN pttype ON (incpt.pttype = pttype.pttype)
//                        WHERE     arpt.arno = incpt.arno
//                              AND arpt.an IS NULL
//                              AND incpt.itemno > 0
//                              AND incpt.incamt <> 0
//                              AND arpt.canceldate IS NULL
//                              AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                              AND arpt.arno = :ARNO";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_adp()//14 มาตรฐานแฟ้มข้อมูลค่าใช้จ่ายเพิ่ม และบริการที่ยังไม่ได้จัดหมวด***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HN, AN, DATEOPD, TYPE, CODE, QTY, RATE, SEQ, CAGCODE, DOSE, 
                              to_char(VSTDATE,'dd/mm/yyyy') AS VSTDATE, ARGRP,
                              to_char(TRNDATE,'dd/mm/yyyy') AS TRNDATE, ARNO,
                              CA_TYPE, SERIALNO, TOTCOPAY, USE_STATUS, TOTAL, QTYDAY, TMLTCODE, STATUS1, BI, 
                              CLINIC, ITEMSRC, PROVIDER, GRAVIDE, GA_WEEK, DCIP, LMP from rjvt.eclaim_opbkk14_adp where ARNO = :ARNO";

//            $strSql = "SELECT DISTINCT
//                              TO_CHAR (arpt.hn) AS HN,
//                              CASE
//                                 WHEN arpt.an > 9000000
//                                 THEN
//                                    'N' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 6
//                                 THEN
//                                    '0' || TO_CHAR (arpt.an)
//                                 WHEN LENGTH (TO_CHAR (arpt.an)) = 5
//                                 THEN
//                                    '00' || TO_CHAR (arpt.an)
//                                 ELSE
//                                    TO_CHAR (arpt.an)
//                              END
//                                 AS AN,
//                              TO_CHAR (arpt.vstdate, 'yyyymmdd') AS DATEOPD,
//                              '' AS AUTHAE,
//                              '' AS AEDATE,
//                              '' AS AETIME,
//                              '' AS AETYPE,
//                              --incprvlg.sheetno AS REFER_NO,
//                              CASE
//                                 WHEN arpt.an IS NULL and incprvlg.pttype <> 7095
//                                 THEN
//                                    incprvlg.sheetno
//                                 ELSE
//                                    ''
//                              END
//                                 AS REFER_NO,
//                              case when incprvlg.pttype <> 7095 then LPAD (
//                                 CASE
//                                    WHEN arpt.argrp IN (2010, 2020, 2025)
//                                    THEN
//                                       TO_CHAR (NVL (incprvlg.mainhpt, incprvlg.supphpt))
//                                    ELSE
//                                       TO_CHAR (
//                                          NVL (NVL (incprvlg.mainhpt, incprvlg.referhpt), 10662))
//                                 END,
//                                 5,
//                                 0) end
//                                 AS REFMAINI,
//                              case when incprvlg.pttype <> 7095 then '0100' end AS IREFTYPE,
//                              '' AS REFMAINO,
//                              '' AS OREFTYPE,
//                              CASE
//                                 WHEN arpt.argrp IN (2010, 2020, 2025)
//                                 THEN
//                                    'A'
//                                 WHEN arpt.argrp IN (2040,
//                                                     2060,
//                                                     2061,
//                                                     2062)
//                                 THEN
//                                    ''
//                                 WHEN incprvlg.acer = 1
//                                 THEN
//                                    'A'
//                                 WHEN incprvlg.acer = 2
//                                 THEN
//                                    'E'
//                                 ELSE
//                                    ''
//                              END
//                                 AS UCAE,
//                              '' AS EMTYPE,
//                              TO_CHAR (arpt.arno) AS SEQ,
//                              to_char(arpt.vstdate,'dd/mm/yyyy') AS VSTDATE,
//                              arpt.argrp AS ARGRP,
//                              to_char(arpt.trndate,'dd/mm/yyyy') AS TRNDATE,
//                              arpt.arno AS ARNO
//                              , '' as AESTATUS
//                              , '' as DALERT
//                              , '' as TALERT
//                         FROM arpt,
//                              incpt
//                              LEFT OUTER JOIN ovst
//                                 ON (    ovst.hn = incpt.hn
//                                     AND incpt.vn = ovst.vn
//                                     AND incpt.fn = ovst.fn)
//                              LEFT OUTER JOIN lct ON (ovst.cliniclct = lct.lct)
//                              LEFT OUTER JOIN incprvlg
//                                 ON (    incprvlg.hn = incpt.hn
//                                     AND incprvlg.prvno = incpt.prvno
//                     --                AND incprvlg.pttype = incpt.pttype --เอาออกเพราะ ระบบต้นทางยิงสิทธิมามั่วตลอดเลยทำให้rowเบิ้ล rpsd 08/12/60
//                                     )
//                              LEFT OUTER JOIN pttype ON (incpt.pttype = pttype.pttype)
//                        WHERE     arpt.arno = incpt.arno
//                              AND arpt.an IS NULL
//                              AND incpt.itemno > 0
//                              AND incpt.incamt <> 0
//                              AND arpt.canceldate IS NULL
//                              AND arpt.ardate >= TO_DATE ('11012012', 'mmddyyyy')
//                              AND arpt.arno = :ARNO";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_lvd()//15 มาตรฐานแฟ้มข้อมูลกรณีที่ผู้ป่วยมีการลากลับบ้าน (Leave day)
    {

    }


    public function data_dru()//16 มาตรฐานแฟ้มข้อมูลการใช้ยา***
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {

            $arno = $_POST['ARNO'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select HCODE, HN, AN, CLINIC, PERSON_ID, DATE_SERV, DID, DIDSTD, DIDNAME, 
                                DRUGCOST, UNIT, UNIT_PACK, SEQ, DRUGTYPE, DRUGREMARK, PA_NO,  
                                to_char(VSTDATE,'dd/mm/yyyy') as VSTDATE, ARGRP, 
                                to_char(TRNDATE,'dd/mm/yyyy') as TRNDATE, ARNO, 
                                AMOUNT, DRUGPRIC, TOTCOPAY, USE_STATUS, TOTAL, SIGCODE, 
                                SIGTEXT, PROVIDER from rjvt.eclaim_opbkk16_dru where ARNO = :ARNO";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ARNO' => $arno));

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


    public function data_labfu()//15 แฟ้มข้อมูลตรวจทางห้องปฎิบัติการของผู้ป่วยโรคเรื้อรัง
    {

    }


}
