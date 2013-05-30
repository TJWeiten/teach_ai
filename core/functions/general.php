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

function email($to, $subject, $body) {
	mail($to, $subject, $body, 'From: noreply@tjweiten.com');
}
?>