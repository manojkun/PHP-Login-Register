<?php 
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $password = $username_error = $password_error = '';
		
		if (isset($_POST['username'])) {
			if(empty($_POST['username'])) {
				$username_error = 'Username is required';
			} else {
				$username = test_input($_POST['username']);
			}
		}
		if (isset($_POST['password'])) {
			if(empty($_POST['password'])) {
				$password_error = 'Password is required';
			} else {
				$password = test_input($_POST['password']);
			}
		}


		if($username_error == '' && $password_error == '') {
			$select_statement = $conn -> prepare('SELECT * FROM login WHERE UID = ?');
			$select_statement -> bind_param('s', $username);
			$select_statement -> execute();
			$result = $select_statement -> get_result();
			$row = $result -> fetch_assoc();

			if($row == NULL) {
				$mssg = 'User not found';
				$password = '';
				$username = '';
			} else if(md5($password) == $row['PASS']) {
				$_SESSION['user'] = $row['UID'];
				$token = bin2hex(random_bytes(25));
				$update_statement = $conn -> prepare('UPDATE login SET token = ? WHERE UID = ?');
				$update_statement -> bind_param('ss', $token, $row['UID']);
				$update_statement -> execute();
				setcookie('user', $row['UID'], time() + (86400 * 5), '/');
				setcookie('token', $token, time() + (86400 * 5), '/');
				header('Location: home.php');
			} else {
				$mssg = 'Incorrect Password';
				$password = '';
			}
			$select_statement -> close();
		}
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$conn -> close();
?>
<div class="heading">Login</div>
<form action="<?php echo $current_file ?>" method="POST">
	<div class="mssg formele <?php if(!empty($mssg)) echo 'errorcolor' ?>">&nbsp;<?php if(isset($mssg)) echo $mssg; ?></div>
	<div class="formele <?php if(!empty($username_error)) echo 'errorcolor' ?>">
		<input type="text" maxlength=40 name="username" placeholder="Enter username" autofocus="autofocus" value="<?php if(isset($username)) echo $username; ?>" />
		<div class="error">&nbsp;<?php if(isset($username_error)) echo $username_error; ?></div>
	</div>
	<div class="formele <?php if(!empty($password_error)) echo 'errorcolor' ?>">
		<input type="password" maxlength=40 name="password" placeholder="Enter password" value="<?php if(isset($password)) echo $password; ?>" />
		<div class="error">&nbsp;<?php if(isset($password_error)) echo $password_error; ?></div>
	</div>
	<div class="formele"><input type="submit" value="Login" /></div>
</form>