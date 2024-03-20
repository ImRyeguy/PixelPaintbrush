
<?php

$ini = parse_ini_file( __DIR__ . '/prod_dbconfig.ini');

// var_dump($ini);
// exit;

$db = new PDO(
                "mysql:host=" . $ini['servername'] . 
                ";port=" . $ini['port'] . 
                ";dbname=" . $ini['dbname'], 
                $ini['username'], 
                $ini['password']);


$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//var_dump($db);
