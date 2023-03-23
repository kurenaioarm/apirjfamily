<?php

class er_esi_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function er_esi()
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $sdate = $_POST['SDATE'];
            $edate = $_POST['EDATE'];
            $emrgncy = $_POST['EMRGNCY'];
            if($emrgncy == 0){
                $notequal = ""; //notequal
                $emrgncystatus = "";
            }else if($emrgncy == 1){
                $notequal = 'and prscemrgncy.emrgncy = ovst.emrgncy'; //notequal
                $emrgncystatus = "";
            }else if($emrgncy == 2){
                $notequal = 'and prscemrgncy.emrgncy != ovst.emrgncy'; //notequal
                $emrgncystatus = "";
            }else if($emrgncy == 3){
                $notequal = 'and prscemrgncy.emrgncy != ovst.emrgncy'; //notequal
                $emrgncystatus = 'and prscemrgncy.emrgncy > ovst.emrgncy'; //'OVER'
            }else if($emrgncy == 4){
                $notequal = 'and prscemrgncy.emrgncy != ovst.emrgncy'; //notequal
                $emrgncystatus = "and prscemrgncy.emrgncy < ovst.emrgncy"; //'UNDER'
            }

             $responseArray = array();
             $responseArray['json_result'] = true;
            //cast(pt.dspname AS varchar2(128) ) as ptnm,
            $strSql = "select substr(ovst.hn,3,6)||'-'||SUBSTR(ovst.hn,1,2) as HN, ovst.vn 
                        ,to_char(ovst.vstdate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as ddate      
                        ,to_char(ovst.vsttime,'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as dtime
                        
                        , cast(pt.dspname as varchar2(128)) as PNAME
                        ,to_char(ovst.itvdate,'fmDD MON YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI') as hdate      
                        , to_char(ovst.itvtime,'fm00G00G00','NLS_NUMERIC_CHARACTERS=''.:''') as htime 
                        , cast(ovstost.name as varchar2(128)) as FSTATUS
                        , case ovst.trauma when 1 then 'trauma' when 2 then 'non trauma' else '' end as trauma
                        ,xemrgncy.name as ESI_DOC
                        ,dct.dspname as MEDICO
                        , emrgncy.name as ESI
                        , ovst.ccp as chiefcomplaint
                        ,case when (prscemrgncy.emrgncy > ovst.emrgncy) then 'OVER'
                               when (prscemrgncy.emrgncy < ovst.emrgncy) then 'UNDER'
                               when (prscemrgncy.emrgncy = ovst.emrgncy) then 'SAME' end st
                        ,ovstpress.hbpn ,ovstpress.lbpn 
                        ,ovst.bt ,ovst.pr ,ovst.rr
                        
                        from (select ovst.hn,min(rjvt.todatetime(ovst.vstdate,ovst.vsttime)) as date_time,ovst.vn 
                              from rjvt.ovst 
                              left outer join rjvt.prscemrgncy
                                   on ovst.hn = prscemrgncy.hn 
                                   and ovst.vstdate = prscemrgncy.vstdate 
                                   and ovst.vsttime = prscemrgncy.vsttime
                                   and ovst.vn = prscemrgncy.vn 
                                   and ovst.fn = prscemrgncy.fn
                              where ovst.vstdate >=  to_date(:SDATE,'dd/mm/yyyy')
                              and ovst.vstdate <= to_date(:EDATE,'dd/mm/yyyy')
                              and ovst.canceldate is null
                              and ovst.cliniclct in ( select lct from rjvt.lct where canceldate is null and lower(varcode) ='ems' )
                              --and ovst.hn = 65038225
                              and prscemrgncy.emrgncy is not null
                              group by ovst.hn ,ovst.vn  ) max_vst
                        left outer join rjvt.ovst
                          on max_vst.hn = ovst.hn
                          and max_vst.date_time = rjvt.todatetime(ovst.vstdate ,ovst.vsttime)
                          and max_vst.vn = ovst.vn
                        left outer join rjvt.ovstost on ovst.ovstost = ovstost.ovstost    
                        left outer join rjvt.emrgncy on ovst.emrgncy = emrgncy.emrgncy
                        left outer join rjvt.ovstpress on ovst.hn = ovstpress.hn
                             and ovst.vstdate = ovstpress.vstdate
                             and ovst.vsttime = ovstpress.vsttime
                             and ovst.vn = ovstpress.vn
                        
                        /*left outer join rjvt.incpt on ovst.hn = incpt.hn 
                             and ovst.vstdate = incpt.incdate 
                             and ovst.vsttime = incpt.inctime and incpt.income = 999*/
                             
                        left outer join rjvt.prscemrgncy on ovst.hn = prscemrgncy.hn 
                             and ovst.vstdate = prscemrgncy.vstdate 
                             and ovst.vsttime = prscemrgncy.vsttime
                             and ovst.vn = prscemrgncy.vn 
                             and ovst.fn = prscemrgncy.fn
                        left outer join rjvt.emrgncy xemrgncy on prscemrgncy.emrgncy = xemrgncy.emrgncy
                        left outer join rjvt.dct on ovst.dct =  dct.dct
                        ,rjvt.pt 
                                      
                        where ovst.hn = pt.hn
                        and ovst.vstdate >=  to_date(:SDATE,'dd/mm/yyyy')
                        and ovst.vstdate <= to_date(:EDATE,'dd/mm/yyyy')
                        and ovst.canceldate is null
                        and not ovst.ovstost in ('-11','-12')
                        and ovst.cliniclct in ( select lct from rjvt.lct where canceldate is null and lower(varcode) ='ems' )
                        -- and ovst.hn =65000923
                        and ovstpress.item = 1
                         $notequal
                         $emrgncystatus
                        --and prscemrgncy.emrgncy > ovst.emrgncy --'OVER'
                        --and prscemrgncy.emrgncy < ovst.emrgncy --'UNDER'
                                
                        order by ovst.vstdate ,ovst.vsttime";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_his->prepare($strSql);
            //$staff_id = '3039';
            //$objQuery->bindParam(":staff_id", $staff_id, PDO::PARAM_STR);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':SDATE' => $sdate,':EDATE' => $edate));

            if ($result) {
                $dataset = $objQuery->fetchAll();
//                $dataset = array();
//                while ($row = $objQuery->fetch(PDO::FETCH_ASSOC)){
//                    $dataset[] = $row;
//                }
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
