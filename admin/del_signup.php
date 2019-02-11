<?php
require_once('../includes/config.php');
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Signup</title>
  <link href="../require/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <link rel="stylesheet" href="../style/menu.css">
  <style>
	body{
		background:url(../images/cat.jpeg);
		background-size:cover;
	}
	@media (max-width:600px){
		body{
			background:url(../images/cat-mobile.jpg);
			background-size:cover;
		}
	}
  </style>
</head>
<body class="formBody">
<div>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#loginMenu" aria-expanded="false">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Blog Logo</a>
			</div>
			<div class="collapse navbar-collapse cssmenu" id="loginMenu">
				<ul class="nav navbar-nav">
					<li><a href="../index.php">Posts</a></li>
					<li><a href="login.php">Login</a></li>
					<li class="active"><a href="">Signup</a></li>
				</ul>
			</div>
		</div>
	</nav>
</div>

<div id="login">
<?php
	$username=$email="";
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
			try{
				$stmt = $db->query("SELECT COUNT(memberID) FROM blog_members WHERE username = '$username' OR email = '$email';");
				$result = $stmt->fetch();
				$count = $result[0];
				if($count > 0){
					$error[] = 'This Username or Email already exists.';
				}
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
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
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
			if($user->login($username,$password)){ 
				header('Location: index.php');
				exit;
			} else {
				$message = '<p class="error">Failed to login. Try again later.</p>';
			}
		}
	}
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
?>


	<form action="" method="post" id="login-form">
	<div><label>Username:</label><br><input type="text" name="username" value="<?php echo $username?>"  required /></div>
	<div><label>Email:</label><br><input type="email" name="email" value="<?php echo $email?>" required /></div>
	<div><label>Password:</label><br><input type="password" name="password" value="" required /></div>
	<div><label>Confirm Password:</label><br><input type="password" name="passwordConfirm" value="" required /></div><br>
	<div><label></label><input type="submit" name="submit" value="Signup"  /></div>
	</form>
	<script src = "../require/jquery.js"></script>
	<script src = "../require/js/bootstrap.min.js"></script>
</div>
</body>
</html>
