<?php
 if(!is_dir("../images/tempAzhar")) mkdir("../images/tempAzhar");
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
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
			$filename = time();
			$fileExt = $ext;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . '/images/tempAzhar/' . $filename. $fileExt;
			if (!is_uploaded_file($_FILES['postDescImage']['tmp_name']) or !copy($_FILES['postDescImage']['tmp_name'], $filepath))
			{
				$error = "Could not save file as $filename!";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
			rename("../images/tempAzhar","../images/$filename");
		}
		
		
		

		if(!isset($error)){
			$postCont = str_replace("/tempAzhar/","/$filename/",$postCont);
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
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $postTitle;}?>'></p>

		<p><label>Description Image</label><br />
		<input type="file" name='postDescImage'><br></p>
		
		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $postDesc;}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $postCont;}?></textarea></p>
		
		<p><input type='submit' name='submit' value='Submit'></p>
	</form>

</div>
