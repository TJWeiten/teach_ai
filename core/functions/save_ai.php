<?php

# Require init.php to get session information
require '../init.php';

# If there was something sent in their post data...
if(isset($_POST['data'])) {
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username');
	$usernamehash = md5($user_data['username']);
	
	# Save their updated AI file based on the MD5 hash of their username
	$file = $_SERVER['DOCUMENT_ROOT'] . '/data/preconverted/ai' . $usernamehash . '.txt' ;
	$fp = fopen($file, "w");
	$data = $_POST['data'];
	fwrite($fp, $data);
	fclose($fp);
	
	# Translate our pseduolanguage to Java code
	exec("java Translate " . $usernamehash . " \"" . $_SERVER['DOCUMENT_ROOT'] . "\"", $translate_output);
}
	  
?>