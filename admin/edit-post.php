<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>

<?php
try{
	$postID = $_GET['id']; 
	$result = $db->query("SELECT categoryID FROM post_category WHERE postID = $postID");
}catch(PDOException $e){
	$error = "Error in fetching the list of Categories.";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	exit();	
}
foreach($result as $row){
	$selectedCategories[] = $row['categoryID'];
}
try{
	$result = $db->query("SELECT * FROM categories");
}catch(PDOException $e){
	$error = "Error in fetching the list of Categories.";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	exit();	
}
if(!empty($selectedCategories))
	foreach($result as $row){
		$categories[]  = array('id'=>$row['categoryID'], 'name'=>$row['categoryName'], 'selected'=>in_array($row['categoryID'], $selectedCategories));
	}
else
	foreach($result as $row){
		$categories[]  = array('id'=>$row['categoryID'], 'name'=>$row['categoryName'], 'selected'=>FALSE);
	}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit Post</title>
  <link rel="stylesheet" href="../css/main.css">
  <?php include "../includes/linkStyle.html"?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste",
				  "image code"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | image code",
			  images_upload_url: 'upload.php',
			
				images_upload_handler: function (blobInfo, success, failure) {
					var xhr, formData;
      
					xhr = new XMLHttpRequest();
					xhr.withCredentials = false;
					xhr.open('POST', 'upload.php');
      
					xhr.onload = function() {
					var json;
        
					if (xhr.status != 200) {
						failure('HTTP Error: ' + xhr.status);
						return;
					}
        
					json = JSON.parse(xhr.responseText);
        
					if (!json || typeof json.location != 'string') {
						failure('Invalid JSON: ' + xhr.responseText);
						return;
					}
        
				success(json.location);
				};
      
			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());
      
			xhr.send(formData);
			},

			
          });
  </script>
</head>
<body>
<?php include('navbar.html.php');?>
<div id="wrapper">
	<h2>Edit Post</h2>
	<?php
	if(isset($_POST['submit'])){
		$postTitle=$_POST['postTitle'];
		$postCont = $_POST['postCont'];
		$postDesc = $_POST['postDesc'];
		$postID = $_POST['postID'];
		if($postID ==''){
			$error[] = 'This post is missing a valid id!.';
		}
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}
		
		try{
				$stmt = $db->query("SELECT postDescImage,postDescImageExt FROM blog_posts WHERE postID = $postID");
				$result = $stmt->fetch();
				$filename = $result[0];
				$fileExt = $result[1];
			}catch(PDOException $e){
				$error = "Error in acessing Images";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
		if($_FILES['postDescImage']['size'] > 0 && !isset($error)){
			if (preg_match('/^image\/p?jpeg$/i', $_FILES['postDescImage']['type']))
			{
				$ext = '.jpg';
			}
			else if (preg_match('/^image\/gif$/i', $_FILES['postDescImage']['type']))
			{
				$ext = '.gif';
			}
			else if (preg_match('/^image\/(x-)?png$/i', $_FILES['postDescImage']['type']))
			{
				$ext = '.png';
			}
			else
			{
				$ext = '.unknown';
				$error[] = "Please check the extension of  description image.";
			}
			if(!isset($filename)) $filename = time();
			$fileExt = $ext;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $filename . '/' . $filename. $fileExt;
			if (!is_uploaded_file($_FILES['postDescImage']['tmp_name']) or !copy($_FILES['postDescImage']['tmp_name'], $filepath))
			{
				$error = "Could not save file as $filename!";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
		}
		
		
		
		
		if(!isset($error)){
			try {
				$stmt = $db->prepare('UPDATE blog_posts SET postTitle = :postTitle, postDesc = :postDesc, postCont = :postCont, postDescImage = :postDescImage, postDescImageExt = :postDescImageExt WHERE postID = :postID') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postDescImage' => $filename,
					':postDescImageExt' => $fileExt,
					':postID' => $postID
				));
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
			try{
				$result = $db->query("DELETE FROM post_category WHERE postID=$postID;");
			}
			catch(PDOException $e){
				$error = "Failed to update category list.";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
			if(isset($_POST['categories'])){
				try{
					$sql = "INSERT INTO post_category SET postID = :postID, categoryID = :categoryID";
					$stmt = $db->prepare($sql);
					foreach($_POST['categories'] as $categoryID){
						$stmt->bindValue(':postID',$postID);
						$stmt->bindValue(':categoryID',$categoryID);
						$stmt->execute();
					}
				}catch(PDOException $e){
					$error = "Failed to update category list.";
					include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					exit();
				}
			}
			header('Location: index.php?action=updated');
			exit;
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
			$stmt = $db->prepare('SELECT postID, postTitle, postDesc, postCont FROM blog_posts WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['id']));
			$row = $stmt->fetch(); 
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>

	<form action='' method='post' enctype="multipart/form-data">
		<input type='hidden' name='postID' value='<?php echo $row['postID'];?>'>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php echo $row['postTitle'];?>'></p>
		
		<p><label>Description Image (Upload only if you want to replace the Description Image.)</label><br />
		<input type="file" name='postDescImage'><br></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php echo $row['postDesc'];?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php echo $row['postCont'];?></textarea></p>
		
		<p>
			<label>Categories:</label><br>
			<?php foreach($categories as $category):?>
				<p>
					<label for="category<?php echo $category['id'];?>"><input type="checkbox" name="categories[]" id="category<?php echo $category['id'];?>" value="<?php echo $category['id'];?>" <?php if($category['selected']) echo "checked";?>><?php echo $category['name'];?></label>
				</p>
			<?php endforeach;?>
		</p>

		<p><input type='submit' name='submit' value='Update'></p>

	</form>

</div>
<?php include "../includes/linkScript.html"?>
<?php include "footer.html.php"?>
</body>
</html>	
