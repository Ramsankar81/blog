<?php
require_once('../includes/config.php');
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
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

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="./">Blog Admin Index</a></p>

	<h2>Add Post</h2>

	<?php
	if(isset($_POST['submit'])){
		$postTitle=$_POST['postTitle'];
		$postCont = $_POST['postCont'];
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		
		
		
		
		if($_FILES['postDesc']['size'] > 0){
			if (preg_match('/^image\/p?jpeg$/i', $_FILES['postDesc']['type']))
			{
				$ext = '.jpg';
			}
			else if (preg_match('/^image\/gif$/i', $_FILES['postDesc']['type']))
			{
				$ext = '.gif';
			}
			else if (preg_match('/^image\/(x-)?png$/i', $_FILES['postDesc']['type']))
			{
				$ext = '.png';
			}
			else
			{
				$ext = '.unknown';
			}
// The complete path/filename
			$filename = time() . $ext;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $filename;
// Copy the file (if it is deemed safe)
			if (!is_uploaded_file($_FILES['postDesc']['tmp_name']) or !copy($_FILES['postDesc']['tmp_name'], $filepath))
			{
				$error = "Could not save file as $filename!";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
		}
		
		
		
		
		
		
		
		
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}
		if(!isset($error)){
			try {
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate,authorID) VALUES (:postTitle, :postDesc, :postCont, :postDate, :authorid)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postDesc' => $filename,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s'),
					':authorid' => $_SESSION['memberID']
				));
				header('Location: index.php?action=added');
				exit();
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

	<form action='' method='post' enctype="multipart/form-data">

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

		<p><label>Description</label><br />
		<input type="file" name='postDesc'><br></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><input type='submit' name='submit' value='Submit'></p>

	</form>

</div>
