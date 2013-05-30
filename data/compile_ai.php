<?php

# Require init.php to get session information
require '../core/init.php';

# If there was something sent in their post data...
$session_user_id = $_SESSION['user_id'];
$user_data = user_data($session_user_id, 'user_id', 'username');
$usernamehash = md5($user_data['username']);
$user_id = $_SESSION['user_id'];
	
exec("javac -Xstdout errors\\" . $usernamehash . ".txt " . $_SERVER['DOCUMENT_ROOT'] . "\data\postconverted\ai" . $usernamehash . ".java", $compilation_output);

$file = $_SERVER['DOCUMENT_ROOT'] . "/data/errors/" . $usernamehash . ".txt";
if(file_exists($file)) {
	if(filesize( $file ) == 0)
	{
		mysql_query("UPDATE `users` SET `bot_working` = '1' WHERE `user_id` = " . $user_id); 
	} else {
		mysql_query("UPDATE `users` SET `bot_working` = '0' WHERE `user_id` = " . $user_id); 
	}
}
	  
?>