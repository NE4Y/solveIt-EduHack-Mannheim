<?php

require_once '../inc/config.inc.php';
require_once '../classes/dbhandler.class.php';
require_once '../classes/db.class.php';
require_once '../classes/question.class.php';
require_once '../classes/hashtag.class.php';
require_once '../classes/profile.class.php';

DBHandler::initDB();

if(isset($_GET["q"]))
{
	$q = $_GET["q"];
	$keywords = explode(' ', $q);
	$search_result = Profile::findManyByKeywords($keywords);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($search_result);
}
else
{
	die("query string missing");
}