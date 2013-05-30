<aside id="Just_A_Random_ID">
	<?php 
		if(logged_in() == true) {
			include 'includes/widgets/logged_in.php';
			include 'includes/widgets/info.php';
			include 'includes/widgets/scores.php';
		} else {
			include 'includes/widgets/login.php'; 
		}
	?>
</aside>