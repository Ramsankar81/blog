<?php
	require_once('/includes/config.php');

	try{
		$result = $db->query("SELECT * FROM categories");
	}catch(PDOException $e){
		$error = "Error in fetching the list of Categories.";
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
		exit();	
	}
	foreach($result as $row){
		$categories[]  = array('categoryID'=>$row['categoryID'], 'categoryName'=>$row['categoryName']);
	}
	if(!is_dir("./images/tempAzhar")) mkdir("./images/tempAzhar");

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
			rename("./images/tempAzhar","./images/$filename");
		}
	
		if(!isset($error)){
			$postCont = str_replace("/tempAzhar/","/$filename/",$postCont);
			try {
				$stmt = $db->prepare('INSERT INTO suggestions (postTitle,postDesc,postCont,authorName,postDescImage,postDescImageExt) VALUES (:postTitle, :postDesc, :postCont, :authorName, :postDescImage, :postDescImageExt)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':authorName' => $_POST['authorName'],
					':postDescImage'=>$filename,
					':postDescImageExt'=>$fileExt
				));
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
			header("Location:index.php");
		}
	}
	include "./includes/suggest.html.php";
	exit();

