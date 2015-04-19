
<div class="login">
  <div>
    <p>Willkommen zurück!<br />
      Bitte logge dich mit deinen Nutzerdaten ein.</p>
    <img src="img/login_bild.png" />
  </div>
  <form method="post" class="clear" action="index.php?s=login">
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">@</span>
        <input name="id" type="text" class="form-control" placeholder="Username oder Email" aria-describedby="basic-addon1">
    </div>

    <div class="input-group">
        <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1"></span>
        <input name="pw" type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
    </div>

    <input class="btn btn-primary" type="submit" value="Login" />
  </form>
    
    <?php



if(isset($_POST['id'])) {
    $log = new Login($_POST['id'], $_POST['pw']);
    
    if($log->verifyLogin()) {
        if($log->doLogin()) {
            echo'<p class="success">Erfolgreich eingeloggt.</p>';    
            echo'<meta http-equiv="refresh" content="0; URL=index.php?s=home" />';
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
</div>
