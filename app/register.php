<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<?php include 'head.inc.php' ?>
</head>
<body>
<?php
	$navlink = 'index.php';
	$navname = 'Login';
	include 'nav.inc.php'; 
?>
<?php 
	include 'database.inc.php';
	include 'core.inc.php';
	if(loggedin()) {
		header('Location: home.php');
	} else {
		include 'registerform.inc.php';
	}
?>
</body>
</html>