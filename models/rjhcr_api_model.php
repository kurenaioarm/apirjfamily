<?php
class rjhcr_api_model extends Model
{
    public function checkup_queue()
    {
        $objCheck = $this->checkToken();
//        print_r($objCheck->check_result );
        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];
            $LRFRLCT = $_POST['LRFRLCT'];
            if($_POST['HN_ID'] == ""){
                $hn_id = "";
            }else{
                $hn_id = $_POST['HN_ID'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ovst.hn, cast(pt.dspname AS varchar2(2000)) as name ,
                            to_char(ovst.vstdate,'fmDD Month YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as tdate ,
                            to_char(ovst.vstdate, 'DDMMYYYY') as edate, dtoage(pt.brthdate,sysdate,5) as age, 
                            cast( male.name AS varchar2(2000))  as sex, ovst.weight, ovst.height, ovst.bt, ovst.rr, ovst.pr,
                            case when vst_press.hbpn is not null then vst_press.hbpn||'/'||vst_press.lbpn end as H_LBP,
                            ovst.vsttime, ovst.cliniclct, 
                            cast( lct.dspname AS varchar2(2000)) as cliniclctname --lab.status
                            from rjvt.ovst
                            right outer join (select distinct(lvst.hn), lvst.lvstdate, lvst.lrfrlct --lvst.lvstst,lvst.ln--, lvstexm.labexm, labexm.name, lvstexm.result
                                                    /*case when lvst.lvstst in (15,21) then 'Complete'
                                                         else 'NotComplete' end status*/
                                              from rjvt.lvst
                                              left outer join rjvt.lvstexm
                                                   on lvst.hn = lvstexm.hn
                                                   and lvst.lvstdate = lvstexm.lvstdate
                                                   and lvst.lvsttime = lvstexm.lvsttime
                                                   and lvst.ln = lvstexm.ln

                                              left outer join rjvt.labexm
                                                   on lvstexm.labexm = labexm.labexm

                                              where lvst.lvstdate between to_date(:SDATE,'ddmmyyyy')
                                              and to_date(:EDATE,'ddmmyyyy')
                                              and lvst.lvstst != 99
                                              and lvst.lrfrlct = :LRFRLCT
                                               and lvstexm.labexm in (3075,3076,3073,3080,5047,5048,5039,5040,1069,1070,1071,1160,1072,1083,1084,1080,1081,1082,1085,1086,5060,5058,
                                              8012,8013,8014,2051,2053,2050,2054,2052)
                                              ) lab
                                              --and lvstexm.labexm in (3108,3073,3079,3075,3076,3077,3078,3079,3080,3081,3019,3004,3005,3023,3006,3024,3007,3025,3008,3026,3009,3027,3083,3084,3085,3086,3082,
                                              --1069,1070,1071,1072,1075,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,
                                              --5053,5041,5042,5043,5044,5048,5047,5049,5051,5050,5046,5045,5052,5039,5040,5033,5092,
                                              --5055,5054,5056,5058,
                                              --8012,8013,8014,
                                              --2050,2051,2052,2053,2054)
                                              --) lab
                                on ovst.hn = lab.hn
                                and ovst.vstdate = lab.lvstdate
                                and ovst.cliniclct = lab.lrfrlct

                            left outer join (select ovstpress.hn, ovstpress.vstdate, ovstpress.vsttime, max_time.mx_time, ovstpress.vn, ovstpress.lbpn, ovstpress.hbpn
                                              from rjvt.ovstpress
                                              right outer join ( select hn, vstdate, vsttime, max(prtime) mx_time, vn
                                                                  from rjvt.ovstpress
                                                                  where vstdate between to_date(:SDATE,'ddmmyyyy')
                                                                  and to_date(:EDATE,'ddmmyyyy')
                                                                  --and hn = 64015984
                                                                  group by hn, vstdate, vsttime, vn
                                                              ) max_time
                                                 on ovstpress.hn = max_time.hn
                                                 and ovstpress.vstdate = max_time.vstdate
                                                 and ovstpress.vsttime = max_time.vsttime
                                                 and ovstpress.prtime = max_time.mx_time
                                                 and ovstpress.vn = max_time.vn
                                              where ovstpress.vstdate between to_date(:SDATE,'ddmmyyyy')
                                              and to_date(:EDATE,'ddmmyyyy')
                                              --and ovstpress.hn = 64015984
                                              ) vst_press
                                on ovst.hn = vst_press.hn
                                and ovst.vstdate = vst_press.vstdate
                                and ovst.vsttime = vst_press.vsttime
                                and ovst.vn = vst_press.vn

                            left outer join rjvt.lct
                                 on ovst.cliniclct = lct.lct
                                 and lct.canceldate is null
                            ,rjvt.pt
                              left outer join rjvt.male
                              on pt.male = male.male

                            where ovst.hn = pt.hn
                            and pt.canceldate is null
                            and not (pt.fname like '%ทดสอบ%' or pt.lname like '%ทดสอบ%')
                            and ovst.vstdate between to_date(:SDATE,'ddmmyyyy')
                            and to_date(:EDATE,'ddmmyyyy')
                            and ovst.cliniclct = :LRFRLCT
                            and ovst.hn LIKE '%$hn_id%'
                            and ovst.canceldate is null
                            order by ovst.vstdate desc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':SDATE' => $sdate,':EDATE' => $edate,':LRFRLCT' => $LRFRLCT));

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


