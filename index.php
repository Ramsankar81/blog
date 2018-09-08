<?php require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Blog</title>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Comptible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="require/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

<!--  --------------------------------------MENU BAR--------------------------------------------  -->
		<div id="header">
				<nav>
					<div>
						<h1 id="header_title">
							BLOG
						</h1>
					</div>
					<ul id="menu" style="margin:15px;">
						<li><a href="/admin">Login</a></li>
					</ul>
				</nav>
			</div>
<!--  --------------------------------------MENU BAR--------------------------------------------  -->
	<div id="wrapper">
		<?php
			echo '<div class="grid">';
			try {
				$postPerPage = 10;
				if(!isset($_GET['page'])) $page = 0;
				else $page = $_GET['page'];
				$postNum = $page*$postPerPage;
				$stmt = $db->query("SELECT postID, postTitle, postDesc, postDate,postDescImage, postDescImageExt FROM blog_posts ORDER BY postID DESC LIMIT $postNum, $postPerPage");
				while($row = $stmt->fetch()){	
					echo '<div class="post" onclick="linkViewpost('.$row['postID'].');"><div class="content">';
						//echo '<h1>'.$row['postTitle'].'</h1>';
						//echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).'</p>';
						echo '<img class="postDescImage" src="/images/'.$row['postDescImage'].$row['postDescImageExt'].'">';		
						echo '<h1>'.$row['postTitle'].'</h1>';
					echo '</div></div>';
				}
			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
			echo "</div>";
			$stmt = $db->query("SELECT COUNT(postID) FROM blog_posts");
			$result = $stmt->fetch();
			$totalRows = $result[0];
			$postNum += $postPerPage;
		?>
		<br>
		<?php 
			if($postNum < $totalRows){$tempPage = $page + 1; echo "<a href = index.php?page=$tempPage>NEXT</a><br><br><br>";}
			if($postNum > $postPerPage){$tempPage = $page -1; echo "<a href = index.php?page=$tempPage>PREVIOUS</a><br><br><br>";}
		?>
	</div>
	
	<script>
		function linkViewpost(x){
			window.location.href="viewpost.php?id="+x;
		}
		function resizeGridItem(post){
			grid = document.getElementsByClassName("grid")[0]; 
			rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows'));  
			rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap'));
			rowSpan = Math.ceil((post.querySelector('.content').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap))+1;
			post.style.gridRowEnd = "span "+rowSpan;
		}

		function resizeAllGridItems(){
			allItems = document.getElementsByClassName("post");
			for(x=0;x<allItems.length;x++){
				resizeGridItem(allItems[x]);
			}
		}

		function resizeInstance(instance){
			post = instance.elements[0];
			resizeGridItem(item);
		}

		window.onload = resizeAllGridItems();
		window.addEventListener("resize", resizeAllGridItems);

		allItems = document.getElementsByClassName("post");
		for(x=0;x<allItems.length;x++){
			imagesLoaded( allItems[x], resizeInstance);
		}
	</script>

	<script src = "require/jquery.js"></script>
	<script src = "require/js/bootstrap.min.js"></script>
</body>
</html>
