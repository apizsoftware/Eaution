<?php
// core configuration
include_once "config/core.php";

// get database connection
include_once 'config/database.php';
include_once 'objects/page.php';
include_once "objects/category.php";
include_once 'objects/cart_item.php';
include_once "objects/product.php";
include_once 'libs/php/utils.php';

// get databae connection
$database = new Database();
$db = $database->getConnection();

// instantiate page object
$page_obj = new Page($db);
$category = new Category($db);
$product = new Product($db);
$cart_item = new CartItem($db);

// utilities
$utils = new Utils();

// get ID of the page to be edited
$slug = isset($_GET['slug']) ? $_GET['slug'] : die('ERROR: missing slug.');

// set ID property of page to be edited
$page_obj->slug = $slug;

// read the details of page to be edited
$page_obj->readOneBySlug();

// set page headers
$page_title = $page_obj->title;
$meta_description = $page_obj->meta_description;
$author="";
$page_url="{$home_url}page/{$page_obj->slug}";
$featured_image=$page_obj->featured_image;

include_once "layout_head.php";
?>

<?php
if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="Admin"){
	echo "<div class='col-md-12'>";
		echo "<a href='{$home_url}admin/update_page.php?id={$page_obj->id}' class='btn btn-primary btn-xs' id='edit-page'>";
			echo "<span class='glyphicon glyphicon-edit'></span> EDIT THIS PAGE";
		echo "</a>";
	echo "</div>";
}

// show page featured image
if(!empty($page_obj->featured_image)){
	echo "<div class='col-md-12'>";
		echo "<a href='{$home_url}images/" . htmlentities($page_obj->featured_image) . "' title='{$page_obj->title}' data-gallery>";
			echo "<img src='{$home_url}images/" . htmlentities($page_obj->featured_image) . "' id='featured-img' />";
		echo "</a>";
	echo "</div>";
}

echo "<div class='col-md-2'></div>";
echo "<div class='col-md-8'>";
	// show page body content
	echo "<div id='page-content'>";
		// make html
		$page_body = htmlspecialchars_decode(htmlspecialchars_decode($page_obj->body));

		// to show images
		$page_body = str_replace("../libs/js/", "{$home_url}libs/js/", $page_body);

		// for internal links
		$page_body = str_replace("../", "{$home_url}", $page_body);

		// show content to user
		echo $page_body;
	echo "</div>";

	echo "<div id='page-meta'>";
		echo "This page was created by {$page_obj->firstname} {$page_obj->lastname} on " . date('Y/m/d', strtotime($page_obj->created));
	echo "</div>";

	echo "<div id='share-page'>";
		echo "<span class='m-r-10px'>Share on</span>";

		echo "<a href='https://www.facebook.com/sharer/sharer.php?u={$page_url}' class='btn btn-primary m-r-10px' target='_blank'>";
			echo "<i class='fa fa-facebook'></i> Facebook";
		echo "</a>";

		echo "<a href='https://twitter.com/intent/tweet?text={$page_title}&url={$page_url}' class='btn btn-info m-r-10px' target='_blank'>";
			echo "<i class='fa fa-twitter'></i> Twitter";
		echo "</a>";

		echo "<a href='https://plus.google.com/share?url={$page_url}' class='btn btn-danger m-r-10px' target='_blank'>";
			echo "<i class='fa fa-google'></i> Google";
		echo "</a>";

	echo "</div>";
echo "</div>";
echo "<div class='col-md-2'></div>";

echo "<hr />";

include_once "layout_foot.php";
?>
