<?php
if(isset($_SESSION['username'])) {
    $c = new Chat();
    $d = $c->getChats();
    
    
    
    
    
    foreach($d as $e) {
       ?>
<a href="index.php?s=chat&id=<?php echo $e['id'];?>" style="color:black;">
<div class="col-md-3 side">
    <div class="user-img" style="width:50%; margin:0px auto; padding-top:10px;"><img src="img/login_bild.png" style="margin:0px auto;" alt="Benutzerbild" /></div>
    <div class="user-data">
        <h2><?php echo $e['chat_title'];?></h2>
        <span class="rank"><?php echo ($e['user1'] == $_SESSION['user_id']) ? $c->getUserNameById($e['user2']) : $c->getUserNameById($e['user1']); ?></span>
        <a href="">★★★☆☆</a>
    </div>
    <div class="spacer"></div>
    </div></a>
    <?php
    }
    
}
else {
    echo'<p class="error">Bitte logge dich ein.</p>';
}
?>