<?php
if(isset($_SESSION['username'])) {
    $c = new Chat();
    $d = $c->createChat($_GET['q'], $_GET['a']);
    echo'<meta http-equiv="refresh" content="0; URL=index.php?s=chat&id='.$d.'" />';
    
}
else {
echo'<p class="error">Bitte logge dich ein.</p>';
}
?>
