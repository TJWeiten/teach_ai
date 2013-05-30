<?php 
include 'core/init.php'; 
protect_page();
include 'includes/overall/header.php'; 

if(empty($_POST) == false) {
	$required_fields = array('first_name','last_name','email');
	foreach($_POST as $key => $value) {
		if(empty($value) && in_array($key, $required_fields) == true) {
			$errors[] = 'Please fill in all fields!';
			break 1;
		}
	}
	
	if(empty($errors) == true) {
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid e-mail address is required.';
		} else if(email_exists($_POST['email']) == true && $user_data['email'] != $_POST['email']) {
			$errors[] = 'Sorry, the e-mail address \'' . $_POST['email'] . '\' is already in the system.';
		}
	}
}
?>

<h1>Update User Information</h1>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo 'You\'ve changed your information successfully!<br>Redirecting you to the user page in 3 seconds...';
	header('Refresh: 3; url=settings.php');
} else {
	if(empty($_POST) == false && empty($errors) == true) {
		$update_data = array(
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name'],
			'email' 		=> $_POST['email']
		);
		update_user($update_data);
		header('Location: settings.php?success');
		exit();
		
	} else if(empty($errors) == false) {
		echo output_errors($errors);
	}
?>
<form action="" method="post">
	<ul>
		<li>
			First Name:<br>
			<input type="text" name="first_name" value="<?php echo $user_data['first_name'] ?>">
		</li>
		<li>
			Last Name:<br>
			<input type="text" name="last_name" value="<?php echo $user_data['last_name'] ?>">
		</li>
		<li>
			E-Mail:<br>
			<input type="text" name="email" value="<?php echo $user_data['email'] ?>">
		</li>
		<li>
			<a href="changepassword.php">Change Password</a>
		</li>
		<li>
			<input type="submit" value="Update">
		</li>
	</ul>
<?php 
}
include 'includes/overall/footer.php'; 
?>