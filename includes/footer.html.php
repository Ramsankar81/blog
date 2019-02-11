<footer id="footer">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			<div class="col-md-4">
				<div class="footer-widget">
					<div class="footer-logo">
						<a href="index.html" class="logo"><h1 style="color:white;">BLOG NAME</h1></a>
					</div>
					<p>About the Blog</p>
					<ul class="contact-social" style="font-size:40px;">
						<li><a href="#" class="social-facebook"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#" class="social-twitter"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#" class="social-google-plus"><i class="fa fa-google-plus"></i></a></li>
						<li><a href="#" class="social-instagram"><i class="fa fa-instagram"></i></a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-4">
				<div class="footer-widget">
					<h3 class="footer-title">Categories</h3>
					<div class="category-widget">
						<ul>
							<?php foreach($categories as $category):?>
								<li><a href='index.php?category=<?php echo $category['categoryID'];?>'><?php echo $category['categoryName'];?></a></li>
							<?php endforeach?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="footer-widget">
					<h3 class="footer-title">Newsletter</h3>
					<div class="newsletter-widget">
						<form>
							<p>Subscribe to our Newsletter to stay Updated about our posts.</p>
							<input class="input" name="newsletter" placeholder="Enter Your Email">
							<button class="primary-button">Subscribe</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- /row -->

		<!-- row -->
		<div class="footer-bottom row">
			<div class="col-md-6 col-md-push-6">
				<ul class="footer-nav">
					<li><a href="index.html">Home</a></li>
					<li><a href="about.html">About Us</a></li>
					<li><a href="contact.html">Contacts</a></li>
					<li><a href="#">Privacy</a></li>
					<li><a href="/admin/index.php" target="blank">Login</a></li>
				</ul>
			</div>
			<div class="col-md-6 col-md-pull-6">
				<div class="footer-copyright">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This blog is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by Azharuddin.
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</div>
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</footer>
