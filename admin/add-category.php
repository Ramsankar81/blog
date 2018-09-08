<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<?php
	if(isset($_POST['add']) && !empty(stripslashes($_POST['newPost']))){
		try{
			$categoryName = $_POST['newPost'];
			$result = $db->query("INSERT INTO categories SET categoryName='$categoryName';");
		}catch(PDOException $e){
			$error = "Failed to update categories table: ".$e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
			exit();
		}
	}
	
	if(isset($_POST['delete']) && !empty($_POST['categories'])){
		foreach($_POST['categories'] as $category){
			try{
				$s=$db->query("DELETE FROM categories WHERE categoryID = $category");
			}catch(PDOException $e){
				$error = "Failed to update categories table.".$e->getMessage();
				include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
				exit();
			}
			try{
				$s = $db->query("DELETE FROM post_category WHERE categoryID = $category");
			}catch(PDOException $e){
				$error = "Failed to update categories table.".$e->getMessage();
				include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
				exit();
			}
		}
	}
?>

<?php
try{
	$result = $db->query("SELECT * FROM categories");
}catch(PDOException $e){
	$error = "Error in fetching the list of Categories.";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	exit();	
}
foreach($result as $row){
	$categories[]  = array('id'=>$row['categoryID'], 'name'=>$row['categoryName']);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Post Categories</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>
	<div id="wrapper">
		<?php include('menu.php');?>
		<br>
		<form method="post" action="">
			<div>
				<label>Add New Post Category:</label>
				<input type="text" name="newPost">
			</div>
			<div>
				<input type="submit" name="add" value="ADD">
			</div>
		</form>
		<br>
		<form method="post" action="">
			<label>Delete Post Categories:</label><br>
			<?php if(!empty($categories)):?>
			<?php foreach($categories as $category):?>
				<div>
					<label for="category<?php echo $category['id'];?>"><input type="checkbox" name="categories[]" id="category<?php echo $category['id'];?>" value="<?php echo $category['id'];?>"><?php echo $category['name'];?></label>
				</div>
			<?php endforeach;?>
			<?php endif;?>
			<br>
			<div>
				<input type="submit" name="delete" value="DELETE">
			</div>
		</form>
	</div>
</body>
</html>