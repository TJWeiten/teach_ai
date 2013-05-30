<?php
# Checks if a user exists and returns true or false
function user_exists($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

# Checks if a user's e-mail address is already in the system
function email_exists($email) {
	$email = sanitize($email);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email` = '$email'");
	return (mysql_result($query, 0) == 1) ? true : false;
}

# Checks if the user has an activated account
function user_active($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `active` = 1");
	return (mysql_result($query, 0) == 1) ? true : false;
}

# Takes a username and returns the user's id
function user_id_from_username($username) {
	$username = sanitize($username);
	$query = mysql_query("SELECT (`user_id`) FROM `users` WHERE `username` = '$username'");
	return mysql_result($query, 0, 'user_id');
}

# Takes an email and returns the user's id
function user_id_from_email($email) {
	$email = sanitize($email);
	$query = mysql_query("SELECT (`user_id`) FROM `users` WHERE `email` = '$email'");
	return mysql_result($query, 0, 'user_id');
}

# Logs a user in based on their username and password and returns false if their credentials were not correct
function login($username, $password) {
	$user_id = user_id_from_username($username);
	$username = sanitize($username);
	$password = md5($password);
	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username` = '$username' AND `password` = '$password'");
	return (mysql_result($query, 0) == 1) ? $user_id : false;
}

# Checks if a user is logged in
function logged_in() {
	return (isset($_SESSION['user_id'])) ? true : false;
}

# Returns an array of all relevant user data
function user_data($user_id) {
	$data = array();
	$user_id = (int)$user_id;
	
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	if($func_num_args > 1) {
		unset($func_get_args[0]);
		$fields = '`' . implode('`, `', $func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `user_id` = $user_id"));
		return $data;
	}
}

# Inserts user information into the accounts database; also creates their AI file
function register_user($register_data) {
	array_walk($register_data, 'array_sanitize');
	$register_data['password'] = md5($register_data['password']);
	
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	# Register the user
	mysql_query("INSERT INTO `users` ($fields) VALUES ($data)");
	
	# Insert first game replay
	mysql_query("INSERT INTO `games` (`user_id`, `replay`) VALUES ('" . user_id_from_username($register_data['username']) . "', '" . "NULL" . "')");
	
	# Create preconverted AI file with default welcome message based on an MD5 hash of their username
	$usernamehash = md5($register_data['username']);
	$file = 'C:\Web\htdocs\data\preconverted\ai' . $usernamehash . '.txt' ;
	$fp = fopen($file, "w");
	$msg = "// Welcome to the AI Development Platform, " . $register_data['first_name'] . "!\n// Please read the documentation above for instructions on using the program!\n\n";
	fwrite($fp, $msg);
	fclose($fp);
}

# Inserts user information into the accounts database; also creates their AI file
function update_user($update_data) {
	array_walk($update_data, 'array_sanitize');
	
	$fields = '`' . implode('`, `', array_keys($register_data)) . '`';
	$data = '\'' . implode('\', \'', $register_data) . '\'';
	
	foreach($update_data as $field => $data) {
		$update[] = '`' . $field . '` = \'' . $data . '\'';
	}

	mysql_query("UPDATE `users` SET" . implode(', ', $update) . " WHERE `user_id` = " . $_SESSION['user_id']);
}

# Updates a user's password in the database
function change_password($user_id, $password) {
	$password = md5($password);
	mysql_query("UPDATE `users` SET `password` = '$password'" . " WHERE `user_id` = " . $user_id); 
}

# Attempts to recover usernames or passwords should they be lost
function recover($mode, $email) {

	$mode = sanitize($mode);
	$email = sanitize($email);
	
	$user_data = user_data(user_id_from_email($email), 'user_id', 'first_name', 'username');
	
	if($mode == 'username') {
		email($email, "Your Username on AI Development", "Hello " . $user_data['first_name'] . ",\n\nYour username is: " . $user_data['username'] . "\n\nThanks!");
	} else if($mode == 'password') {
		$generated_password = substr(md5(rand(999,999999)), 0, 10);
		change_password($user_data['user_id'], $generated_password);
		email($email, 'Your Username on AI Development', "Hello " . $user_data['first_name'] . ",\n\nYour password is now: " . $generated_password . "\n\nPlease log in to change this.\n\nThanks!");
	}

}
?>