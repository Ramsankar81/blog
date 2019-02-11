<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add User</title>
   <link rel="stylesheet" href="../css/main.css">
  <?php include "../includes/linkStyle.html"?>
</head>
<body>
<?php include "navbar.html.php";?>
<div id="wrapper">
	<h2>Add User</h2>

	<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if($password ==''){
			$error[] = 'Please enter the password.';
		}
		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}
		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){
			$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
			try {
				$stmt = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username, :password, :email)') ;
				$stmt->execute(array(
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email
				));
				header('Location: users.php?action=added');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post'>

		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

		<p><label>Password</label><br />
		<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>

		<p><input type='submit' name='submit' value='Add User'></p>

	</form>
	
</div>
<?php include "../includes/linkScript.html"?>
<?php include "footer.html.php"?>