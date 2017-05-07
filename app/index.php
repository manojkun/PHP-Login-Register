<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<?php include 'head.inc.php' ?>
</head>
<body>
<?php
	$navlink = 'register.php';
	$navname = 'Register';
	include 'nav.inc.php'; 
?>
<?php 
	include 'database.inc.php';
	include 'core.inc.php';
	if(loggedin()) {
		header('Location: home.php');
	} else {
		include 'loginform.inc.php';
	}
?>
</body>
</html>