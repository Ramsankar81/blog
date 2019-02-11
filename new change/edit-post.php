<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit Post</title>
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
					xhr.open('POST', 'upload.php?folder=1540389760');
      
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
			if(!isset($filename)) $filename = 1540389760;
			$fileExt = $ext;
			$filepath = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $filename . '/' . $filename. $fileExt;
			if (!is_uploaded_file($_FILES['postDescImage']['tmp_name']) or !copy($_FILES['postDescImage']['tmp_name'], $filepath))
			{
				$error = "Could not save file as $filename!";
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
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
		</p>

		<p><input type='submit' name='submit' value='Update'></p>

	</form>

</div>
</body>
</html>	
