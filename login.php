<?php
include 'core/init.php';
logged_in_redirect();

if(empty($_POST) == false) {

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(empty($username) == true || empty($password) ==  true) {
		$errors[] = 'You must enter a username and password.';
	} else if(user_exists($username) == false) {
		$errors[] = 'That username/password combination is incorrect.';
	} else if(user_active($username) == false) {
		$errors[] = 'You have not activated your account!';
	} else {
		$login = login($username, $password);
		if($login == false) {
			$errors[] = 'That username/password combination is incorrect.';
		} else {
			$_SESSION['user_id'] = $login;
			header('Location: index.php');
			exit();
		}
	}
	
} else {
	$errors[] = 'No data received.';
}
include 'includes/overall/header.php';
if(empty($errors) ==  false) {
?>	
	<h2>Login Error!</h2>
<?php
	echo output_errors($errors);
}
include 'includes/overall/footer.php';
?>