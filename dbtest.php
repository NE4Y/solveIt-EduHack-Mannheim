<?php
error_reporting(E_ALL);

require("inc/config.inc.php");

function __autoload($class_name) {
    include 'classes/'.strtolower($class_name) . '.class.php';
}
DBHandler::initDB();


$test = DBHandler::getDB()->fetch_assoc("SELECT id FROM account WHERE id=? LIMIT 1", array("1"));

echo $test['id'];


?>