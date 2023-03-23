<?php

class medicalrecord_api_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function medicalrecord_admin()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['ADMINTYPE_ID'] == "ADMIN_ALL" && $_POST['ADMIN_ID'] == ""){
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select RJMedicalRecord_Admin. *  from webout.RJMedicalRecord_Admin";

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
            }else if($_POST['ADMINTYPE_ID'] == "ADMIN_INSERT" && $_POST['ADMIN_ID'] == ""){
                $ADMIN_IDCARD = $_POST['ADMIN_IDCARD'];
                $ADMIN_NAME = $_POST['ADMIN_NAME'];
                $ADMIN_AGENCY_ID = $_POST['ADMIN_AGENCY_ID'];
                $TYPE_ID = $_POST['TYPE_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "INSERT INTO webout.RJMedicalRecord_Admin (ADMIN_ID, ADMIN_NAME, ADMIN_AGENCY_ID, TYPE_ID)
                                  VALUES (:ADMIN_IDCARD , :ADMIN_NAME ,:ADMIN_AGENCY_ID , :TYPE_ID)";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ADMIN_IDCARD' => $ADMIN_IDCARD,':ADMIN_NAME' => $ADMIN_NAME,':ADMIN_AGENCY_ID' => $ADMIN_AGENCY_ID,':TYPE_ID' => $TYPE_ID));

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
            }else if($_POST['ADMINTYPE_ID'] == "ADMIN_DELETE" && $_POST['ADMIN_ID'] != ""){
                $admin_id = $_POST['ADMIN_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "DELETE FROM webout.RJMedicalRecord_Admin where ADMIN_ID = :ADMINID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ADMINID' => $admin_id));

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
            }else{
                $admin_id = $_POST['ADMIN_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select RJMedicalRecord_Admin. *  from webout.RJMedicalRecord_Admin where ADMIN_ID = :ADMINID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ADMINID' => $admin_id));

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

    public function medr_access_ip()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['IPTYPE'] == "IP_MAX" && $_POST['ACCESS_IP'] == "") {
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select MAX(IP_ID) as MAXID from webout.RJMedR_Accession";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute();

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
            }else if ($_POST['IPTYPE'] == "IP_ALL" && $_POST['ACCESS_IP'] == ""){
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select 
                                IP_ID,
                                ACCESS_IP,
                                cast(NOTE_IP AS varchar2(2000)) as NOTE_IP
                                from webout.RJMedR_Accession ORDER BY IP_ID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute();

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
            }else if ($_POST['IPTYPE'] == "IP_INSERT" && $_POST['ACCESS_IP'] != "" ){
                $IP_ID = $_POST['IP_ID'];
                $ACCESS_IP = $_POST['ACCESS_IP'];
                $NOTE_IP = $_POST['NOTE_IP'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "INSERT INTO webout.RJMedR_Accession (IP_ID, ACCESS_IP,NOTE_IP)
                                VALUES (:IP_ID , :ACCESS_IP , :NOTE_IP)";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':IP_ID' => $IP_ID,':ACCESS_IP' => $ACCESS_IP,':NOTE_IP' => $NOTE_IP));

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
            }else if($_POST['IPTYPE'] == "IP_DELETE" && $_POST['ACCESS_IP'] != ""){
                $ACCESS_IP = $_POST['ACCESS_IP'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "DELETE FROM webout.RJMedR_Accession where ACCESS_IP = :ACCESS_IP";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ACCESS_IP' => $ACCESS_IP));

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
            }else{
                $ACCESS_IP = $_POST['ACCESS_IP'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select 
                                IP_ID,
                                ACCESS_IP,
                                cast(NOTE_IP AS varchar2(2000)) as NOTE_IP
                                from webout.RJMedR_Accession where ACCESS_IP = :ACCESS_IP";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webout->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ACCESS_IP' => $ACCESS_IP));

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

    public function check_children() // เช็คมีเด็กกี่คน
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            dlvst.hn hn_mum, 
                            dlvst.an an_mum, 
                            pttno.CARDNO,
                            cast(pt.dspname AS varchar2(2000)) as dspname_mum, 
                            TO_CHAR(ancvst.EDCDATE, 'MM/DD/YYYY')as EDCANCVST ,
                            TO_CHAR(dlvst.EDC, 'MM/DD/YYYY')as EDCDLVST ,
                            ancvst.ANCNO,
                            TO_CHAR(ancvst.ANCDATE, 'MM/DD/YYYY')as ANCDATE ,
                            ancvst.ANCTIME as ANCTIME,
                            ptt.hn hn_children, 
                            cast(ptt.dspname AS varchar2(2000)) as dspname_children, 
                            TO_CHAR(ptt.brthdate, 'DD/MM/YYYY')as pttbrthdate_children,
                            TO_CHAR(ptt.brthtime, 'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as ptt_brthtime,
                            TO_CHAR(dlvstdt.brthdate, 'DD/MM/YYYY')as dlvbrthdate_children,
                            TO_CHAR(dlvstdt.brthtime, 'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as dlv_brthtime,
                            TO_CHAR(dlvstdt.DLVSTDATE , 'DD/MM/YYYY')as DLVSTDATE, 
                            dlvstdt.DLVSTTIME,
                            TO_CHAR(dlvst.RGTDATE , 'MM/DD/YYYY')as admit_date,
                            cast(DLST.NAME AS varchar2(2000)) as  DLST_NAME,
                            TO_DATE(ptt.brthdate , 'DD/MM/YYYY') Dbrth,
                            dlvstdt.brthtime Tbryh
                            --, ptt.momhn, ptt.moman, ptt.brthdate, dlvstdt.hninfant,dlvst.brthdate, dlvst.* 
                            from dlvst
                            left outer join ancvst 
                                 on dlvst.Hn = ancvst.hn
                                 
                                 and dlvst.EDC = ancvst.EDCDATE
                            left outer join dlvstdt
                                 on dlvst.Hn = dlvstdt.hn
                                 and dlvst.DLVSTDATE = dlvstdt.dlvstdate
                                 and dlvst.DLVSTTIME = dlvstdt.dlvsttime
                            left outer join pt
                                 on dlvst.hn = pt.hn
                                 and pt.canceldate is null
                            left outer join DLST
                                 on dlvst.DLST = DLST.DLST
                            left outer join (select hn, momhn, moman, brthdate ,brthtime, dspname
                                                      from pt 
                                                      where momhn = :HN_MOM
                                                    ) ptt
                               on dlvstdt.hn = ptt.momhn
                               and dlvstdt.an = ptt.moman
                               and dlvstdt.brthdate = ptt.brthdate
                               and dlvstdt.brthtime = ptt.brthtime
                               left outer join (select HN,CARDNO
                                                        from ptno 
                                                        where hn =  :HN_MOM
                                                        and notype = 10
                                                      ) pttno
                                on dlvstdt.hn = pttno.hn
                            
                            where dlvst.hn = :HN_MOM
                            and DLST.NAME = 'คลอดแล้ว'
                            and dlvst.cncldate is null 
                           order by TO_DATE(ptt.brthdate , 'DD/MM/YYYY') desc,dlvstdt.brthtime desc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM));

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

    public function data_children() // ข้อมูลเด็ก
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $AN_MOM = $_POST['AN_MOM'];
            $BRTH_CHILDREN = $_POST['BRTH_CHILDREN'];
            $HN_CHILDREN = $_POST['HN_CHILDREN'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            dlvst.hn hn_mum, 
                            cast(pt.dspname AS varchar2(2000)) as MDSPNAME, 
                            BLOODGRP.NAME as BLOODGRPMOM,
                            ancvst.G as GMOM,
                            ancvst.P as PMOM,
                            ancvst.A as AMOM,
                            dlvstext.CHILDLIVE as LMOM,
                            DLANCPLACE.NAME as ANCLCTNAME,
                            dlvstanc.ANCNO as ANCNOAMOM,
                            cast(dlvst.HSBNNM AS varchar2(2000)) as FDSPNAME,
                            cast(ptaddr.ADDR AS varchar2(2000)) as ADDRMOM,
                            cast(AMPUR.NAME AS varchar2(2000)) as AMPURMOM,
                            cast(TUMBON.NAME AS varchar2(2000)) as TUMBONMOM,
                            cast(CHANGWAT.NAME AS varchar2(2000)) as CHANGWATMOM,
                            TO_CHAR(ancvst.EDCDATE, 'MM/DD/YYYY')as EDCANCVST,
                            TO_CHAR(dlvst.EDC, 'MM/DD/YYYY')as EDCMOM,
                            ptt.hn hn_children, 
                            ptt.an an_children, 
                            cast(ptt.dspname AS varchar2(2000)) as CDSPNAME,
                            MALE.NAME as MALE,
                            pttno.CARDNO as CCARDNO,
                            cast(NTNLTY.NAME AS varchar2(2000)) as ETHNIC,
                            cast(NT.NAME AS varchar2(2000)) as NTNLTY,
                            cast(RLGN.NAME AS varchar2(2000)) as NRLGN,
                            TO_CHAR(ptt.brthdate, 'MM/DD/YYYY')as brthdate_children,
                            cast(ptt.ADDR AS varchar2(2000)) as ADDR,
                            cast(ptt.tumbon_name AS varchar2(2000)) as tumbon_name,
                            cast(ptt.ampur_name AS varchar2(2000)) as ampur_name,
                            cast(ptt.changwat_name AS varchar2(2000)) as changwat_name,
                            pt.MRTLST,
                            twins.cnt_twin
                            from dlvst
                            left outer join ancvst
                                 on dlvst.Hn = ancvst.hn
                                 and dlvst.EDC = ancvst.EDCDATE
                             left outer join dlvstanc
                                  on dlvst.Hn = dlvstanc.Hn 
                             left outer join DLANCPLACE
                                  on dlvstanc.ANCLCT = DLANCPLACE.ANCPLACE
                            left outer join dlvstext 
                                 on dlvst.Hn = dlvstext.Hn
                                 and dlvst.DLVSTDATE = dlvstext.DLVSTDATE  
                                 and dlvst.DLVSTTIME = dlvstext.dlvsttime                    
                            left outer join dlvstdt
                                  on dlvst.Hn = dlvstdt.hn
                                  and dlvst.DLVSTDATE = dlvstdt.dlvstdate
                                  and dlvst.DLVSTTIME = dlvstdt.dlvsttime
                            left outer join pt
                                 on dlvst.hn = pt.hn
                                 and pt.canceldate is null
                            left outer join BLOODGRP
                                  on pt.BLOODGRP = BLOODGRP.BLOODGRP      
                            left outer join MALE
                                  on pt.MALE = MALE.MALE
                            left outer join NTNLTY
                                  on pt.CTZSHP = NTNLTY.NTNLTY
                            left outer join RLGN
                                 on pt.RLGN = RLGN.RLGN  
                            left outer join NTNLTY NT
                                 on pt.NTNLTY = NT.NTNLTY 
                            left outer join ptaddr
                                  on pt.hn = ptaddr.hn
                                  and ptaddr.addrtype = 10
                                  and ptaddr.addrflag = 1
                            left outer join tumbon
                                  on ptaddr.tumbon = tumbon.tumbon
                            left outer join ampur
                                   on ptaddr.ampur = ampur.ampur                        
                            left outer join CHANGWAT
                                   on ptaddr.changwat = changwat.changwat                           
                            left outer join (select pt.hn,ipt.an, pt.momhn, pt.moman, pt.brthdate, pt.brthtime, pt.dspname, 
                                                    ptaddr.addr, ptaddr.TUMBON, tumbon.name tumbon_name, ptaddr.AMPUR, ampur.name ampur_name, ptaddr.CHANGWAT, changwat.name changwat_name
                                                    from pt 
                                                        left outer join ptaddr
                                                               on pt.hn = ptaddr.hn
                                                               and ptaddr.addrtype = 10
                                                               and ptaddr.addrflag = 1
                                                        left outer join tumbon
                                                                on ptaddr.tumbon = tumbon.tumbon
                                                        left outer join ampur
                                                                on ptaddr.ampur = ampur.ampur                        
                                                        left outer join CHANGWAT
                                                                on ptaddr.changwat = changwat.changwat
                                                        left outer join ipt
                                                                on ptaddr.hn = ipt.hn        
                                                    where momhn = :HN_MOM  ) ptt
                                 on dlvstdt.hn = ptt.momhn
                                 and dlvstdt.an = ptt.moman
                                 and dlvstdt.brthdate = ptt.brthdate
                                 and dlvstdt.brthtime = ptt.brthtime
                            left outer join (select HN,CARDNO from ptno 
                                                        where hn = :HN_CHILDREN
                                                        and notype = 10 ) pttno
                                   on ptt.hn = pttno.hn  
                            left outer join (select  momhn,moman,brthdate, count(*) cnt_twin
                                                      from pt 
                                                      where momhn = :HN_MOM
                                                      and moman = :AN_MOM
                                                      and brthdate = to_date(:BRTH_CHILDREN,'dd/mm/yyyy')
                                                      group by momhn,moman,brthdate ) twins
                                            on dlvstdt.hn = twins.momhn
                                     and dlvstdt.an = twins.moman
                                     and dlvstdt.brthdate = twins.brthdate
                                    
                            where dlvst.hn = :HN_MOM
                            and dlvst.cncldate is null 
                            and ptt.hn = :HN_CHILDREN";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM,':HN_CHILDREN' => $HN_CHILDREN,':AN_MOM' => $AN_MOM,':BRTH_CHILDREN' => $BRTH_CHILDREN));

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

    public function data_labmom()// / ข้อมูลการฝากครรภ์ บันทึกผลทางห้องปฏิบัติการ
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $ANCNO = $_POST['ANCNO'];
            $ANCDATE = $_POST['ANCDATE'];
            $ANCTIME = $_POST['ANCTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            hn,ancno,
                            TO_CHAR(ancdate, 'DD/MM/YYYY')as ancdate, 
                            TO_CHAR(anctime, 'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as anctime,  
                            to_char(LABDATE1, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS LABDATE1,
                            CONVERT(BLOODGRP1 ,'UTF8' ,'TH8TISASCII') as BLOODGRP1,
                            CONVERT(RH1 ,'UTF8' ,'TH8TISASCII') as RH1,
                            CONVERT(ICT1 ,'UTF8' ,'TH8TISASCII') as ICT1,
                            CONVERT(HBSAG1 ,'UTF8' ,'TH8TISASCII') as HBSAG1,
                            CONVERT(HIV1 ,'UTF8' ,'TH8TISASCII') as HIV1,
                            CONVERT(VDRL1 ,'UTF8' ,'TH8TISASCII') as VDRL1,
                            CONVERT(ALBUMIN ,'UTF8' ,'TH8TISASCII') as ALBUMIN,
                            CONVERT(SUGAR ,'UTF8' ,'TH8TISASCII') as SUGAR,
                            CONVERT(HBEAG1 ,'UTF8' ,'TH8TISASCII') as HBEAG1,
                            CONVERT(TPHA1 ,'UTF8' ,'TH8TISASCII') as TPHA1,
                            CONVERT(FTAABS1 ,'UTF8' ,'TH8TISASCII') as FTAABS1,
                            to_char(LABADDDATE, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS LABADDDATE,
                            cast(CASE 
                                          WHEN LAB1IN =  1  THEN 'ราชวิถี'
                                          WHEN LAB1IN =  2 THEN 'LAB นอก'
                                ELSE '-' END  AS varchar2(2000))AS LAB1IN,
                            cast(CASE  
                                          WHEN LABHUSBANDIN =  1 THEN 'ราชวิถี'
                                          WHEN LABHUSBANDIN =  2  THEN 'LAB นอก'
                                ELSE '-' END AS varchar2(2000)) AS LABHUSBANDIN,
                            to_char(LABDATE2, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS LABDATE2,
                            CONVERT(HB2 ,'UTF8' ,'TH8TISASCII') as HB2,
                            CONVERT(HCT2 ,'UTF8' ,'TH8TISASCII') as HCT2,
                            CONVERT(HIV2 ,'UTF8' ,'TH8TISASCII') as HIV2,
                            CONVERT(VDRL2 ,'UTF8' ,'TH8TISASCII') as VDRL2,
                            CONVERT(TPHA2 ,'UTF8' ,'TH8TISASCII') as TPHA2,
                            CONVERT(FTAABS2 ,'UTF8' ,'TH8TISASCII') as FTAABS2,
                            cast(CASE 
                                           WHEN LAB2IN =  1 THEN 'ราชวิถี'
                                           WHEN LAB2IN =  2  THEN 'LAB นอก'
                                ELSE '-' END AS varchar2(2000))AS LAB2IN,
                            cast(CASE  
                                          WHEN LABTHALASIN =  1  THEN 'ราชวิถี'
                                              WHEN LABTHALASIN =  2  THEN 'LAB นอก'
                                 ELSE '-' END AS varchar2(2000)) AS LABTHALASIN,
                            CONVERT(HB ,'UTF8' ,'TH8TISASCII') as HB,
                            CONVERT(HCT ,'UTF8' ,'TH8TISASCII') as HCT,
                            CONVERT(MCV ,'UTF8' ,'TH8TISASCII') as MCV,
                            CONVERT(LBOF ,'UTF8' ,'TH8TISASCII') as LBOF,
                            CONVERT(DCIP ,'UTF8' ,'TH8TISASCII') as DCIP,
                            CONVERT(HBTYPING ,'UTF8' ,'TH8TISASCII') as HBTYPING,
                            CONVERT(PCR ,'UTF8' ,'TH8TISASCII') as PCR,
                            CONVERT(HSBHB ,'UTF8' ,'TH8TISASCII') as HSBHB,
                            CONVERT(HSBHCT ,'UTF8' ,'TH8TISASCII') as HSBHCT,
                            CONVERT(HSBMCV ,'UTF8' ,'TH8TISASCII') as HSBMCV,
                            CONVERT(HSBOF ,'UTF8' ,'TH8TISASCII') as HSBOF,
                            CONVERT(HSBDCIP ,'UTF8' ,'TH8TISASCII') as HSBDCIP,
                            CONVERT(HSBHBTYPING ,'UTF8' ,'TH8TISASCII') as HSBHBTYPING,
                            CONVERT(HBPCR ,'UTF8' ,'TH8TISASCII') as HBPCR,
                            HSBHN,
                            CONVERT(HSBHIV ,'UTF8' ,'TH8TISASCII') as HSBHIV,
                            CONVERT(HSBTPHA ,'UTF8' ,'TH8TISASCII') as HSBTPHA,
                            CONVERT(HSBVDRL ,'UTF8' ,'TH8TISASCII') as HSBVDRL,
                            CONVERT(HSBNAME ,'UTF8' ,'TH8TISASCII') as HSBNAME,
                            COUPLERISK,
                            BMAJOR,
                            BHBE,
                            BDISEASE,
                            CONVERT(PND ,'UTF8' ,'TH8TISASCII') as PND,
                            CVS,
                            AMNIOCENTESIS,
                            CORDOCENTESIS,
                            to_char(dmdate1, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS dmdate1,
                            CONVERT(dm50g1 ,'UTF8' ,'TH8TISASCII') as dm50g1,
                            CONVERT(dm75g1f ,'UTF8' ,'TH8TISASCII') as dm75g1f,
                            CONVERT(dm75g1s ,'UTF8' ,'TH8TISASCII') as dm75g1s,
                            CONVERT(dm75g1t ,'UTF8' ,'TH8TISASCII') as dm75g1t,
                            dm1rst,
                            CONVERT(case 
                              when dm1rst = '1' then 'normal'
                              when dm1rst = '2' then 'abnormal'
                             else '-'
                               end ,'UTF8' ,'TH8TISASCII') as dm1st,
                            to_char(dmdate2, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS dmdate2,
                               CONVERT(dm50g2 ,'UTF8' ,'TH8TISASCII') as dm50g2,
                               CONVERT(dm75g2f ,'UTF8' ,'TH8TISASCII') as dm75g2f,
                               CONVERT(dm75g2s ,'UTF8' ,'TH8TISASCII') as dm75g2s,
                               CONVERT(dm75g2t ,'UTF8' ,'TH8TISASCII') as dm75g2t,
                            dm2rst,
                            CONVERT(case 
                              when dm2rst = '1' then 'normal'
                              when dm2rst = '2' then 'abnormal'
                             else '-'
                               end ,'UTF8' ,'TH8TISASCII') as dm1st2,
                            to_char(dmdate3, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS dmdate3,
                            CONVERT(dm50g3 ,'UTF8' ,'TH8TISASCII') as dm50g3,
                            CONVERT(dm75g3f ,'UTF8' ,'TH8TISASCII') as dm75g3f,
                            CONVERT(dm75g3s ,'UTF8' ,'TH8TISASCII') as dm75g3s,
                            CONVERT(dm75g3t ,'UTF8' ,'TH8TISASCII') as dm75g3t,
                            dm3rst,
                            CONVERT(case 
                              when dm3rst = '1' then 'normal'
                              when dm3rst = '2' then 'abnormal'
                             else '-'
                               end ,'UTF8' ,'TH8TISASCII') as dm1st3,
                               to_char(DMDATE4, 'dd/mm/yyyy', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') AS DMDATE4,
                            CONVERT(DM50G4 ,'UTF8' ,'TH8TISASCII') as DM50G4,
                            CONVERT(DM75G4F ,'UTF8' ,'TH8TISASCII') as DM75G4F,
                            CONVERT(DM75G4S ,'UTF8' ,'TH8TISASCII') as DM75G4S,
                            CONVERT(DM75G4S ,'UTF8' ,'TH8TISASCII') as DM75G4S,
                            CONVERT(DM75G1Q ,'UTF8' ,'TH8TISASCII') as DM75G1Q,
                            CONVERT(DM75G2Q ,'UTF8' ,'TH8TISASCII') as DM75G2Q,
                            CONVERT(DM75G3Q ,'UTF8' ,'TH8TISASCII') as DM75G3Q,
                            BIOCMSCRN,
                            CHROMOSOME,
                            BIOCMRST,
                            CHROMORST,
                            CONVERT(BIOCMVAL ,'UTF8' ,'TH8TISASCII') as BIOCMVAL,
                            CONVERT(BIOCMDTL ,'UTF8' ,'TH8TISASCII') as BIOCMDTL,
                            CONVERT(CHROMOVAL ,'UTF8' ,'TH8TISASCII') as CHROMOVAL,
                            CONVERT(CHROMEDTL ,'UTF8' ,'TH8TISASCII') as CHROMEDTL
                            
                            
                            from rjvt.ancvstlab 
                            
                              where hn = :HN_MOM
                              and ancno = :ANCNO
                              and ancdate = to_date(:ANCDATE,'mm/dd/yyyy')
                              and anctime =  :ANCTIME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':ANCNO' => $ANCNO, ':ANCDATE' => $ANCDATE, ':ANCTIME' => $ANCTIME));

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

    public function data_mthds() // โรคประจำตัวของมารดา
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $DLVSTDATE = $_POST['DLVSTDATE'];
            $DLVSTTIME = $_POST['DLVSTTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select DLVSTMTHDS.HN,
                            TO_CHAR(DLVSTMTHDS.DLVSTDATE, 'MM/DD/YYYY')as DLVSTDATE , 
                            DLVSTMTHDS.DLVSTTIME, 
                            DLVSTMTHDS.MTHDS,
                            cast(dlmthds.NAME  AS varchar2(2000)) as MTHDSNAME 
                            from DLVSTMTHDS 
                            left outer join dlmthds 
                                   on DLVSTMTHDS.MTHDS = dlmthds.MTHDS
                                  where DLVSTMTHDS.HN = :HN_MOM
                                  and DLVSTMTHDS.DLVSTDATE = to_date(:DLVSTDATE,'dd/mm/yyyy')
                                  and DLVSTMTHDS.DLVSTTIME =  :DLVSTTIME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':DLVSTDATE' => $DLVSTDATE, ':DLVSTTIME' => $DLVSTTIME));

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

    public function data_brthsignmed() // ยาที่มารดารับประทานขณะตั้งครรภ์
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $DLVSTDATE = $_POST['DLVSTDATE'];
            $DLVSTTIME = $_POST['DLVSTTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select dlvst.HN, 
                            TO_CHAR(dlvst.DLVSTDATE, 'MM/DD/YYYY')as DLVSTDATE ,
                            dlvst.DLVSTTIME,
                            cast(dlvst.BRTHSIGNMED  AS varchar2(2000)) as BRTHSIGNMED,
                            dlvst.WMBAGE,
                            dlvstext.US,
                            dlvst.BLDSHED,
                            dlvst.BBA,
                            TO_CHAR(dlvst.WTBRDATE, 'MM/DD/YYYY')as WTBRDATE,
                            dlvstdt.DLMTHD,
                            cast(dlmthd.NAME  AS varchar2(2000)) as DLMTHDNAME,
                            dlvst.FLUIDCOLRBFR,
                            cast(dlfluidcolr.NAME  AS varchar2(2000)) as FLUIDCOLRNAME,
                            dlvstdt.WEIGHT,
                            dlvstdt.HEAD,
                            dlvstdt.CHEST,
                            dlvstdt.BODY,
                            dlvstdt.BT,
                            dlvstdt.STBT,
                            dlvstdt.NDBT
                            from dlvst 
                            left outer join dlvstext 
                                     on dlvst.Hn = dlvstext.Hn
                                     and dlvst.DLVSTDATE = dlvstext.DLVSTDATE
                                     and dlvst.DLVSTTIME = dlvstext.DLVSTTIME
                            left outer join dlvstdt 
                                     on dlvst.Hn = dlvstdt.Hn 
                                     and dlvst.DLVSTDATE = dlvstdt.DLVSTDATE
                                     and dlvst.DLVSTTIME = dlvstdt.DLVSTTIME 
                            left outer join dlmthd 
                                     on dlvstdt.DLMTHD = dlmthd.DLMTHD 
                            left outer join dlfluidcolr 
                                     on dlvst.FLUIDCOLRBFR = dlfluidcolr.FLUIDCOLR                   
                            where dlvst.HN = :HN_MOM
                            and dlvst.DLVSTDATE = to_date(:DLVSTDATE,'dd/mm/yyyy')
                            and dlvst.DLVSTTIME =  :DLVSTTIME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':DLVSTDATE' => $DLVSTDATE, ':DLVSTTIME' => $DLVSTTIME));

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

    public function data_previousOB() // previous_OB
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            DLVSTDLHIS.HN,
                            DLVSTDLHIS.TIMENO,
                            cast(DLVSTDLHIS.BRTHDATE AS varchar2(2000)) as BRTHDATE,
                            cast(hpt.DSPNAME AS varchar2(2000)) as HPTNAME,
                            cast(DLVSTDLHIS.BRTHDT AS varchar2(2000)) as BRTHDT,
                            DLVSTDLHIS.BW,
                            cast(MALE.NAME AS varchar2(2000)) as MALE,
                            cast(DLVSTDLHIS.HEALTHDT AS varchar2(2000)) as HEALTHDT
                            from DLVSTDLHIS
                            left outer join MALE 
                                   on DLVSTDLHIS.MALE = MALE.MALE
                            left outer join hpt 
                                   on DLVSTDLHIS.BRTHHPT = hpt.HPT
                                   where HN = :HN_MOM
                                   order by DLVSTDLHIS.TIMENO asc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM));

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

    public function data_presentOB() // present_OB (ภาวะแทรกซ้อนขณะตั้งครรภ์)
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $DLVSTDATE = $_POST['DLVSTDATE'];
            $DLVSTTIME = $_POST['DLVSTTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            dlvstbfbrthsign.HN,
                            TO_CHAR(dlvstbfbrthsign.DLVSTDATE, 'MM/DD/YYYY')as DLVSTDATE , 
                            dlvstbfbrthsign.DLVSTTIME, 
                            dlvstbfbrthsign.DIAG,
                            cast(NDLDIAG.NAME AS varchar2(2000)) as DIAGNAME,
                            cast(dlvstbfbrthsign.OTHDETAIL AS varchar2(2000)) as OTHDETAIL
                            from dlvstbfbrthsign
                            left outer join (select DLGRPDIAG, DLDIAG, NAME
                                              from dldiag 
                                              where DLGRPDIAG = 3
                                            ) NDLDIAG 
                                 on dlvstbfbrthsign.DIAG = NDLDIAG.DLDIAG
                                 where dlvstbfbrthsign.HN = :HN_MOM
                                 and dlvstbfbrthsign.DLVSTDATE = to_date(:DLVSTDATE,'dd/mm/yyyy')
                                 and dlvstbfbrthsign.DLVSTTIME =  :DLVSTTIME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':DLVSTDATE' => $DLVSTDATE, ':DLVSTTIME' => $DLVSTTIME));

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

    public function data_dlvstdtapgar() // ประเมินสภาวะทารก
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $DLVSTDATE = $_POST['DLVSTDATE'];
            $DLVSTTIME = $_POST['DLVSTTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select dlvstdtapgar.HN , 
                        dlvstdtapgar.DLVSTDATE,
                        dlvstdtapgar.DLVSTTIME,
                        dlvstdtapgar.MINUTE,
                        dlvstdtapgar.TOTAL
                        from dlvstdtapgar 
                        where dlvstdtapgar.hn = :HN_MOM
                        and dlvstdtapgar.DLVSTDATE = to_date(:DLVSTDATE,'dd/mm/yyyy')
                        and dlvstdtapgar.DLVSTTIME = :DLVSTTIME
                        order by dlvstdtapgar.MINUTE asc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':DLVSTDATE' => $DLVSTDATE, ':DLVSTTIME' => $DLVSTTIME));

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

    public function data_labmom2() // บันทึกการคลอด ข้อมูล lab แม่ BNZTHN
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $HN_MOM = $_POST['HN_MOM'];
            $DLVSTDATE = $_POST['DLVSTDATE'];
            $DLVSTTIME = $_POST['DLVSTTIME'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select dlvstext.HN,
                            TO_CHAR(dlvstext.DLVSTDATE, 'MM/DD/YYYY')as DLVSTDATE,
                           dlvstext.DLVSTTIME,
                            dlvstext.CHILDLIVE as LMOM,
                            dlvstext.BNZTHN,
                            TO_CHAR(dlvstext.STBNZTHN, 'MM/DD/YYYY')as STBNZTHN,
                            TO_CHAR(dlvstext.NDBNZTHN, 'MM/DD/YYYY')as NDBNZTHN,
                            TO_CHAR(dlvstext.RDBNZTHN, 'MM/DD/YYYY')as RDBNZTHN,
                            dlvstext.TREATOTH
                            from dlvstext 
                            where hn = :HN_MOM
                            and DLVSTDATE = to_date(:DLVSTDATE,'DD/MM/YYYY')
                            and DLVSTTIME =  :DLVSTTIME";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HN_MOM' => $HN_MOM, ':DLVSTDATE' => $DLVSTDATE, ':DLVSTTIME' => $DLVSTTIME));

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
}
