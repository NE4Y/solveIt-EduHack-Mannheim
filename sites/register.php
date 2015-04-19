

          <div class="register">
            <div>
              <p>Willkommen bei SolveIt!<br />
                Wir freuen uns, dass du ein Teil unserer Community werden möchtest. Bitte gib dazu deine Nutzerdaten an.</p>
              <img src="img/login_bild.png" />
            </div>
            <form method="post" class="clear" action="index.php?s=register">
              <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">@</span>
                  <input name="id" type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
              </div>

              <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">@</span>
                  <input name="email" type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1">
              </div>

              <div class="input-group">
                  <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1"></span>
                  <input name="pw" type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
              </div>

              <div class="input-group">
                  <span class="input-group-addon glyphicon glyphicon-lock" id="basic-addon1"></span>
                  <input name="repw" type="password" class="form-control" placeholder="Password wiederholen" aria-describedby="basic-addon1">
              </div>
              <input class="btn btn-primary" type="submit" name="register" value="Register" />
            </form>
              <?php

// init register
if(isset($_POST['id'])) {
    $reg = new Register($_POST['id'], $_POST['pw'], $_POST['repw'], $_POST['email']);
    
    if($reg->verifyRegister()) {
        if(!$reg->existsUser() && !$reg->existsEmail()) {
            if($reg->register()) {
                echo'<p class="success">Registration erfolgreich.</p>';
            }
            else {
                Error::showError();
            }
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
