<?php
if(isset($_SESSION['username'])) {
    if(DBHandler::getDB()->query("INSERT INTO questions (question, author, status) VALUES (?,?,?)", array($_POST['question'], $_SESSION['user_id'], 0))) {
    echo'<p class="success">Dein Problem wurde online gestellt.</p>';
    }
    
}
else {
  echo'<p class="error">Bitte logge dich ein.</p>';
}
?>
