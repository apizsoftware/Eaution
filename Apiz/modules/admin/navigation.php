<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
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
		
			<!-- upper right corner options -->
			<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Products <span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="<?php echo $home_url; ?>/modules/admin/read_products.php">All Products</a>
						</li>

						<?php
						// read all product categories
						$stmt=$category->readAll_WithoutPaging();
						$num = $stmt->rowCount();

						if($num>0){
							while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

								// highlight if the currenct $category_name is the same as the current category name in the loop
								if(isset($category_name) && $category_name==$row['name']){
									echo "<li><a href='{$home_url}modules/admin/category.php?id={$row['id']}'>{$row['name']}</a></li>";
								}

								// no highlight
								else{
									echo "<li><a href='{$home_url}modules/admin/category.php?id={$row['id']}'>{$row['name']}</a></li>";
								}
							}
						}
						?>
					</ul>
				</li>

			<li <?php echo $page_title=="MyProducts" ? "class='active'" : ""; ?>>
					<a href="<?php echo $home_url; ?>modules/admin/read_messages.php">Messages</a>
				</li>
			<!-- contact us page -->
			<li <?php echo $page_title=="Users" ? "class='active'" : ""; ?>>
					<a href="<?php echo $home_url; ?>modules/admin/read_users.php">Users</a>
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
