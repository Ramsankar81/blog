<?php require('includes/config.php'); 
	$postPerPage = 10;
	$query = "?";
	
	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = 0;
	
	$select = "SELECT *";
	$from = " FROM blog_posts";
	$where = " WHERE TRUE";
	$placeholders=array();
	
	if(isset($_GET['category'])){
		$from .= " INNER JOIN post_category ON blog_posts.postID = post_category.postID";
		$where .=" AND categoryID = :categoryID";
		$placeholders[':categoryID'] = $_GET['category'];
		$query = $query."&category=".$_GET['category'];
	}
	
	try{
		$sql = $select.$from.$where." ORDER BY blog_posts.postID DESC LIMIT 0,3";
		$stmt = $db->prepare($sql);
		$stmt->execute($placeholders);
	}catch(PDOException $e){
		$error = "Error in fetching the Posts: ".$e->getMessage();
		include $_SERVER['DOCUMENT_ROOT']."/includes/error.html.php";
		exit();
	}
	
	foreach($stmt as $row){
		$hots[] = array('postID'=>$row['postID'],'postTitle'=>$row['postTitle'],'postDesc'=>$row['postDesc'],'postDescImage'=>$row['postDescImage'],'postDescImageExt'=>$row['postDescImageExt'],'postDate'=>$row['postDate']);
	}
	
	if(isset($_GET['search'])){
		$where .= " AND postTitle LIKE :postTitle";
		$search = $_GET['search'];
		$placeholders[':postTitle'] = "%$search%";
		$query = $query."search=".$_GET['search'];
	}
	
	if(!isset($_GET['page'])) $page = 0;
	else $page = $_GET['page'];
	$postNum = $page*$postPerPage; 
	
	$order = " ORDER BY blog_posts.postID DESC";
	$extraPost = $postPerPage+1;
	$limit = " LIMIT $postNum,$extraPost";
	
	try{
		$sql = $select.$from.$where.$order.$limit;
		$stmt = $db->prepare($sql);
		$stmt->execute($placeholders);
	}catch(PDOException $e){
		$error = "Error in fetching the Posts";
		include $_SERVER['DOCUMENT_ROOT']."/includes/error.html.php";
		exit();
	}
	
	foreach($stmt as $row){
		$posts[] = array('postID'=>$row['postID'],'postTitle'=>$row['postTitle'],'postDesc'=>$row['postDesc'],'postDescImage'=>$row['postDescImage'],'postDescImageExt'=>$row['postDescImageExt'],'postDate'=>$row['postDate']);
	}
	
	try{
		$sql = "SELECT * FROM categories";
		$result = $db->query($sql);
	}catch(PDOException $e){
		$error = "Error in fetching categories.";
		include $_SERVER['DOCUMENT_ROOT']."/include/error.html.php";
		exit();
	}
	
	foreach($result as $row){
		$categories[] = array('categoryID'=>$row['categoryID'], 'categoryName'=>$row['categoryName']);
	}
	
	try{
		$sql = "SELECT postID,postDescImage,postDescImageExt,postViews,postTitle FROM blog_posts ORDER BY postViews DESC LIMIT 0,4";
		$result = $db->query($sql);
	}catch(PDOException $e){
		$error = "Error in fetching popular posts.";
		include $_SERVER['DOCUMENT_ROOT']."/include/error.html.php";
		exit();
	}
	
	foreach($result as $row){
		$populars[] = array('id'=>$row['postID'],'image'=>$row['postDescImage'],'ext'=>$row['postDescImageExt'],'views'=>$row['postViews'], 'postTitle'=>$row['postTitle']);
	}
	include "includes/main.html.php";
	exit();