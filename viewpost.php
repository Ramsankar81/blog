<?php require('includes/config.php'); 

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


$stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate,username,postDesc,postDescImage,postDescImageExt,postViews FROM blog_posts INNER JOIN blog_members ON authorID = memberID WHERE postID = :postID');
$stmt->execute(array(':postID' => $_GET['id']));
$row = $stmt->fetch();
if($row['postID'] == ''){
	header('Location: ./');
	exit;
}
?>
<?php
	$uri="";
	if(isset($_GET['page'])) $uri .= '&page='.$_GET['page'];
	if(isset($_GET['category'])) $uri .= '&category='.$_GET['category'];
?>

<?php
try{
	$sql = "SELECT * FROM categories";
	$result = $db->query($sql);
}catch(PDOException $e){
	$error = "Error in fetching categories.";
	include $_SERVER['DOCUMENT_ROOT']."/include/error.html.php";
	exit();
}

foreach($result as $r){
	$categories[] = array('categoryID'=>$r['categoryID'], 'categoryName'=>$r['categoryName']);
}

?>

<?php
$views = $row['postViews'];
$views++;
try{
	$sql = "UPDATE blog_posts SET postViews = $views WHERE postID = ".$row['postID'];
	$s = $db->query($sql);
}catch(PDOException $e){}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Blog - <?php echo $row['postTitle'];?></title>
	<?php include "includes/linkStyle.html";?>
	
</head>

<body>
	<!-- HEADER -->
	<header id="header">
		<?php include "./includes/navbar.html.php";?>

		<!-- PAGE HEADER -->
		<div id="post-header" class="page-header">
			<div class="page-header-bg" style="background-image: url('./images/<?php echo $row['postDescImage'].'/'.$row['postDescImage'].$row['postDescImageExt']?>');" data-stellar-background-ratio="0.5"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-10">
						<h1><?php echo $row['postDesc']?></h1>
						<ul class="post-meta">
							<li><a href="author.html"><?php echo $row['username']?></a></li>
							<li><?php echo $row['postDate']?></li>
							<li><i class="fa fa-eye"></i> <?php echo $views?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /PAGE HEADER -->
	</header>
	<!-- /HEADER -->

	<!-- section -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<div class="col-md-8">
					<!-- post share -->
					<div class="section-row">
						<div class="post-share">
							<a href="#" class="social-facebook"><i class="fa fa-facebook"></i><span>Share</span></a>
							<a href="#" class="social-twitter"><i class="fa fa-twitter"></i><span>Tweet</span></a>
							<a href="#" class="social-pinterest"><i class="fa fa-pinterest"></i><span>Pin</span></a>
							<a href="#" ><i class="fa fa-envelope"></i><span>Email</span></a>
						</div>
					</div>
					<!-- /post share -->

					<!-- post content -->
					<div class="section-row">
						<?php echo $row['postCont']?>
					</div>
					<!-- /post content -->

					<!-- post nav -->
					<div class="section-row">
						<div class="post-nav">
							<div class="prev-post">
								<a href="viewpost.php?id=<?php echo ($row['postID']-1)?>"><span style="font-size:15px;cursor:pointer">Previous post</span></a>
							</div>

							<div class="next-post">
								<a href="viewpost.php?id=<?php echo ($row['postID']+1)?>"><span style="font-size:15px;cursor:pointer">Next post</span></a>
							</div>
						</div>
					</div>
					<!-- /post nav  --->
					<!-- post author -->
					<div class="section-row">
						<div class="section-title">
							<h3 class="title">About <a href="author.html"><?php echo $row['username']?></a></h3>
						</div>
						<div class="author media">
							<div class="media-left">
								<a href="author.html">
									<img class="author-img media-object" src="./images/admin.jpg" alt="">
								</a>
							</div>
							<div class="media-body">
								<p>About the Administrator of the blog </p>
								<ul class="author-social">
									<li><a href="#"><i class="fa fa-facebook"></i></a></li>
									<li><a href="#"><i class="fa fa-twitter"></i></a></li>
									<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
									<li><a href="#"><i class="fa fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /post author -->
				</div>
				<div class="col-md-4">

					<?php include "./includes/newsletter.html.php"?>
					<!-- post widget -->
					<div class="aside-widget">
						<div class="section-title">
							<h2 class="title">Popular Posts</h2>
						</div>
							<?php foreach($populars as $popular):?>
						
								<div class="post post-widget">
									<a class="post-img" href="viewpost.php?id=<?php echo $popular['id']?>"><img src="./images/<?php echo $popular['image'].'/'.$popular['image'].$popular['ext'];?>" width="100%" height="90"alt=""></a>
									<div class="post-body">
										<div class="post-category">
											<i class="fa fa-eye"></i>
											<a><?php echo $popular['views'];?></a>
										</div>
										<h3 class="post-title"><a href="viewpost.php?id=<?php echo $popular['id']?>"><?php echo $popular['postTitle']?></a></h3>
									</div>
								</div>
						
							<?php endforeach;?>
					</div>
					<!-- /post widget -->
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /SECTION -->

	<!-- FOOTER -->
		<?php include "./includes/footer.html.php";?>
	<!-- /FOOTER -->

	<?php include "./includes/linkScript.html";?>

</body>

</html>
