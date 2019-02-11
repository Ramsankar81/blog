<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Blog Name</title>

	<?php include "./Includes/linkStyle.html";?>
	<!--WOW js animate-->
	<link type="text/css" rel="stylesheet" href="css/animate.css">

</head>

<body>
	<!-- HEADER -->
	<header id="header">
		<!-- NAV -->
		<?php include "navbar.html.php";?>
	</header>
	<!-- /HEADER -->

	<!-- SECTION -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div id="hot-post" class="row hot-post">
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 hot-post-left wow fadeInDown" data-wow-delay=".5s" >
					<!-- post -->
					<?php if(!empty($hots[0])):?>
					<div class="post post-thumb">
						<a class="post-img" href="viewpost.php?id=<?php echo $hots[0]['postID']."&".$_SERVER['QUERY_STRING']?>"><img src="/images/<?php echo $hots[0]['postDescImage'].'/'.$hots[0]['postDescImage'].$hots[0]['postDescImageExt']?>" alt=""></a>
						<div class="post-body">
							<h3 class="post-title title-lg"><a href="viewpost.php?id=<?php echo $hots[0]['postID']."&".$_SERVER['QUERY_STRING'];?>"><?php echo $hots[0]['postTitle']?></a></h3>
							<ul class="post-meta">
								<li><?php echo $hots[0]['postDate']?></li>
							</ul>
						</div>
					</div>
					<?php endif;?>
					<!-- /post -->
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 hot-post-right">
					<!-- post -->
					<?php if(!empty($hots[1])):?>
					<div class="post post-thumb wow fadeInRight" data-wow-delay=".5s" >
						<a class="post-img" href="viewpost.php?id=<?php echo $hots[1]['postID']."&".$_SERVER['QUERY_STRING'];?>"><img src="/images/<?php echo $hots[1]['postDescImage'].'/'.$hots[1]['postDescImage'].$hots[1]['postDescImageExt']?>" alt=""></a>
						<div class="post-body">
							<h3 class="post-title"><a href="viewpost.php?id=<?php echo $hots[1]['postID']."&".$_SERVER['QUERY_STRING'];?>"><?php echo $hots[1]['postTitle']?></a></h3>
							<ul class="post-meta">
								<li><?php echo $hots[1]['postDate']?></li>
							</ul>
						</div>
					</div>
					<?php endif;?>
					<!-- /post -->

					<!-- post -->
					<?php if(!empty($hots[2])):?>
					<div class="post post-thumb wow fadeInUp" data-wow-delay=".5s">
						<a class="post-img" href="viewpost.php?id=<?php echo $hots[2]['postID']."&".$_SERVER['QUERY_STRING'];?>"><img src="/images/<?php echo $hots[2]['postDescImage'].'/'.$hots[2]['postDescImage'].$hots[2]['postDescImageExt']?>" alt=""></a>
						<div class="post-body">
							<h3 class="post-title"><a href="viewpost.php?id=<?php echo $hots[2]['postID']."&".$_SERVER['QUERY_STRING'];?>"><?php echo $hots[2]['postTitle']?></a></h3>
							<ul class="post-meta">
								<li><?php echo $hots[2]['postDate']?></li>
							</ul>
						</div>
					</div>
					<?php endif;?>
					<!-- /post -->
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /SECTION -->

	<!-- SECTION -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<div class="col-md-8">
					<!-- row -->
					<div class="row">
						<div class="col-md-12">
							<div class="section-title">
								<h2 class="title">Posts</h2>
							</div>
						</div>
						<!-- post -->
						<?php if(!empty($posts)):?>
						<?php $countPosts = count($posts); if($countPosts == 11) $countPosts--; for($i=0;$i<$countPosts;$i++):?>
						<?php $post = $posts[$i];if($i%2 == 0) echo '<div class="clearfix visible-md visible-lg"></div>';?>
						<div class="col-xs-6 col-sm-6 col-md-6 wow fadeInUp" data-wow-delay="0.5s" >
							<div class="post">
								<?php if(!empty($post['postDescImage']) && !empty($post['postDescImageExt'])):?>
									<a class="post-img thumb-post-img" href="viewpost.php?id=<?php echo $post['postID']?>"><img src="/images/<?php echo $post['postDescImage'].'/'.$post['postDescImage'].$post['postDescImageExt']?>"></a>
								<?php endif;?>
								<div class="post-body">
									<h3 class="post-title"><a href="viewpost.php?id=<?php echo $post['postID']?>"><?php echo $post['postTitle'];?></a></h3>
									<ul class="post-meta">
										<li><?php echo $post['postDate'];?></li>
									</ul>
								</div>
							</div>
						</div>
						<?php endfor;?>
						
						<?php else:?>
							<div class="col-xs-12 col-sm-12 col-md-12" style="position:relative;margin:10px;">
								<center><i class="fa fa-ban" style="font-size:250px;color:rgba(255,40,40,0.5);"></i><br>
								<b style="position:absolute;top:110px;left:20%">No posts exists in this Category yet.Sorry for inconvenience.</b>
								</center>
							</div>
						<?php endif;?>
						<div class="clearfix visible-md visible-lg"></div>
						<?php
							if(!empty($posts)){
								echo '<div class="row"><div class="col-md-6">';
								if($postNum+$postPerPage > $postPerPage){$tempPage = $page -1; echo "<a href = index.php$query&page=$tempPage><i class='fa fa-chevron-circle-left' style='font-size:100px;'></i></a>";}
								echo '</div><div class="col-md-6" style="text-align:right;">';
								if(count($posts) > $postPerPage){$tempPage = $page + 1;echo "<a href = index.php$query&page=$tempPage><i class='fa fa-chevron-circle-right' style='font-size:100px;'></i></a>";}
								echo '</div></div>';
							}
						?>
						</div>
					<!-- /row -->
				</div>
				<div class="col-md-4">
				
				
					<!-- newsletter widget -->
					<?php include "./includes/newsletter.html.php"?>
					<!-- /newsletter widget -->

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
		<?php include "footer.html.php";?>
	<!-- /FOOTER -->

	<?php include "./includes/linkScript.html";?>
	<script>
		function linkViewpost(x){
			window.location.href="viewpost.php?id="+x+"<?php echo $_SERVER['QUERY_STRING'];?>";
		}
	</script>
	<script src="js/wow.min.js"></script>
	<script>
	new WOW().init();
	</script>
</body>

</html>
