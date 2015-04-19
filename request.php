
<?php	

session_start();

require("inc/config.inc.php");

function __autoload($class_name) {
    include 'classes/'.strtolower($class_name) . '.class.php';
}

DBHandler::initDB();

if(isset($_GET['s']) && !empty($_GET['s'])) {
    if(file_exists(realpath('./sites/')."/".$_GET['s'].".php")) {
        include(realpath('./sites/')."/".$_GET['s'].".php");
    }
    else {
        include(realpath('./sites/').'/404.php');
    }
} 
else {
 include(realpath('./sites/home.php'));
}
?>
