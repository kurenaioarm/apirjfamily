<?php

class donate_api_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function donate_admin()
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['ADMINTYPE_ID'] == "ADMIN_ALL" && $_POST['ADMIN_ID'] == ""){
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select DONATE_Admin. *  from webintra.DONATE_Admin";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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

                $strSql = "INSERT INTO webintra.DONATE_Admin (ADMIN_ID, ADMIN_NAME, ADMIN_AGENCY_ID, TYPE_ID)
                                  VALUES (:ADMIN_IDCARD , :ADMIN_NAME ,:ADMIN_AGENCY_ID , :TYPE_ID)";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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

                $strSql = "DELETE FROM webintra.DONATE_Admin where ADMIN_ID = :ADMINID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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

                $strSql = "select DONATE_Admin. *  from webintra.DONATE_Admin where ADMIN_ID = :ADMINID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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

    public function maxdonate_id()// หาค่า max รหัสใบรับบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['MAXDONATE_ID'] == "MAXID"){

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select MAX(DONATE_ID) as MAXDONATE_ID from webintra.DONATE_ITEM";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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
            }else{
                $maxdonate_id = $_POST['MAXDONATE_ID'];
                print_r($maxdonate_id);
            }
        }
    }

    public function donate_item()// สิ่งของบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['ITEMTYPE_ID'] == "ITEMTYPE_ALL" && $_POST['ITEM_ID'] == ""){
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select ITEMTYPE_ID, cast(ITEM_NAME AS varchar2(2000)) as  ITEM_NAME from webintra.DONATE_ITEMTYPE
                                where canceldate is null";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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
            }else if ($_POST['ITEM_ID'] == "ITEM_ALL" && $_POST['ITEMTYPE_ID'] == ""){
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select ITEM_ID, ITEMTYPE_ID, cast(ITEM_NAME AS varchar2(2000)) as ITEM_NAME from webintra.DONATE_ITEMMASTER 
                                where canceldate is null";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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
            }else if ($_POST['ITEM_ID'] != "" && $_POST['ITEMTYPE_ID'] == ""){
                $item_id = $_POST['ITEM_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select DONATE_ITEMMASTER.ITEMTYPE_ID, cast(DONATE_ITEMTYPE.ITEM_NAME AS varchar2(2000)) as TYPE_NAME, 
                                DONATE_ITEMMASTER.ITEM_ID, cast(DONATE_ITEMMASTER.ITEM_NAME AS varchar2(2000)) as ITEM_NAME 
                                from webintra.DONATE_ITEMMASTER 
                                left outer join webintra.DONATE_ITEMTYPE 
                                     on DONATE_ITEMMASTER.ITEMTYPE_ID = DONATE_ITEMTYPE.ITEMTYPE_ID
                                where DONATE_ITEMMASTER.ITEM_ID = :ITEMID
                                and DONATE_ITEMMASTER.canceldate is null";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ITEMID' => $item_id));

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
            }else if ($_POST['ITEM_ID'] == "" && $_POST['ITEMTYPE_ID'] != ""){
                $itemtype_id = $_POST['ITEMTYPE_ID'];

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select ITEM_ID, ITEMTYPE_ID, cast(ITEM_NAME AS varchar2(2000)) as ITEM_NAME from webintra.DONATE_ITEMMASTER 
                                where ITEMTYPE_ID = :ITEMTYPEID
                                and canceldate is null ";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':ITEMTYPEID' => $itemtype_id));

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

    public function donate_cu()// หน่วยนับ
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if($_POST['CU_ID'] == "CU_ALL") {
                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select CU_ID ,cast(CU_NAME AS varchar2(2000)) as CU_NAME from webintra.DONATE_CU
                              where canceldate is null";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
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
            }
        }
    }

    public function donate_insert()// บันทึก ใบรับบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค
            $NAME_TYPE = $_POST['NAME_TYPE'];//1 = บุคคลธรรมดา / 2 = บริษัท,นิติบุคคล,มูลนิธิ
            $PNAME = $_POST['PNAME'];//คำนำหน้า
            $NAME = $_POST['NAME'];//ชื่อ
            $LNAME = $_POST['LNAME'];//นามสกุล
            $TAXPAYER_NUMBER = $_POST['TAXPAYER_NUMBER'];//เลขผู้เสียภาษี
            $DONATE_DATE = $_POST['DONATE_DATE'];//วันที่รับบริจาค
            $ADDRESS_NO = $_POST['ADDRESS_NO'];//ที่อยู่ เลขที่
            $ALLEY = $_POST['ALLEY'];//ซอย
            $ROAD = $_POST['ROAD'];//ถนน
            $TAMBON = $_POST['TAMBON'];//ตำบล/แขวง
            $DISTRICT = $_POST['DISTRICT'];//อำเภอ/เขต
            $PROVINCE = $_POST['PROVINCE'];//จังหวัด
            $ZIP_CODE = $_POST['ZIP_CODE'];//รหัสไปรษณีย ์
            $TELEPHONE = $_POST['TELEPHONE'];//เบอร์โทรศัพท์
            $DONATE_NOTE = $_POST['DONATE_NOTE'];//หมายเหตุ บริจาคเนื่องในโอกาส
            $LETTER_TYPE = $_POST['LETTER_TYPE'];//1 = ต้องการหนังสือตอบขอบคุณ / 2 = ไม่ต้องการหนังสือตอบขอบคุณ
            $REGISTER_TYPE  = $_POST['REGISTER_TYPE'];//1 = ลงทะเบียนครุภัณฑ์ / 2 = ไม่ลงทะเบียนครุภัณฑ์
            $CANCELDATE = $_POST['CANCELDATE'];//วันที่ยกเลิก
            $FIRSTSTAFF = $_POST['FIRSTSTAFF'];//ผู้บันทึกข้อมูล
            $CANCELSTAFF = $_POST['CANCELSTAFF'];//ผู้ยกเลิกข้อมูล


            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "INSERT INTO webintra.DONATE_ITEM (DONATE_ID, NAME_TYPE, PNAME, NAME, LNAME, TAXPAYER_NUMBER, 
                                DONATE_DATE, ADDRESS_NO, ALLEY, ROAD, TAMBON, DISTRICT, PROVINCE, ZIP_CODE, TELEPHONE, 
                                DONATE_NOTE, LETTER_TYPE, REGISTER_TYPE, CANCELDATE, FIRSTSTAFF, CANCELSTAFF )
                                VALUES (:DONATE_ID , :NAME_TYPE , :PNAME , :NAME , :LNAME , 
                                                  :TAXPAYER_NUMBER , to_date(:DONATE_DATE,'dd/mm/yyyy hh24:mi:ss') , :ADDRESS_NO,
                                                  :ALLEY , :ROAD , :TAMBON , :DISTRICT , :PROVINCE , :ZIP_CODE , :TELEPHONE,
                                                  :DONATE_NOTE , :LETTER_TYPE, :REGISTER_TYPE , to_date(:CANCELDATE,'dd/mm/yyyy hh24:mi:ss') , :FIRSTSTAFF , :CANCELSTAFF)";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID,':NAME_TYPE' => $NAME_TYPE,':PNAME' => $PNAME,':NAME' => $NAME,':LNAME' => $LNAME,
                ':TAXPAYER_NUMBER' => $TAXPAYER_NUMBER,':DONATE_DATE' => $DONATE_DATE,':ADDRESS_NO' => $ADDRESS_NO,
                ':ALLEY' => $ALLEY,':ROAD' => $ROAD,':TAMBON' => $TAMBON,':DISTRICT' => $DISTRICT,':PROVINCE' => $PROVINCE,':ZIP_CODE' => $ZIP_CODE,':TELEPHONE' => $TELEPHONE,
                ':DONATE_NOTE' => $DONATE_NOTE,':LETTER_TYPE' => $LETTER_TYPE,':REGISTER_TYPE' => $REGISTER_TYPE ,':CANCELDATE' => $CANCELDATE,':FIRSTSTAFF' => $FIRSTSTAFF,':CANCELSTAFF' => $CANCELSTAFF));


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

    public function donate_update()// UPDATE ใบรับบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค
            $NAME_TYPE = $_POST['NAME_TYPE'];//1 = บุคคลธรรมดา / 2 = บริษัท,นิติบุคคล,มูลนิธิ
            $PNAME = $_POST['PNAME'];//คำนำหน้า
            $NAME = $_POST['NAME'];//ชื่อ
            $LNAME = $_POST['LNAME'];//นามสกุล
            $TAXPAYER_NUMBER = $_POST['TAXPAYER_NUMBER'];//เลขผู้เสียภาษี
            $ADDRESS_NO = $_POST['ADDRESS_NO'];//ที่อยู่ เลขที่
            $ALLEY = $_POST['ALLEY'];//ซอย
            $ROAD = $_POST['ROAD'];//ถนน
            $TAMBON = $_POST['TAMBON'];//ตำบล/แขวง
            $DISTRICT = $_POST['DISTRICT'];//อำเภอ/เขต
            $PROVINCE = $_POST['PROVINCE'];//จังหวัด
            $ZIP_CODE = $_POST['ZIP_CODE'];//รหัสไปรษณีย ์
            $TELEPHONE = $_POST['TELEPHONE'];//เบอร์โทรศัพท์
            $DONATE_NOTE = $_POST['DONATE_NOTE'];//หมายเหตุ บริจาคเนื่องในโอกาส
            $LETTER_TYPE = $_POST['LETTER_TYPE'];//1 = ต้องการหนังสือตอบขอบคุณ / 2 = ไม่ต้องการหนังสือตอบขอบคุณ
            $REGISTER_TYPE  = $_POST['REGISTER_TYPE'];//1 = ลงทะเบียนครุภัณฑ์ / 2 = ไม่ลงทะเบียนครุภัณฑ์
            $FIRSTSTAFF = $_POST['FIRSTSTAFF'];//ผู้บันทึกข้อมูล


            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "UPDATE webintra.DONATE_ITEM SET NAME_TYPE = :NAME_TYPE, PNAME = :PNAME, NAME = :NAME, LNAME = :LNAME,
                                TAXPAYER_NUMBER = :TAXPAYER_NUMBER,  ADDRESS_NO = :ADDRESS_NO, ALLEY = :ALLEY, ROAD = :ROAD, 
                                TAMBON = :TAMBON, DISTRICT = :DISTRICT, PROVINCE = :PROVINCE, ZIP_CODE = :ZIP_CODE, TELEPHONE = :TELEPHONE, 
                                DONATE_NOTE = :DONATE_NOTE, LETTER_TYPE = :LETTER_TYPE, REGISTER_TYPE = :REGISTER_TYPE, FIRSTSTAFF = :FIRSTSTAFF
                                WHERE DONATE_ID = :DONATE_ID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID,':NAME_TYPE' => $NAME_TYPE,':PNAME' => $PNAME,':NAME' => $NAME,':LNAME' => $LNAME,
                ':TAXPAYER_NUMBER' => $TAXPAYER_NUMBER,':ADDRESS_NO' => $ADDRESS_NO,
                ':ALLEY' => $ALLEY,':ROAD' => $ROAD,':TAMBON' => $TAMBON,':DISTRICT' => $DISTRICT,':PROVINCE' => $PROVINCE,':ZIP_CODE' => $ZIP_CODE,':TELEPHONE' => $TELEPHONE,
                ':DONATE_NOTE' => $DONATE_NOTE,':LETTER_TYPE' => $LETTER_TYPE,':REGISTER_TYPE' => $REGISTER_TYPE ,':FIRSTSTAFF' => $FIRSTSTAFF));


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

    public function item_insert()// บันทึก ของรับบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            $ITEM_ID = $_POST['ITEM_ID'];//รหัสของบริจาค
            $ITEMTYPE_ID = $_POST['ITEMTYPE_ID'];//รหัสประเภทของบริจาค
            $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค
            $DONATE_DATE = $_POST['DONATE_DATE'];//วันที่รับบริจาค
            $QUANTITY = $_POST['QUANTITY'];//จำนวน
            $COUNTING_UNIT = $_POST['COUNTING_UNIT'];//หน่วยนับ
            $PRICE = $_POST['PRICE'];//มูลค่า(ต่อหน่วย)
            $ITEM_NAME = $_POST['ITEM_NAME'];//ระบุชื่อ กรณีอื่นๆ
            $ORDER_NUM = $_POST['ORDER_NUM'];//ลำดับของ ITEM

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "INSERT INTO webintra.DONATE_ITEMDT (ITEM_ID, ITEMTYPE_ID, DONATE_ID, DONATE_DATE, QUANTITY, COUNTING_UNIT, PRICE , ITEM_NAME , ORDER_NUM)
                              VALUES (:ITEM_ID , :ITEMTYPE_ID , :DONATE_ID , to_date(:DONATE_DATE,'dd/mm/yyyy hh24:mi:ss') , :QUANTITY , :COUNTING_UNIT , :PRICE , :ITEM_NAME , :ORDER_NUM)";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);
            $result = $objQuery->execute(array(':ITEM_ID' => $ITEM_ID,':ITEMTYPE_ID' => $ITEMTYPE_ID,':DONATE_ID' => $DONATE_ID,':DONATE_DATE' => $DONATE_DATE,':QUANTITY' => $QUANTITY,
                ':COUNTING_UNIT' => $COUNTING_UNIT,':PRICE' => $PRICE,':ITEM_NAME' => $ITEM_NAME,':ORDER_NUM' => $ORDER_NUM));


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

    public function donate_home()// รายการขอรับบริจาค
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            if($_POST['SDATE'] == "" || $_POST['EDATE'] == ""){
                $sdate = date('01/01/Y');
                $edate = date('31/12/Y');
            }else{
                $sdate = $_POST['SDATE'];
                $edate = $_POST['EDATE'];
            }

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select DONATE_ITEM.DONATE_ID, DONATE_ITEM.NAME_TYPE, DONATE_ITEM.PNAME, 
                            cast(DONATE_ITEM.NAME AS varchar2(2000)) as NAME, 
                            cast(DONATE_ITEM.LNAME AS varchar2(2000)) as LNAME, DONATE_ITEM.TAXPAYER_NUMBER, 
                            TO_CHAR(DONATE_ITEM.DONATE_DATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI')as DONATE_DATE, 
                            cast(DONATE_ITEM.ADDRESS_NO AS varchar2(2000)) as ADDRESS_NO, 
                            cast(DONATE_ITEM.ALLEY AS varchar2(2000)) as ALLEY, 
                            cast(DONATE_ITEM.ROAD AS varchar2(2000)) as ROAD, 
                            DONATE_ITEM.TAMBON, cast(TUMBON.NAME AS varchar2(2000)) as TNAME,
                            DONATE_ITEM.DISTRICT, cast(AMPUR.NAME AS varchar2(2000)) as DNAME,
                            DONATE_ITEM.PROVINCE, cast(CHANGWAT.NAME AS varchar2(2000)) as PRNAME,
                            DONATE_ITEM.ZIP_CODE, DONATE_ITEM.TELEPHONE, 
                            cast(DONATE_NOTE AS varchar2(2000)) as DONATE_NOTE, LETTER_TYPE,REGISTER_TYPE, DONATE_ITEM.CANCELDATE, FIRSTSTAFF, CANCELSTAFF  
                            from webintra.DONATE_ITEM 
                                 left outer join webout.tumbon
                                      on DONATE_ITEM.tambon = tumbon.tumbon
                                 left outer join webout.ampur
                                      on DONATE_ITEM.district = ampur.ampur
                                 left outer join webout.changwat
                                      on DONATE_ITEM.PROVINCE = changwat.changwat
                            where DONATE_ITEM.CANCELDATE is null 
                            and trunc(DONATE_DATE) BETWEEN TO_DATE(:SDATE,'dd/mm/yyyy')
                            and TO_DATE(:EDATE,'dd/mm/yyyy')
                            ORDER BY DONATE_ID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
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

    public function donateitem_fpdf()// ข้อมูลใบรับบริจาค
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select DONATE_ID, NAME_TYPE, PNAME, 
                            cast(DONATE_ITEM.NAME AS varchar2(2000)) as NAME, 
                            cast(LNAME AS varchar2(2000)) as LNAME, TAXPAYER_NUMBER, 
                            TO_CHAR(DONATE_DATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI')as DONATE_DATE, 
                            cast(ADDRESS_NO AS varchar2(2000)) as ADDRESS_NO, 
                            cast(ALLEY AS varchar2(2000)) as ALLEY, 
                            cast(ROAD AS varchar2(2000)) as ROAD, 
                             TAMBON, cast(TUMBON.NAME AS varchar2(2000)) as TNAME, 
                            DISTRICT, cast(AMPUR.NAME AS varchar2(2000)) as DNAME, 
                            PROVINCE, cast(CHANGWAT.NAME AS varchar2(2000)) as PRNAME, 
                            ZIP_CODE, TELEPHONE, 
                            cast(DONATE_NOTE AS varchar2(2000)) as DONATE_NOTE, LETTER_TYPE,REGISTER_TYPE, DONATE_ITEM.CANCELDATE, FIRSTSTAFF, CANCELSTAFF 
                            from webintra.DONATE_ITEM
                            left outer join webout.TUMBON 
                                 on TUMBON.TUMBON = DONATE_ITEM.TAMBON
                            left outer join webout.AMPUR 
                                 on AMPUR.AMPUR = DONATE_ITEM.DISTRICT
                            left outer join webout.CHANGWAT 
                                 on CHANGWAT.CHANGWAT = DONATE_ITEM.PROVINCE 
                            where DONATE_ID = :DONATE_ID";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID));

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

    public function donateitemdt_fpdf()// มูลสิ่งของในใบรับบริจาค
    {
        $objCheck = $this->checkToken();

        if ($objCheck->check_result === true) {
            $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค

            $responseArray = array();
            $responseArray['json_result'] = true;

            $strSql = "select cast(DONATE_ITEMMASTER.ITEM_NAME AS varchar2(2000)) as ITEM_NAME, DONATE_ID, 
                            TO_CHAR(DONATE_DATE, 'DD/MM/YYYY HH24:MI:SS', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI')as DONATE_DATE, QUANTITY, 
                            cast(DONATE_CU.CU_NAME AS varchar2(2000)) as CU_NAME, PRICE, 
                            cast(DONATE_ITEMDT.ITEM_NAME AS varchar2(2000)) as ITEM_NAMEOTHER, ORDER_NUM 
                            from webintra.DONATE_ITEMDT
                            left outer join webintra.DONATE_ITEMMASTER 
                                 on DONATE_ITEMDT.ITEMTYPE_ID = DONATE_ITEMMASTER.ITEMTYPE_ID
                                 and DONATE_ITEMDT.ITEM_ID = DONATE_ITEMMASTER.ITEM_ID
                            left outer join webintra.DONATE_CU 
                                 on DONATE_ITEMDT.COUNTING_UNIT = DONATE_CU.CU_ID
                            where DONATE_ID = :DONATE_ID
                            ORDER BY ORDER_NUM";

            ////connect oracle แสดงข้อมูล
            $objQuery = $this->oracle_webintra->prepare($strSql);
            $objQuery->setFetchMode(PDO::FETCH_ASSOC);

            $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID));

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

    public function donate_confirm()// การยืนยันการได้รับสิ่งของบริจาค
    {
        $objCheck = $this->checkToken();
        if ($objCheck->check_result === true) {
            if ($_POST['CONFIRMTYPE_ID'] == "CONFIRM_INSERT") {
                $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค
                $CON_DATE = $_POST['CON_DATE'];//วันที่รับของบริจาค
                $CON_TELEPHONE = $_POST['CON_TELEPHONE'];//เบอร์โทรหน่วยงาน
                $FIRSTSTAFF = $_POST['FIRSTSTAFF'];//ผู้บันทึกข้อมูล
                $FIRSTDATE = $_POST['FIRSTDATE'];//วันที่ยืนยันรับของบริจาค

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "INSERT INTO webintra.DONATE_CONFIRM (donate_id, con_date, con_telephone, firststaff, firstdate)
                                VALUES (:DONATE_ID , to_date(:CON_DATE,'dd/mm/yyyy hh24:mi:ss') , :CON_TELEPHONE ,:FIRSTSTAFF,to_date(:FIRSTDATE,'dd/mm/yyyy'))";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID,':CON_DATE' => $CON_DATE,':CON_TELEPHONE' => $CON_TELEPHONE,':FIRSTSTAFF' => $FIRSTSTAFF,':FIRSTDATE' => $FIRSTDATE));

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
            }elseif ($_POST['CONFIRMTYPE_ID'] == "CONFIRM_UPDATE"){
                $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค
                $CON_TELEPHONE = $_POST['CON_TELEPHONE'];//เบอร์โทรหน่วยงาน

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "UPDATE webintra.DONATE_CONFIRM SET con_telephone = :CON_TELEPHONE WHERE DONATE_ID = :DONATE_ID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID,':CON_TELEPHONE' => $CON_TELEPHONE));

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
                $DONATE_ID = $_POST['DONATE_ID'];//รหัสใบรับบริจาค

                $responseArray = array();
                $responseArray['json_result'] = true;

                $strSql = "select donate_id, 
                                --TO_CHAR(con_date, 'DD/MM/YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI')as con_date,
                                TO_CHAR(con_date, 'MM/DD/YYYY')as con_datem,
                                TO_CHAR(con_date, 'DD/MM/YYYY')as con_dated,
                                con_telephone, 
                                firststaff, 
                                --TO_CHAR(firstdate, 'DD/MM/YYYY', 'NLS_CALENDAR=''THAI BUDDHA'' NLS_DATE_LANGUAGE=THAI')as firstdate 
                                TO_CHAR(firstdate, 'MM/DD/YYYY')as firstdate 
                                 from webintra.DONATE_CONFIRM where DONATE_ID = :DONATE_ID";

                ////connect oracle แสดงข้อมูล
                $objQuery = $this->oracle_webintra->prepare($strSql);
                $objQuery->setFetchMode(PDO::FETCH_ASSOC);

                $result = $objQuery->execute(array(':DONATE_ID' => $DONATE_ID));

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


}
