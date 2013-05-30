<?php
//error_reporting(0);
session_start();

require 'database/connect.php';
require 'functions/general.php';
require 'functions/users.php';

if(logged_in() == true) {
	# Get session id and relevant user information
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username', 'password', 'first_name', 'last_name', 'email', 'bot_working');
	
	# Check if account is active
	if(user_active($user_data['username']) == false) {
		session_destroy();
		header("Location: index.php");
		exit();
	}
}

$errors = array();
?>