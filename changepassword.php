<?php 
include 'core/init.php'; 
protect_page();

if(empty($_POST) == false) {
	$required_fields = array('current_password','password','password_again');
	foreach($_POST as $key => $value) {
		if(empty($value) && in_array($key, $required_fields) == true) {
			$errors[] = 'Please fill in all fields!';
			break 1;
		}
	}
	
	if($user_data['password'] == md5($_POST['current_password'])) {
		if($_POST['password'] != $_POST['password_again']) {
			$errors[] = 'Your new passwords did not match! Please retry typing in the passwords.';
		}
		if(strlen($_POST['password']) <= 6 && strlen($_POST['password'] >= 30)) {
			$errors[] = 'Your password must be at least 6 characters and no greater than 30.';
		}
	} else {
		$errors[] = 'Your current password is incorrect!';
	}
}

include 'includes/overall/header.php'; 
?>

<h1>Change Password</h1>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo 'You\'ve changed your password successfully!<br>Redirecting you to the user page in 3 seconds...';
	header('Refresh: 3; url=settings.php');
} else {
	if(empty($_POST) == false && empty($errors) == true) {
		change_password($_SESSION['user_id'], $_POST['password']);
		header('Location: changepassword.php?success');
		exit();
		
	} else if(empty($errors) == false) {
		echo output_errors($errors);
	}
?>
<form action="" method="post">
	<ul>
		<li>
			Current Password:<br>
			<input type="password" name="current_password">
		</li>
		<li>
			New Password:<br>
			<input type="password" name="password">
		</li>
		<li>
			Retype New Password:<br>
			<input type="password" name="password_again">
		</li>
		<li>
			<input type="submit" value="Change Password">
		</li>
	</ul>
</form>

<?php 
}
include 'includes/overall/footer.php'; ?>