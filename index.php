<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Use an Autoloader!
require_once 'libs/Bootstrap.php';
require_once 'libs/Controller.php';
require_once 'libs/Model.php';
require_once 'libs/View.php';

//Library
require_once 'libs/Database.php';
require_once 'libs/Session.php';

require_once 'config/paths.php';
require_once 'config/database.php';

$app = new Bootstrap();
