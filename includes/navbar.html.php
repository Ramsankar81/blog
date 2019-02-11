<div id="nav">
	<!-- Top Nav -->
	<div id="nav-top">
		<div class="container">
			<!-- social -->
			<ul class="nav-social">
				<li><a href="#"><i class="fa fa-facebook"></i></a></li>
				<li><a href="#"><i class="fa fa-twitter"></i></a></li>
				<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
				<li><a href="#"><i class="fa fa-instagram"></i></a></li>
			</ul>
			<!-- /social -->

			<!-- logo -->
			<div class="nav-logo">
				<a href="index.html" class="logo"><h1>BLOG NAME</h1></a>
			</div>
			<!-- /logo -->

			<!-- search & aside toggle -->
			<div class="nav-btns">
				<button class="aside-btn"><i class="fa fa-bars"></i></button>
				<button class="search-btn"><i class="fa fa-search"></i></button>
				<div id="nav-search">
					<form action="index.php" method="get">
						<input class="input" name="search" placeholder="Enter your search...">
					</form>
					<button class="nav-close search-close">
						<span></span>
					</button>
				</div>
			</div>
			<!-- /search & aside toggle -->
		</div>
	</div>
	<!-- /Top Nav -->

	<!-- Main Nav -->
	<div id="nav-bottom">
		<div class="container">
			<!-- nav -->
			<ul class="nav-menu">
				<li class="has-dropdown">
					<a href="index.php"><i class="fa fa-home"></i> Home</a>
					<div class="dropdown">
						<div class="dropdown-body">
							<ul class="dropdown-list">
								<li><a href="author.html">Author page</a></li>
								<li><a href="about.html">About Us</a></li>
								<li><a href="contact.html">Contacts</a></li>
							</ul>
						</div>
					</div>
				</li>
				<li class="has-dropdown">
					<a href="index.php"><i class="fa fa-list-alt"></i> Categories</a>
					<div class="dropdown">
						<div class="dropdown-body">
							<ul class="dropdown-list">
								<li><a href='?'>All</a></li>
								<?php foreach($categories as $category):?>
									<li><a href='index.php?category=<?php echo $category['categoryID'];?>'><?php echo $category['categoryName'];?></a></li>
								<?php endforeach?>
							</ul>
						</div>
					</div>
				</li>
				<li><a href="suggest.php">Suggest</a></li>
			</ul>
			<!-- /nav -->
		</div>
	</div>
	<!-- /Main Nav -->

	<!-- Aside Nav -->
	<div id="nav-aside">
		<ul class="nav-aside-menu">
			<li><a href="index.html">Home</a></li>
			<li class="has-dropdown"><a>Categories</a>
				<ul class="dropdown">
					<li><a href='?'>All</a></li>
						<?php foreach($categories as $category):?>
							<li><a href='index.php?category=<?php echo $category['categoryID'];?>'><?php echo $category['categoryName'];?></a></li>
						<?php endforeach?>
				</ul>
			</li>
			<li><a href="about.html">About Us</a></li>
			<li><a href="contact.html">Contacts</a></li>
		</ul>
		<button class="nav-close nav-aside-close"><span></span></button>
	</div>
	<!-- /Aside Nav -->
</div>