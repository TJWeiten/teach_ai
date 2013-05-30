<?php
# Sanitize data from MySQL injections
function sanitize($data) {
	return htmlentities(strip_tags(mysql_real_escape_string($data)));
}

# Sanitize array data from MySQL injections
function array_sanitize(&$item) {
	$item = htmlentities(strip_tags(mysql_real_escape_string($item)));
}

# Output relevant errors neatly
function output_errors($errors) {
	return '<span style="color:#FF0000"><ol><li>' . implode('</li><li>', $errors) . '</li></ol></span>';
}

# Keeps non-logged in users from accessing protected pages
function protect_page() {
	if(logged_in() == false) {
		header("Location: nosession.php");
		exit();
	}
}

# Keeps logged in users from accessing pages for non-logged in users
function logged_in_redirect() {
	if(logged_in() == true) {
		header("Location: index.php");
		exit();
	}
}

# Read the user's AI file based on the MD5 hash of their username
function ai_read() {
	# Get session id and relevant user information
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username');
	$usernamehash = md5($user_data['username']);
	$file = 'file://' . 'C:\Web\htdocs\data\preconverted\ai' . $usernamehash . '.txt' ;
	$fp = fopen($file, "r");
	while(!feof($fp)) {
		$data = fgets($fp, filesize($file));
		echo $data;
	}
	fclose($fp);
}

function email($to, $subject, $body) {
	mail($to, $subject, $body, 'From: noreply@tjweiten.com');
}
?>