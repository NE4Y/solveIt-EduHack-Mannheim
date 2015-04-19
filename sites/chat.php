<?php
if(isset($_SESSION['username'])) {
$c            = new Chat();
$d            = $c->getChatById($_GET['id']);
$chatMessages = $c->getChatMessages($_GET['id']);
$oldAuthor                       = "-1";
#var_dump($chatMessages[0]);
    
    
$usernames[strval($_SESSION['user_id'])] = $_SESSION['username'];
if ($d['user2'] != $_SESSION['user_id']) {
    $usernames[strval($d['user2'])] = $c->getUserNameById($d['user2']);
} else {
    $usernames[strval($d['user1'])] = $c->getUserNameById($d['user1']);
}
    


?>


<section class="chat-background">
  <div class="container">
    <div class="chat">
      <div class="col-md-9">
          <div class="col-md-12 post clear">
             <div class="col-md-2">
               <div class="user1 user-data">
                 <img src="img/login_bild.png" alt="Benutzerbild" />
                  <p><?php echo $usernames[strval($chatMessages[0]['author'])];?></p>
               </div>
             </div>
          <div class="col-md-10 content">
          <?php
          $oldAuthor = "-1";
          foreach($chatMessages as $e) { 
              if($oldAuthor != $e['author'] && $oldAuthor != -1) {
                  $oldAuthor = $e['author'];
                  ?>
                   
              </div>
              </div>
              <div class="col-md-12 post clear">
              <div class="col-md-2">
               <div class="user1 user-data">
                 <img src="img/login_bild.png" alt="Benutzerbild" />
                  <p><?php echo $usernames[strval($e['author'])];?></p>
               </div>
             </div>
          <div class="col-md-10 content">
              <p><?php echo $e['msg'];?>  <span class="rightTime"><?php echo date("d.m.Y - H:i", $e['timestamp']);?></span></p>
             
                <?php                  
              }
              else {                            
                  echo'<p>'.$e['msg'].' <span class="rightTime">'.date("d.m.Y - H:i", $e['timestamp']).'</span></p>';
                  ?>
                   
                  <?php
                  $oldAuthor = $e['author'];
              }
        ?>
                   
          <?php
                                       }
            
?>
                  </div>
          </div>
        </div>
                <div class="col-md-3 side">
                  <div class="headline">
                      <h1><?php
echo $d['chat_title'];
                          ?></h1></div>
                    <ul class="hashtags">
                      <li><a href="">#hashtag1</a></li>
                      <li><a href="">#hashtag2</a></li>
                      <li><a href="">#hashtag3</a></li>
                    </ul>
                  </div>
                    </div>
                   
                
    
     </section>
    <?php
    $toID;
    if($d['user1'] == $_SESSION['user_id']) {
        $toID = $d['user2'];
    }
    else {
        $toID = $d['user1'];
    }
    ?>
    
      <section id="reply">
        <div class="row">
          <div class="col-md-12">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="chatMessage" placeholder="Schreibe deine Antwort">
                <span class="input-group-btn">
                    <button id="chatSend" class="btn btn-default yellow" type="button">Absenden</button>
                    <input type="hidden" value="<?php echo $_SESSION['user_id']; ?>" id="sessionId" />
                    <input type="hidden" value="<?php echo $toID;?>" id="toID" />
                    <input type="hidden" value="<?php echo $_SESSION['username']; ?>" id="username" />
                    <input type="hidden" value="<?php echo $_GET['id']; ?>" id="chatID" />
                    <input type="hidden" value="<?php echo Chat::getUserNameId($toID); ?>" id="partnerName" />
                </span>
            
            </div>
            <a class="btn btn-primary" type="" href="index.php?s=problemSolved&id=<?php echo $_GET['id'];?>">Problemlösung abschließen</a>
            <div class="clear"></div>
              
            </div>
          </div>
      </section>
    <?php
}
else {
    echo'<p class="error">Bitte logge dich ein.</p>';
}
?>
          