    public function checkup_labgroup()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];
            $LRFRLCT = $_POST['LRFRLCT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ovst.hn, cast(pt.dspname AS varchar2(2000)) as name ,
                            to_char(ovst.vstdate, 'DDMMYYYY') as edate, dtoage(pt.brthdate,sysdate,5) as age,
                            ovst.vsttime, ovst.cliniclct, lab.status,labgrp.name as labname
                            from rjvt.ovst
                            right outer join (select distinct(lvst.hn), lvst.lvstdate, lvst.lrfrlct,lvst.ln,lvst.labgrp, --lvst.lvstst,lvst.ln--, lvstexm.labexm, labexm.name, lvstexm.result
                                                    case when lvst.lvstst in (15,21) then 'Complete'
                                                         else 'NotComplete' end status
                                              from rjvt.lvst
                                              left outer join rjvt.lvstexm
                                                   on lvst.hn = lvstexm.hn
                                                   and lvst.lvstdate = lvstexm.lvstdate
                                                   and lvst.lvsttime = lvstexm.lvsttime
                                                   and lvst.ln = lvstexm.ln

                                              left outer join rjvt.labexm
                                                   on lvstexm.labexm = labexm.labexm

                                              where lvst.lvstdate between to_date(:SDATE,'ddmmyyyy')
                                              and to_date(:EDATE,'ddmmyyyy')
                                              and lvst.lvstst != 99
                                              and lvst.lrfrlct = :LRFRLCT
                                              and lvstexm.labexm in (3075,3076,3073,3080,5047,5048,5039,5040,1069,1070,1071,1160,1072,1083,1084,1080,1081,1082,1085,1086,5060,5058,
                                              8012,8013,8014,2051,2053,2050,2054,2052)
                                              ) lab
                                              --and lvstexm.labexm in (3108,3073,3079,3075,3076,3077,3078,3079,3080,3081,3019,3004,3005,3023,3006,3024,3007,3025,3008,3026,3009,3027,3083,3084,3085,3086,3082,
                                              --1069,1070,1071,1072,1075,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,
                                              --5053,5041,5042,5043,5044,5048,5047,5049,5051,5050,5046,5045,5052,5039,5040,5033,5092,
                                              --5055,5054,5056,5058,
                                              --8012,8013,8014,
                                              --2050,2051,2052,2053,2054)
                                              --) lab
                                on ovst.hn = lab.hn
                                and ovst.vstdate = lab.lvstdate
                                and ovst.cliniclct = lab.lrfrlct

                            left outer join rjvt.labgrp
                            on lab.labgrp = labgrp.labgrp

                            left outer join (select ovstpress.hn, ovstpress.vstdate, ovstpress.vsttime, max_time.mx_time, ovstpress.vn, ovstpress.lbpn, ovstpress.hbpn
                                              from rjvt.ovstpress
                                              right outer join ( select hn, vstdate, vsttime, max(prtime) mx_time, vn
                                                                  from rjvt.ovstpress
                                                                  where vstdate between to_date(:SDATE,'ddmmyyyy')
                                                                  and to_date(:EDATE,'ddmmyyyy')
                                                                  --and hn = 64015984
                                                                  group by hn, vstdate, vsttime, vn
                                                              ) max_time
                                                 on ovstpress.hn = max_time.hn
                                                 and ovstpress.vstdate = max_time.vstdate
                                                 and ovstpress.vsttime = max_time.vsttime
                                                 and ovstpress.prtime = max_time.mx_time
                                                 and ovstpress.vn = max_time.vn
                                              where ovstpress.vstdate between to_date(:SDATE,'ddmmyyyy')
                                              and to_date(:EDATE,'ddmmyyyy')
                                              --and ovstpress.hn = 64015984
                                              ) vst_press
                                on ovst.hn = vst_press.hn
                                and ovst.vstdate = vst_press.vstdate
                                and ovst.vsttime = vst_press.vsttime
                                and ovst.vn = vst_press.vn

                            left outer join rjvt.lct
                                 on ovst.cliniclct = lct.lct
                                 and lct.canceldate is null
                            ,rjvt.pt
                              left outer join rjvt.male
                              on pt.male = male.male

                            where ovst.hn = pt.hn
                            and pt.canceldate is null
                            and not (pt.fname like '%ทดสอบ%' or pt.lname like '%ทดสอบ%')
                            and ovst.vstdate between to_date(:SDATE,'ddmmyyyy')
                            and to_date(:EDATE,'ddmmyyyy')
                            and ovst.cliniclct = :LRFRLCT
                            and ovst.canceldate is null
                            order by ovst.vstdate desc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':SDATE' => $sdate,':EDATE' => $edate,':LRFRLCT' => $LRFRLCT));

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


    public function checkup_hnall(){
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $hn_id = $_POST['HN_ID'];
            $LRFRLCT = $_POST['LRFRLCT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ovst.hn, cast(pt.dspname AS varchar2(2000)) as name , to_char(ovst.vstdate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as tdate ,
                            to_char(ovst.vstdate, 'DDMMYYYY') as edate, dtoage(pt.brthdate,sysdate,5) as age, male.name as sex, ovst.weight, ovst.height, ovst.bt, ovst.rr, ovst.pr,
                            case when vst_press.hbpn is not null then vst_press.hbpn||'/'||vst_press.lbpn end as H_LBP,
                              ovst.vsttime, ovst.cliniclct, lct.dspname as cliniclctname--, lab.status
                            from rjvt.ovst
                            right outer join (select distinct(lvst.hn), lvst.lvstdate, lvst.lrfrlct--, --lvst.lvstst,lvst.ln--, lvstexm.labexm, labexm.name, lvstexm.result 
                                                    /*case when lvst.lvstst in (15,21) then 'Complete' 
                                                         else 'NotComplete' end status*/
                                              from rjvt.lvst 
                                              left outer join rjvt.lvstexm
                                                   on lvst.hn = lvstexm.hn
                                                   and lvst.lvstdate = lvstexm.lvstdate
                                                   and lvst.lvsttime = lvstexm.lvsttime
                                                   and lvst.ln = lvstexm.ln
                                                        
                                              left outer join rjvt.labexm
                                                   on lvstexm.labexm = labexm.labexm
                                                        
                                              where 
                                             --lvst.lvstdate between to_date(:SDATE,'ddmmyyyy')
                                              --and to_date(:EDATE,'ddmmyyyy')
                                              lvst.lvstst != 99
                                              and lvst.lrfrlct = :LRFRLCT
                                              and lvstexm.labexm in (3075,3076,3073,3080,5047,5048,5039,5040,1069,1070,1071,1160,1072,1083,1084,1080,1081,1082,1085,1086,5060,5058,
                                              8012,8013,8014,2051,2053,2050,2054,2052)
                                              ) lab
                                              --and lvstexm.labexm in (3108,3073,3079,3075,3076,3077,3078,3079,3080,3081,3019,3004,3005,3023,3006,3024,3007,3025,3008,3026,3009,3027,3083,3084,3085,3086,3082,
                                              --1069,1070,1071,1072,1075,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,
                                              --5053,5041,5042,5043,5044,5048,5047,5049,5051,5050,5046,5045,5052,5039,5040,5033,5092,
                                              --5055,5054,5056,5058,
                                              --8012,8013,8014,
                                              --2050,2051,2052,2053,2054)
                                              --) lab
                                on ovst.hn = lab.hn
                                and ovst.vstdate = lab.lvstdate
                                and ovst.cliniclct = lab.lrfrlct 
                            
                            left outer join (select ovstpress.hn, ovstpress.vstdate, ovstpress.vsttime, max_time.mx_time, ovstpress.vn, ovstpress.lbpn, ovstpress.hbpn
                                              from rjvt.ovstpress
                                              right outer join ( select hn, vstdate, vsttime, max(prtime) mx_time, vn
                                                                  from rjvt.ovstpress
                                                                  --where vstdate between to_date(:SDATE,'ddmmyyyy')
                                                                  --and to_date(:EDATE,'ddmmyyyy')
                                                                  --and hn = 64015984
                                                                  group by hn, vstdate, vsttime, vn
                                                              ) max_time
                                                 on ovstpress.hn = max_time.hn
                                                 and ovstpress.vstdate = max_time.vstdate
                                                 and ovstpress.vsttime = max_time.vsttime
                                                 and ovstpress.prtime = max_time.mx_time
                                                 and ovstpress.vn = max_time.vn
                                              --where ovstpress.vstdate between to_date(:SDATE,'ddmmyyyy')
                                              --and to_date(:EDATE,'ddmmyyyy')
                                              --and ovstpress.hn = 64015984
                                              ) vst_press
                                on ovst.hn = vst_press.hn
                                and ovst.vstdate = vst_press.vstdate
                                and ovst.vsttime = vst_press.vsttime
                                and ovst.vn = vst_press.vn
                                                        
                            left outer join rjvt.lct
                                 on ovst.cliniclct = lct.lct
                                 and lct.canceldate is null
                            ,rjvt.pt  
                              left outer join rjvt.male
                              on pt.male = male.male
                                                                      
                            where ovst.hn = pt.hn
                            and pt.canceldate is null
                            and not (pt.fname like '%ทดสอบ%' or pt.lname like '%ทดสอบ%')
                            --and ovst.vstdate between to_date(:SDATE,'ddmmyyyy')
                            --and to_date(:EDATE,'ddmmyyyy')
                            and ovst.cliniclct = :LRFRLCT
                            and ovst.hn = :HNID
                            and ovst.canceldate is null
                            order by ovst.vstdate desc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HNID' => $hn_id,':LRFRLCT' => $LRFRLCT));

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


    public function checkup_hnalllabgroup(){
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $hn_id = $_POST['HN_ID'];
            $LRFRLCT = $_POST['LRFRLCT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select ovst.hn, cast(pt.dspname AS varchar2(2000)) as name ,
                            to_char(ovst.vstdate, 'DDMMYYYY') as edate, dtoage(pt.brthdate,sysdate,5) as age,
                            ovst.vsttime, ovst.cliniclct, lab.status,labgrp.name as labname
                            from rjvt.ovst
                            right outer join (select distinct(lvst.hn), lvst.lvstdate, lvst.lrfrlct,lvst.ln,lvst.labgrp, --lvst.lvstst,lvst.ln--, lvstexm.labexm, labexm.name, lvstexm.result 
                                                    case when lvst.lvstst in (15,21) then 'Complete' 
                                                         else 'NotComplete' end status
                                              from rjvt.lvst 
                                              left outer join rjvt.lvstexm
                                                   on lvst.hn = lvstexm.hn
                                                   and lvst.lvstdate = lvstexm.lvstdate
                                                   and lvst.lvsttime = lvstexm.lvsttime
                                                   and lvst.ln = lvstexm.ln
                                                        
                                              left outer join rjvt.labexm
                                                   on lvstexm.labexm = labexm.labexm
                                                        
                                              where 
                                              --lvst.lvstdate between to_date(:SDATE,'ddmmyyyy')
                                              -- and to_date(:EDATE,'ddmmyyyy')
                                              lvst.lvstst != 99
                                              and lvst.lrfrlct = :LRFRLCT
                                              and lvstexm.labexm in (3075,3076,3073,3080,5047,5048,5039,5040,1069,1070,1071,1160,1072,1083,1084,1080,1081,1082,1085,1086,5060,5058,
                                              8012,8013,8014,2051,2053,2050,2054,2052)
                                              ) lab
                                              --and lvstexm.labexm in (3108,3073,3079,3075,3076,3077,3078,3079,3080,3081,3019,3004,3005,3023,3006,3024,3007,3025,3008,3026,3009,3027,3083,3084,3085,3086,3082,
                                              --1069,1070,1071,1072,1075,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,
                                              --5053,5041,5042,5043,5044,5048,5047,5049,5051,5050,5046,5045,5052,5039,5040,5033,5092,
                                              --5055,5054,5056,5058,
                                              --8012,8013,8014,
                                              --2050,2051,2052,2053,2054)
                                              --) lab
                                on ovst.hn = lab.hn
                                and ovst.vstdate = lab.lvstdate
                                and ovst.cliniclct = lab.lrfrlct 
                            
                            left outer join rjvt.labgrp
                            on lab.labgrp = labgrp.labgrp
                            
                            left outer join (select ovstpress.hn, ovstpress.vstdate, ovstpress.vsttime, max_time.mx_time, ovstpress.vn, ovstpress.lbpn, ovstpress.hbpn
                                              from rjvt.ovstpress
                                              right outer join ( select hn, vstdate, vsttime, max(prtime) mx_time, vn
                                                                  from rjvt.ovstpress
                                                                  --where vstdate between to_date(:SDATE,'ddmmyyyy')
                                                                  --and to_date(:EDATE,'ddmmyyyy')
                                                                  --and hn = 64015984
                                                                  group by hn, vstdate, vsttime, vn
                                                              ) max_time
                                                 on ovstpress.hn = max_time.hn
                                                 and ovstpress.vstdate = max_time.vstdate
                                                 and ovstpress.vsttime = max_time.vsttime
                                                 and ovstpress.prtime = max_time.mx_time
                                                 and ovstpress.vn = max_time.vn
                                              --where ovstpress.vstdate between to_date(:SDATE,'ddmmyyyy')
                                              --and to_date(:EDATE,'ddmmyyyy')
                                              --and ovstpress.hn = 64015984
                                              ) vst_press
                                on ovst.hn = vst_press.hn
                                and ovst.vstdate = vst_press.vstdate
                                and ovst.vsttime = vst_press.vsttime
                                and ovst.vn = vst_press.vn
                                                        
                            left outer join rjvt.lct
                                 on ovst.cliniclct = lct.lct
                                 and lct.canceldate is null
                            ,rjvt.pt  
                              left outer join rjvt.male
                              on pt.male = male.male
                                                                      
                            where ovst.hn = pt.hn
                            and pt.canceldate is null
                            and not (pt.fname like '%ทดสอบ%' or pt.lname like '%ทดสอบ%')
                            --and ovst.vstdate between to_date(:SDATE,'ddmmyyyy')
                            --and to_date(:EDATE,'ddmmyyyy')
                            and ovst.cliniclct = :LRFRLCT
                              and ovst.hn = :HNID
                            and ovst.canceldate is null
                            order by ovst.vstdate desc";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':HNID' => $hn_id,':LRFRLCT' => $LRFRLCT));

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


    public function checkup_lab()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $adate = $_POST['ADATE'];
            $hn_id = $_POST['HN_ID'];
            $LRFRLCT = $_POST['LRFRLCT'];

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select lvst.hn, lvst.lvstdate, lvst.lrfrlct, lvst.lvstst,lvst.ln, lvstexm.labexm, labexm.name, lvstexm.result, lvstexm.cnfmstf, staff.DSPNAME
                            from rjvt.lvst 
                            left outer join rjvt.lvstexm
                                 on lvst.hn = lvstexm.hn
                                 and lvst.lvstdate = lvstexm.lvstdate
                                 and lvst.lvsttime = lvstexm.lvsttime
                                 and lvst.ln = lvstexm.ln
                            
                            left outer join rjvt.labexm
                                  on lvstexm.labexm = labexm.labexm
                            left outer join staff
                                  on lvstexm.cnfmstf = staff.STAFF     
                            
                            where lvst.lvstdate = to_date(:ADATE,'ddmmyyyy')
                            and lvst.lvstst != 99
                            and lvst.lrfrlct = :LRFRLCT
                            and lvstexm.labexm in (3108,3073,3079,3075,3076,3077,3078,3079,3080,3081,3019,3004,3005,3023,3006,3024,3007,3025,3008,3026,3009,3027,3083,3084,3085,3086,3082,
                            1069,1070,1071,1072,1075,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1160,
                            5053,5041,5042,5043,5044,5048,5047,5049,5051,5050,5046,5045,5052,5039,5040,5033,5092,
                            5055,5054,5056,5058,5060,
                            8012,8013,8014,
                            2050,2051,2052,2053,2054)
                            and  lvst.hn =:HNID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':ADATE' => $adate,':HNID' => $hn_id,':LRFRLCT' => $LRFRLCT));

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

    public function checkup_lrfrlct()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select 
                            LRFRLCT,
                            cast(NAME AS varchar2(2000)) as NAME,
                            cast(DSPNAME AS varchar2(2000)) as DSPNAME,
                            cast(ABBRNAME AS varchar2(2000)) as ABBRNAME
                             from lrfrlct 
                             where lrfrlct in (302090300)--991009906 991000601 
                             and CANCELDATE is null";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
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
    }

    public function stack_download()
    {
        $tdate = $_POST['TDATE'];
        $astaff = $_POST['ASTAFF'];
        $LRFRLCT = $_POST['LRFRLCT'];

        $responseArray = array();
        $responseArray['json_result'] = true;

        $Stack_Step1  = "select * from webintra.log_hcreport 
                                    where LOG_DATE = to_char(to_date(:TDATE,'dd/mm/yyyy'))
                                    and LOG_STAFF = :ASTAFF
                                    and LOG_LRFRLCT = :LRFRLCT ";
        $objQuery_Step1 = $this->oracle_webintra->prepare($Stack_Step1);
        $objQuery_Step1->setFetchMode(PDO::FETCH_ASSOC);
        $objQuery_Step1->execute(array(':TDATE' => $tdate,':ASTAFF' => $astaff,':LRFRLCT' => $LRFRLCT));
        $row_objQuery_Step1 = $objQuery_Step1->fetch(PDO::FETCH_BOTH);


        if($row_objQuery_Step1['LOG_STACK']  !=  null){
            $stack_number = $row_objQuery_Step1['LOG_STACK']+1;
            $strSql_Update = "UPDATE webintra.log_hcreport SET LOG_STACK = ".$stack_number."
                                          where LOG_DATE = to_char(to_date(:TDATE,'dd/mm/yyyy')) 
                                          and LOG_LRFRLCT = :LRFRLCT
                                          and LOG_STAFF = :ASTAFF" ;
            ////connect oracle แสดงข้อมูล
            $objQuery_Update = $this->oracle_webintra->prepare($strSql_Update);
            $objQuery_Update->setFetchMode(PDO::FETCH_ASSOC);
            $objQuery_Update->execute(array(':TDATE' => $tdate,':ASTAFF' => $astaff,':LRFRLCT' => $LRFRLCT));
        }else{
            $Stack_Step2  = "select MAX(LOG_ID) as LOG_ID from webintra.log_hcreport";
            $objQuery_Step2 = $this->oracle_webintra->prepare($Stack_Step2);
            $objQuery_Step2->setFetchMode(PDO::FETCH_ASSOC);
            $objQuery_Step2->execute();
            $row_objQuery_Step2 = $objQuery_Step2->fetch(PDO::FETCH_BOTH);

            $log_id = $row_objQuery_Step2['LOG_ID']+1;
            $strSql_Insert = "INSERT INTO webintra.log_hcreport (LOG_ID, LOG_DATE, LOG_STACK , LOG_STAFF , LOG_LRFRLCT)
                                              VALUES (".$log_id." ,  to_date(:TDATE,'dd/mm/yyyy'), 1 , :ASTAFF , :LRFRLCT)";
            ////connect oracle แสดงข้อมูล
            $objQuery_Insert = $this->oracle_webintra->prepare($strSql_Insert);
            $objQuery_Insert->setFetchMode(PDO::FETCH_ASSOC);
            $objQuery_Insert->execute(array(':TDATE' => $tdate,':ASTAFF' => $astaff,':LRFRLCT' => $LRFRLCT));
        }
    }
}