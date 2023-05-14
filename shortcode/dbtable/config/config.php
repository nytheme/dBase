<?php

ini_set('display_errors', 1);

define('USER', 'root');
define('PW', 'root');
define('HOST', 'localhost');
define('PORT', 10006);
define('DBNAME', 'local');
define('DSN', 'mysql:host='.HOST.';port='.PORT.';dbname='.DBNAME.';charset=utf8');

require_once(__DIR__ . '/../lib/functions.php');
require_once(__DIR__ . '/../lib/Model.php');

session_start();
