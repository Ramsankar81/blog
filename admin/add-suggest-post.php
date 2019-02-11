<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
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
$postID = $_GET['id'];
try{
	$result = $db->query("SELECT * FROM suggestions WHERE postID=$postID;");
}catch(PDOException $e){
	$error = "Error in fetching the suggestion.";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
	exit();	
}
$post = $result->fetch();
$postTitle=$post['postTitle'];
$postCont = $post['postCont'].'<br>Special Thanks to '.$post['authorName'].' for Contribution';
$postDesc = $post['postDesc'];
$folder=$post['postDescImage'];
$filename=$folder;
$fileExt=$post['postDescImageExt'];
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
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
					xhr.open('POST', 'upload.php?folder=<?php echo $folder;?>');
      
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
<?php include "navbar.html.php"?>
<div id="wrapper">
	<h2>Add Post</h2>

	<?php
	if(isset($_POST['submit'])){
		$postTitle=$_POST['postTitle'];
		$postCont = $_POST['postCont'];
		$postDesc = $_POST['postDesc'];
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if($postDesc ==''){
			$error[] = 'Please enter the Description.';
		}
		if($postCont ==''){
			$error[] = 'Please enter the content.';
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
				$ext = '.unknown';$error[] = "Please check the extension of  description image.";
			}
			$filename = $folder;
			$fileExt = $ext;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . '/images/'.$folder.'/' . $filename. $fileExt;
			if (!is_uploaded_file($_FILES['postDescImage']['tmp_name']) or !copy($_FILES['postDescImage']['tmp_name'], $filepath))
			{
				$error = "Could not save file as $filename!";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
		}
		
		
		

		if(!isset($error)){
			try{
				$sql = "DELETE FROM suggestions WHERE postID = $postID";
				$db->query($sql);
				
			}catch(PDOException $e){
				$error = "Error in deleting suggestion in database.";
					include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					exit();
			}
			$postCont = str_replace("/tempAzhar/","/$filename/",$postCont);
			try {
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate,authorID,postDescImage,postDescImageExt) VALUES (:postTitle, :postDesc, :postCont, :postDate, :authorid, :postDescImage, :postDescImageExt)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s'),
					':authorid' => $_SESSION['memberID'],
					':postDescImage'=>$filename,
					':postDescImageExt'=>$fileExt
				));
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
			$postID = $db->lastInsertId();
			if(isset($_POST['categories'])){
				try{
					$sql = "INSERT INTO post_category SET
							postID = :postID,
							categoryID = :categoryID;";
					$stmt = $db->prepare($sql);
					foreach($_POST['categories'] as $categoryID){
						$stmt->bindValue(':postID', $postID);
						$stmt->bindValue(':categoryID', $categoryID);
						$stmt->execute();
						header('Location: suggestions.php?action=added');
						exit();
				}
				}catch(PDOException $e){
					$error = "Error in inserting Categories in database.";
					include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
					exit();
				}
			}
		}
	}
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post' enctype="multipart/form-data">

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php  echo $postTitle;?>'></p>

		<p><label>Description Image</label><br />
		<input type="file" name='postDescImage'><br></p>
		
		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php echo $postDesc;?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php echo $postCont;?></textarea></p>
		
		<p>
			<label>Select Categories:</label><br>
			<?php foreach($categories as $category):?>
					<label for="category<?php echo $category['id'];?>"><input type="checkbox" name="categories[]" id="category<?php echo $category['id'];?>" value="<?php echo $category['id'];?>"><?php echo $category['name'];?></label><br>
			<?php endforeach;?>
		</p>
		<p><input type='submit' name='submit' value='Submit'></p>
	</form>

</div>
<?php include "../includes/linkScript.html"?>
<?php include "footer.html.php"?>
