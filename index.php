<?php
session_start();

require("inc/config.inc.php");

function __autoload($class_name) {
    include 'classes/'.strtolower($class_name) . '.class.php';
}


DBHandler::initDB();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="js/navi.js"></script>
        <script src="js/page.js"></script>
        <script src="js/search.js"></script>
        <script type="text/javascript" src="js/chat.js"></script>
        <link rel="stylesheet" href="css/main.css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link rel="shortcut icon" href="img/logo_favicon.png" type="image/png" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="icon" href="img/logo_favicon.png" type="image/png" />
        <script src="js/notify.js"></script>
        <?php
        if(isset($_SESSION['username'])) {
            echo '<script type="text/javascript">function WebSocketTest() {
     var ws = new WebSocket("ws://ne4y-dev.de:9999");
     
     ws.onopen = function()    {
        // Web Socket is connected, send data using send()
        console.log("connected");
         ws.send("ID:" + "'.base64_encode($_SESSION['user_id']).'");
         console.log("Autoisierungskey gesendet");
     };
      
     ws.onmessage = function (msg)     { 
        var received_msg = msg.data;
        console.log("Message is received:" + msg.data);
        split = msg.data.split("@");
        console.log(msg);
        var to = $("#chatID");
        if(to && to.val() == split[4]){
            
            appendMessage(split[1], $("#partnerName").val());
        } 
        $.ajax({
        url: "request.php?s=getID&id=" + split[2],
        success: function (result) {
            console.log(split[4])
       
            notify("Sie haben eine neue Nachricht von " + result + " erhalten.", split[4])
        }
    });
        
        
        
     };
      
     ws.onclose = function()     { 
        // websocket is closed.
        console.log("connection closed");
     };
  }</script>';
        }
        ?>
    </head>
    <body <?php echo (isset($_SESSION['username'])) ? 'onload="WebSocketTest()"' : ''?>>
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="index.php?s=home" id="logo" >
                <img alt="Brand" src="img/logo_klein.png"/>
              </a>
                <ul>
                    <li><a href="index.php?s=home">Suchen</a></li>
                    <li><a href="index.php?s=ask">Fragen</a></li>
                    <?php echo isset($_SESSION['username']) ? '<li><a href="index.php?s=profile">Profil</li><li><a href="index.php?s=chats">Chats</li><li><a href="index.php?s=logout">Logout</a></li>' : '<li><a href="index.php?s=login">Login</a></li> <li><a href="index.php?s=register">Registrieren</a></li>';?>                    
                </ul>
            </div>
          </div>
        </nav>
        <section class="container">
            <?php	
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
        </section>
        <div id="notification"></div>
        
        <script type="text/javascript" src="js/login.js"></script>
       

        <div id="notificationArea">
        </div>
        
    </body>
</html>
