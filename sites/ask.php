<form action="index.php?s=shareProblem" method="post">
<textarea id="question" name="question" class="form-control" rows="2" placeholder="Wie kann dir geholfen werden? (#hashtags erlaubt)"></textarea>


<div id="matching-persons" class="row">
</div>

<div id="ask-public" class="thumbnail">
	<p>Keinen passenden Ansprechpartner gefunden?</p>
	<button class="btn btn-primary" type="submit">Stelle deine Frage öffentlich</button>
</div>
</form>

<template id="expert-preview">
	<div class="col-md-4 person-container">
		<div class="person thumbnail clearfix">
			<img class="pull-left" src="http://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c00001?default=retro"/>
			<section>
				<h3>TheMasterMinder</h3>
				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p>
				<button class="btn btn-primary pull-right">Hilf mir</button>
			</section>
		</div>
	</div>
</template>

<script src="js/ask.js"></script>
