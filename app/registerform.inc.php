<?php
	$insert_statement = $conn -> prepare('INSERT INTO login (UID, PASS) VALUES (?, ?)');
	$insert_statement -> bind_param('ss', $username, $password_hash);
	$select_statement = $conn -> prepare('SELECT * FROM login WHERE UID = ?');
	$select_statement -> bind_param('s', $username);
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $password = $username_error = $password_error = $repassword_error = '';
		
		if(isset($_POST['username'])) {
			if (!empty($_POST['username'])) {
				$username = test_input($_POST['username']);
				$select_statement -> execute();
				$result = $select_statement -> get_result();
				$row = $result -> fetch_assoc();
				if($row) {
					$username_error = 'User already exists';
				}
			} else {
				$username_error = 'Username is required';
			}
		}

		if(isset($_POST['password'])) {
			if (!empty($_POST['password'])) {
				$password = test_input($_POST['password']);
				$password_hash = md5($_POST['password']);
			} else {
				$password_error = 'Password is required';
			}
		}

		if(isset($_POST['repassword'])) {
			if (!empty($_POST['repassword']) && !empty($_POST['password'])) {
				$repassword = test_input($_POST['repassword']);
				if($repassword !== $password) {
					$repassword_error = 'Passwords do not match';
				}
			} else {
				$repassword_error = 'Retype password';
			}
		}

		if($username_error == '' && $password_error == '' && $repassword_error == '') {
			$insert_statement -> execute();
			$mssg = 'Registration Successful';
		}
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$insert_statement -> close();
	$conn -> close();
?>
<div class="heading">Register</div>
<form action="<?php echo $current_file; ?>" method="POST">
	<div class="mssg formele <?php if(!empty($mssg)) echo 'successcolor' ?>">&nbsp;<?php if(isset($mssg)) echo $mssg; ?></div>
	<div class="formele <?php if(!empty($username_error)) echo 'errorcolor' ?>">
		<input type="text" maxlength=40 name="username" placeholder="Enter username *" autofocus="autofocus" value="<?php if(isset($username)) echo $username; ?>" />
		<div class="error">&nbsp;<?php if(isset($username_error)) echo $username_error; ?></div>
	</div>
	<div class="formele <?php if(!empty($password_error)) echo 'errorcolor' ?>">
		<input type="password" maxlength=40 name="password" placeholder="Enter password *" value="<?php if(isset($password)) echo $password; ?>" />
		<div class="error">&nbsp;<?php if(isset($password_error)) echo $password_error; ?></div>
	</div>
	<div class="formele <?php if(!empty($repassword_error)) echo 'errorcolor' ?>">
		<input type="password" maxlength=40 name="repassword" placeholder="Confirm password *" value="<?php if(isset($repassword)) echo $repassword; ?>" />
		<div class="error">&nbsp;<?php if(isset($repassword_error)) echo $repassword_error; ?></div>
	</div>
	<div class="formele"><input type="submit" value="Register" /></div>
</form>