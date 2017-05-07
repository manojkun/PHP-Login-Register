<?php
	session_start();
	$current_file = $_SERVER['SCRIPT_NAME'];
	
	function loggedin() {
		if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
			return true;
		} else if(isset($_COOKIE['user']) && isset($_COOKIE['token'])) {
			global $conn;
			$select_statement = $conn -> prepare('SELECT * FROM login WHERE UID = ?');
			$select_statement -> bind_param('s', $_COOKIE['user']);
			$select_statement -> execute();
			$result = $select_statement -> get_result();
			$row = $result -> fetch_assoc();
			if($_COOKIE['token'] == $row['token']) {
				$_SESSION['user'] = $row['UID'];
				$token = bin2hex(random_bytes(25));
				$update_statement = $conn -> prepare('UPDATE login SET token = ? WHERE UID = ?');
				$update_statement -> bind_param('ss', $token, $row['UID']);
				$update_statement -> execute();
				setcookie('user', $row['UID'], time() + (86400 * 5), '/');
				setcookie('token', $token, time() + (86400 * 5), '/');
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
?>