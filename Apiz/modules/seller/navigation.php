<!-- navbar -->
<div class="navbar navbar-dark bg-primary navbar-static-top" role="navigation">
	<div class="container">

		<div class="navbar-header">
			<!-- to enable navigation dropdown when viewed in mobile device -->
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>

			
		</div>

		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-left">
		<li <?php echo $page_title=="MyProducts" ? "class='active'" : ""; ?>>
					<a href="<?php echo $home_url; ?>modules/seller/read_products.php">My Products</a>
				</li>
		</ul>
			<!-- upper right corner options -->
			<ul class="nav navbar-nav navbar-right">
			<li <?php echo $page_title=="MyProducts" ? "class='active'" : ""; ?>>
					<a href="<?php echo $home_url; ?>modules/seller/read_bidded_products.php">My Bidded Products</a>
				</li>
			<!-- contact us page -->
			<li <?php echo $page_title=="Contact Us" ? "class='active'" : ""; ?>>
					<a href="<?php echo $home_url; ?>modules/seller/contact.php">Contact us</a>
				</li>
				<li <?php echo $page_title=="Edit Profile" || $page_title=="Orders" || $page_title=="Order Details" ? "class='active'" : ""; ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						&nbsp;&nbsp;<?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?>
						&nbsp;&nbsp;<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $home_url; ?>modules/auth/logout.php">Logout</a></li>
					</ul>
				</li>			
					
		</div><!--/.nav-collapse -->
    
	</div>
</div>
<!-- /navbar -->
