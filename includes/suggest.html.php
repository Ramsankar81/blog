<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add Post</title>
  <link rel="stylesheet" href="/css/main.css">
  <?php include "/includes/linkStyle.html"?>
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
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  image code",
			  images_upload_url: 'upload.php',
			
				images_upload_handler: function (blobInfo, success, failure) {
					var xhr, formData;
      
					xhr = new XMLHttpRequest();
					xhr.withCredentials = false;
					xhr.open('POST', '../admin/upload.php');
      
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
<?php include "/includes/navbar.html.php"?>
<div id="wrapper">
	<h2>Your Post Idea</h2>
	<?php if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}?>
	
	<form action='' method='post' enctype="multipart/form-data">
		<p><label>Your Name</label><br />
		<input type='text' name='authorName' value='' ></p>

		<p><label>Post Title</label><br />
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>' required></p>

		<p><label>Description Image</label><br />
		<input type="file" name='postDescImage' required><br></p>
		
		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10' required><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10' required><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>
		
		<p><input type='submit' name='submit' value='Submit'></p>
	</form>
</div>
<?php include "/includes/linkScript.html"?>
<?php include "footer.html.php"?>

	
	