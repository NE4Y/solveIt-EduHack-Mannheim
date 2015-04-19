<?php
$username = DBHandler::getDB()->fetch_assoc("SELECT username FROM account WHERE id=? LIMIT 1", array($_GET['id']));

echo $username['username'];
?>