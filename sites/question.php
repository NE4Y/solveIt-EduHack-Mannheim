<?php
$q = Question::getQuestionData($_GET['id']);
$hashTags = Question::getHashtags($_GET['id']);

?>
<div class="problem open">
                <div class="col-md-9">
                  <div class="headline">
                    <h1><?php echo $q->getQuestion()?></h1>
                    <ul class="hashtags">
                        <?php
                        foreach($hashTags as $e) {
                            echo'<li><a href="">#'.$e['hashtag'].'</a></li>';
                        }
                        ?>
                    </ul>
                  </div>
                  
                  <div class="col-md-12 recommended-wrapper clear">
                    <h2>Ähnliche Probleme</h2>
                    
                    <div class="similar">
                      <a class="problem-name" href="">Dies ist mal wieder ein Problemtitel. Bitte löst mein Problem!</a>
                      <ul class="hashtags">
                      <li><a href="">#hashtag1</a></li>
                      <li><a href="">#hashtag2</a></li>
                      <li><a href="">#hashtag3</a></li>
                    </ul>
                      <div class="user-data">
                        <a href=""><?php echo Chat::getUserNameId($q->getAuthor()); ?></a>
                        <span class="rank">Community Rang</span>
                        <a href="">★★★☆☆</a>
                      </div>
                    </div>
                    
                    <div class="similar">
                      <a class="problem-name" href="">Dies ist mal wieder ein Problemtitel. Bitte löst mein Problem!</a>
                      <ul class="hashtags">
                      <li><a href="">#hashtag1</a></li>
                      <li><a href="">#hashtag2</a></li>
                      <li><a href="">#hashtag3</a></li>
                    </ul>
                      <div class="user-data">
                        <a href="">Username</a>
                        <span class="rank">Community Rang</span>
                        <a href="">★★★☆☆</a>
                      </div>
                    </div>
                    
                  </div>
              </div>
                

                <div class="col-md-3 side">
                  <div class="user-img"><img src="img/login_bild.png" alt="Benutzerbild" /></div>
                  <div class="user-data">
                    <h2><?php echo Chat::getUserNameId($q->getAuthor()); ?></h2>
                    <span class="rank">Community Rang</span>
                    <a href="">★★★☆☆</a>
                  </div>
                  <div class="spacer"></div>
                    <?php if($q->isSolved()) { echo'<p class="success">Dieses Problem ist bereits gelöst.</p>'; } else { ?> <a class="btn btn-primary" href="index.php?s=startChat&a=<?php echo $q->getAuthor();?>&q=<?php echo $_GET['id'];?>" >Problem lösen</a> <?php } ?>               </div>
            </div>
