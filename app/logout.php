<?php
	session_start();
	setcookie('token', '', time() - (86400 * 5), '/');
	setcookie('user', '', time() - (86400 * 5), '/');
	if(session_destroy()) {
		header('Location: index.php');
	}
?>