<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
if(isset($_GET['deluser'])){ 
	if($_GET['deluser'] !='1'){
		$stmt = $db->prepare('DELETE FROM blog_members WHERE memberID = :memberID') ;
		$stmt->execute(array(':memberID' => $_GET['deluser']));
		header('Location: logout.php');
		exit;
	}
} 
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Users</title>
  <link rel="stylesheet" href="../css/main.css">
  <?php include "../includes/linkStyle.html"?>
  <script language="JavaScript" type="text/javascript">
  function deluser(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
	  	window.location.href = 'users.php?deluser=' + id;
	  }
  }
  </script>
</head>
<body>
<?php include "navbar.html.php"?>
	<div id="wrapper">
	<?php 
	if(isset($_GET['action'])){ 
		echo '<h3>User '.$_GET['action'].'.</h3>'; 
	} 
	?>
	<h1>Users</h1>
	<hr/>
	<p><a href='add-user.php' style="color:red;">Add User</a></p>
	<table  style="font-size:15px;">
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Action</th>
	</tr>
	<?php
		try {
			$stmt = $db->query('SELECT memberID, username, email FROM blog_members ORDER BY username');
			while($row = $stmt->fetch()){
				
				echo '<tr>';
				echo '<td>'.$row['username'].'</td>';
				echo '<td>'.$row['email'].'</td>';
				?>
				<td>
					<?php if($_SESSION['memberID'] == $row['memberID'] || $_SESSION['memberID'] == 1):?>
					<a href="edit-user.php?id=<?php echo $row['memberID'];?>" style="color:red;">Edit</a> 
					<?php if($row['memberID'] != 1){?>
						| <a href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')" style="color:red;">Delete</a>
					<?php } ?>
					<?php endif;?>
				</td>

				<?php 
				echo '</tr>';
			}
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>
	</table>
</div>
<?php include "../includes/linkScript.html"?>
<?php include "footer.html.php"?>
</body>
</html>
