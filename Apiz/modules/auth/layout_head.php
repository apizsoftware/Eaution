<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
	
	<!-- set the page title, for seo purposes too -->
    <title><?php echo isset($page_title) ? strip_tags($page_title) : "eauction"; ?></title>

    <!-- Bootstrap CSS -->
	<link href="<?php echo $home_url; ?>src/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" media="screen">

	<!-- blue imp gallery CSS -->
	<link rel="stylesheet" href="<?php echo $home_url; ?>src/js/Bootstrap-Image-Gallery-3.1.1/css/blueimp-gallery.min.css">
	<link rel="stylesheet" href="<?php echo $home_url; ?>src/js/Bootstrap-Image-Gallery-3.1.1/css/bootstrap-image-gallery.min.css">

  

	<!-- custom CSS -->
	<link rel="stylesheet" href="<?php echo $home_url; ?>src/css/bidder/user.css" />

</head>
<body>

	<!-- include the navigation bar -->
	<?php //include_once 'navigation.php'; ?>

    <!-- container -->
    <div class="container">
		<div class="row">

		<?php
		// values for javascript access
		echo "<div id='home_url' class='display-none'>{$home_url}</div>";

		// if given page title is 'Login', do not display the title
		if($page_title!="Login"){
		?>
		<div class='col-md-12'>
			<div class="page-header">
				<h1><?php echo isset($page_title) ? $page_title : "eauction"; ?></h1>
			</div>
		</div>
		<?php
		}
		?>
