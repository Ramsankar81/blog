<?php
require_once('../includes/config.php');
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../css/main.css">
  <?php include "../includes/linkStyle.html"?>
</head>
<body class="formBody">
 
 
 <nav style="text-align:center;">
	<h1>BLOG NAME</h1>
	<hr/>
	<a href="../index.php"><i class="fa fa-home" style="font-size:30px;"></i></a>
 </nav>
 
 

<div id="login">
	<?php
	if(isset($_POST['submit'])){
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		if($user->login($username,$password)){ 
			header('Location: index.php');
			exit;
		} else {
			$message = '<p class="error">Wrong username or password</p>';
		}
	}
	if(isset($message)){ echo $message; }
	?>
	<form action="" method="post" id="login-form">
	<div><label>Username:</label><br><input type="text" name="username" value=""  required /></div>
	<div><label>Password:</label><br><input type="password" name="password" value="" required /></div><br>
	<div><label></label><input type="submit" name="submit" value="Login"  /></div>
	</form>
	<?php include "../includes/linkScript.html"?>
</div>
<?php include "footer.html.php"?>
</body>
</html>
