<?php 
	# Require init.php to get session information
	require '../core/init.php';
	
	# If there was something sent in their post data...
	$session_user_id = $_SESSION['user_id'];
	$user_data = user_data($session_user_id, 'user_id', 'username', 'bot_working');
	$userbot = "postconverted.ai" . md5($user_data['username']);
	
	# Get all working user bots
	$result = mysql_query("SELECT `username` FROM `users` WHERE `bot_working` = 1 AND `user_id` != $session_user_id");
	while($row = mysql_fetch_row($result, MYSQL_NUM)) {
		$valid_usernames[] = $row;
	}
	
	$random_users = array_rand($valid_usernames , 3);
	
	$username1 = "postconverted.ai" . md5($valid_usernames[$random_users[0]][0]);
	$username2 = "postconverted.ai" . md5($valid_usernames[$random_users[1]][0]);
	$username3 = "postconverted.ai" . md5($valid_usernames[$random_users[2]][0]);
		
	if($user_data['bot_working']) {
		# Run game passing our players
		exec("java postconverted/Game " . $userbot . " " . $username1 . " " . $username2 . " " . $username3, $output);
		$replay = $output['0'];
		
		# Insert information into the database
		$query = "UPDATE `games` SET `replay` = '$replay' WHERE `user_id` = " . $session_user_id; 
		$results = mysql_query( $query );
			
		if(isset($_GET['debug']) && empty($_GET['debug'])) {
			echo "<h2>DEBUG INFORMATION:</h2>";
			echo "<strong>Users in game:</strong><br><ul>";
			echo "<li>" . $userbot . "</li>";
			echo "<li>" . $username1 . "</li>";
			echo "<li>" . $username2 . "</li>";
			echo "<li>" . $username3 . "</li></ul>";
			echo "<strong>Replay Value:</strong><br><ul>";
			echo $replay . "</ul>";
			echo "<strong>Query:</strong><br><ul>" . $query . "</ul>";
			echo "<strong>Results were... ";
			if($results == 1)
				echo "good!</strong>";
			else
				echo "bad... :(</strong>";
		} else {
			# Redirect to waiting page...
			header("Location: ../test_wait.php");
		}
	} else {
		header("Location: ../test_errors.php");
	}
?>