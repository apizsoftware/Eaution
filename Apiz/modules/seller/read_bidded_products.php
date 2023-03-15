<?php
// core configuration
include_once "../../config/core.php";

// include classes

include_once "../../config/database.php";
include_once "../../libs/utils.php";
include_once "../../classes/Product.php";
include_once "../../classes/Product_image.php";
include_once "../../classes/Category.php";
include_once '../../classes/Bids.php';
include_once '../../classes/Price.php';



// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$product = new Product($db);
$utils = new Utils($db);
$product_image = new ProductImage($db);
$category = new Category($db);
$bid = new Bid($db);
$price = new Price($db);

// set page title
$page_title="Bidded Products";

// include page header HTML
include 'layout_head.php';

// get parameter values, and to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='row'>";
	echo "<div clas='col-md-12'>";
		// tell the user he's already logged in
		if($action=='already_logged_in'){
			echo "<div class='alert alert-info'>";
				echo "<strong>You</strong> are already logged in.";
			echo "</div>";
		}

		else if($action=='logged_in_as_admin'){
			echo "<div class='alert alert-info'>";
				echo "<strong>You</strong> are logged in as admin.";
			echo "</div>";
		}
	echo "</div>";
echo "</div>";

// read all active products in the database
$stmt=$product->readMyBids($from_record_num, $records_per_page);

// count number of products returned
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_products.php?";




// include products table HTML template
include_once "bidded_products_template.php";

// include page footer HTML
include_once 'layout_foot.php';
?>
