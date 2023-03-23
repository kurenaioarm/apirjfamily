<?php

class Oracle_Database extends PDO
{

  var $tns = "  
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = " . ORA_HOST_PRO . ")(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = " . ORA_TNS_NAME_PRO . ")
    )
  )";

  function __construct()
  {
    parent::__construct(
      ORA_TYPE_PRO . ':dbname=' . $this->tns . ';charset=UTF8',
      ORA_USERE_PRO,
      ORA_PASS_PRO,
      array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_PERSISTENT => false
      )
    );
  }
}

class Oracle_His extends PDO
{

  var $tns = "  
(DESCRIPTION =
  (ADDRESS_LIST =
    (ADDRESS = (PROTOCOL = TCP)(HOST = " . ORA_HOST_HIS . ")(PORT = 1521))
  )
  (CONNECT_DATA =
    (SERVICE_NAME = " . ORA_TNS_NAME_HIS . ")
  )
)";

  function __construct()
  {
    parent::__construct(
      ORA_HIS . ':dbname=' . $this->tns . ';charset=AL32UTF8',
      ORA_USERE_HIS,
      ORA_PASS_HIS,
      array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_PERSISTENT => false
      )
    );
  }
}

class Oracle_WEBINTRA extends PDO
{

    var $tns = "  
(DESCRIPTION =
  (ADDRESS_LIST =
    (ADDRESS = (PROTOCOL = TCP)(HOST = " . ORA_HOST_WEBINTRA . ")(PORT = 1521))
  )
  (CONNECT_DATA =
    (SERVICE_NAME = " . ORA_TNS_NAME_WEBINTRA . ")
  )
)";

    function __construct()
    {
        parent::__construct(
            ORA_WEBINTRA . ':dbname=' . $this->tns . ';charset=AL32UTF8',
            ORA_USERE_WEBINTRA,
            ORA_PASS_WEBINTRA,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_PERSISTENT => false
            )
        );
    }
}

class Oracle_WEBOUT extends PDO
{

    var $tns = "  
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = " . ORA_HOST_WEBOUT . ")(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = " . ORA_TNS_NAME_WEBOUT . ")
    )
  )";

    function __construct()
    {
        parent::__construct(
            ORA_WEBOUT . ':dbname=' . $this->tns . ';charset=UTF8',
            ORA_USERE_WEBOUT,
            ORA_PASS_WEBOUT,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_PERSISTENT => false
            )
        );
    }
}