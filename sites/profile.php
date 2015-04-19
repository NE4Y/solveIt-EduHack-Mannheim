<?php 
require_once 'classes/profile.class.php';

if(isset($_GET["uid"]))
{
	$profile = Profile::findOneById($_GET["uid"]);
}
else
{
	$profile = Profile::findOneByCurrentSession();
}

$myProfile = Profile::findOneByCurrentSession();

?>
<div class="col-md-3">
	<div class="side">
   		<img id="profile-pic" src="<?php echo $profile->getPictureUrl();?>" alt="Benutzerbild" />
	    <div class="user-data">
	        <h2><?php echo htmlspecialchars($profile->getUsername())?></h2>
	    	<span class="key">Community Rang:</span><span class="value">★★★☆☆</span><br/>
	    	<span class="key">Registriert seit:</span><span class="value"><?php echo date("d.m.Y", $profile->getRegistrationDate());?></span>
	    </div>
	    <div class="spacer"></div>
	</div>
	<h3>Kompetenzen</h3>
    <ul id="competence-container">
	<?php 
		$competences = Hashtag::findManyByProfile($profile);
		if(count($competences) == 0)
		{
			echo "<p>Noch keine.</p>";
		}
		foreach ($competences as $competence) {
			echo "<li>";
			echo htmlspecialchars($competence->getHashtag());
			echo "</li>";
		}
	?>
	</ul>
    <div class="row">
       <div class="col-lg-12">
                <div class="input-group input-group-sm">
                  <input name="newCompetence" type="text" class="form-control" placeholder="neue Kompetenz" id="newCompetence">
                  <span class="input-group-btn">
                    <button class="btn btn-default" id="addComp" type="button">Hinzufügen</button>
                  </span>
                </div><!-- /input-group -->
              </div><!-- /.col-lg-6 -->
            </div><!-- /.row --> 
    </div>
</div>
<div class="col-md-9">
	<?php if ($profile->getId() != $myProfile->getId()){?>
	<h3>Gemeinsame Chats</h3>
	<?php 
	$chats = new Chat();
	$togetherChats = $chats->getTogetherChats($profile, $myProfile);
	foreach ($togetherChats as $chat) {
		?>
		<div class="chat <?php echo ($chat["status"])?"inactive":"active";?>"><?php echo htmlentities(["chat_title"]);?><a href="#" class="pull-right continue">fortsetzen <span class="glyphicon glyphicon-arrow-right"></span></a><a href="#" class="pull-right view">ansehen <span class="glyphicon glyphicon-arrow-right"></span></a></div>
		<?php
	}
	if(count($togetherChats) == 0)
	{
		echo '<p class="no-chats">Du hattest bisher keinen Kontakt mit '. htmlspecialchars($profile->getUsername()) . '.</p>';	
	}
	else 
	{
		echo "<br/>";
	}
	?>
	<button class="btn btn-success">Chat beginnen</button>
	<?php } ?>
	<h3>Beantwortete Fragen</h3>
	<p class="no-questions"><?php echo htmlspecialchars(strtoupper(substr($profile->getUsername(), 0, 1)) . substr($profile->getUsername(), 1))?> hat noch keine Frage beantwortet.</p>
</div>
<div class="clear"></div>

<script src="js/profile.js"></script>
