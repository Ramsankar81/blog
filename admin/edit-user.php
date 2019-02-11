<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit User</title>
  <link rel="stylesheet" href="../css/main.css">
  <?php include "../includes/linkStyle.html"?>
</head>
<body>
<?php include "navbar.html.php";?>
<div id="wrapper">
	<h2>Edit User</h2>


	<?php
	if(isset($_POST['submit'])){
		extract($_POST);
		if($username ==''){
			$error[] = 'Please enter the username.';
		}
		if( strlen($password) > 0){
			if($password ==''){
				$error[] = 'Please enter the password.';
			}
			if($passwordConfirm ==''){
				$error[] = 'Please confirm the password.';
			}
			if($password != $passwordConfirm){
				$error[] = 'Passwords do not match.';
			}
		}
		
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		if(!isset($error)){
			try {
				if(isset($password)){
					$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':memberID' => $memberID
					));
				} else {
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':memberID' => $memberID
					));
				}
				
				header('Location: users.php?action=updated');
				exit;
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		}
	}
	?>


	<?php
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}
		try {
			$stmt = $db->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID = :memberID') ;
			$stmt->execute(array(':memberID' => $_GET['id']));
			$row = $stmt->fetch(); 
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>

	<form action='' method='post'>
		<input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>'>

		<p><label>Username</label><br />
		<input type='text' name='username' value='<?php echo $row['username'];?>'></p>

		<p><label>Password (only to change)</label><br />
		<input type='password' name='password' value=''></p>

		<p><label>Confirm Password</label><br />
		<input type='password' name='passwordConfirm' value=''></p>

		<p><label>Email</label><br />
		<input type='text' name='email' value='<?php echo $row['email'];?>'></p>

		<p><input type='submit' name='submit' value='Update User'></p>

	</form>

</div>
<?php include "../includes/linkScript.html"?>
<?php include "footer.html.php"?>
</body>
</html>	
