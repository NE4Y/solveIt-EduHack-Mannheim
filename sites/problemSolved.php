<?php if(isset($_SESSION['username'])) {
  if(Chat::solveQuestion($_GET['id'])) {
	echo'<p class="success">Das Problem wurde erfolgreich gelöst.</p>';
  }
}
else {
  echo '<p class="error">Bitte logge dich ein.</p>';
}
?>
