<?php
if(isset($_POST['login'])) {
    $log = new Login($_POST['id'], $_POST['pw']);
    
    if($log->verifyLogin()) {
        if($log->doLogin()) {
            echo'<p class="success">Erfolgreich eingeloggt.</p>'; 
            echo'<meta http-equiv="refresh" content="3; url=index.php" />';
        }
        else {
            Error::showError();
        }
    }
    else {
        Error::showError();
    }
}
?>