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

// get database connection
$database = new Database();
$db = $database->getConnection();


// get ID of the product to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// set the id as product id property
$product->id = $id;

// check if product is active
if(!$product->isActive()){
	// redirect
	header("Location: {$home_url}modules/bidder/products.php?action=product_inactive");
}

// to read single record product
$row = $product->readOne();

// set page title
$page_title = $product->name;

// include page header HTML
include_once 'layout_head.php';

// if the login form was submitted
if($_POST){

	

	$bid->product_id= $_GET['id'];
	$bid->bidder_id= $_SESSION['user_id'];
	$bid->bidding_price=$_POST['newbid'];
	$bid->status = 1;
	
	// create the user
	if($_POST['newbid'] <=  $_GET['price']){
		echo "<div class=\"alert alert-warning\">";
		echo "The bid should be higher than the initial price!";
		echo "</div>";
	}
	else{
		if($bid->placeBid()){
			echo "<div class=\"alert alert-success\">";
			echo "Your bid was placed successfuly!";
			echo "</div>";
			
			}
	
			else{
				echo "<div class=\"alert alert-danger\">";
				echo "Failed to placed bid.</a>";
				echo "</div>";
			}
	}

}

// set product id
$product_image->product_id=$id;

echo "<div class='col-md-4' id='product-img'>";

	// read all related product image
	$stmt_product_image = $product_image->readAll();
	$num_product_image = $stmt_product_image->rowCount();

	// if count is more than zero
	if($num_product_image>0){
		// loop through all product images
		
		$row = $stmt_product_image->fetch(PDO::FETCH_ASSOC);
			// image name and source url
			$product_image_name = $row['name'];
			$source="{$home_url}modules/uploads/{$product_image_name}";
			echo "<img src='{$source}' style='width:100%;' />";
			echo "</a>";
			
		
	}else{ echo "No images."; }
echo "</div>";

echo "<div class='col-md-5'>";

	$price->product_id=$id;
	$price->readByProductId();
  $price_id = $price->id;
	$price_p= $price->price;
	// echo $price_p;
	// exit;
	echo "<div class='product-detail'>Initial Price:</div>";
	echo "<h4 class='m-b-10px price-description'>Kshs." . number_format($price->price) . "</h4>";

	echo "<div class='product-detail'>Product description:</div>";
	echo "<div class='m-b-10px'>";
		// make html
		$page_description = htmlspecialchars_decode(htmlspecialchars_decode($product->description));

		// to show images
		$page_description = str_replace("../src/js/", "{$home_url}src/js/", $page_description);

		// for internal links
		$page_description = str_replace("../", "{$home_url}", $page_description);

		// show to user
		echo $page_description;
	echo "</div>";

	echo "<div class='product-detail'>Product category:</div>";
	echo "<div class='m-b-10px'>{$product->category_name}</div>";

echo "</div>";

echo "<div class='col-md-2'>";
echo "<form action='product.php?id={$_GET['id']}&price={$_GET['price']}'  method='post'>
		<label for='newbid'>Your bidding price:</label>
		<input type='number' class='form-group' name='newbid' id='newbid'>
		<input type='submit' class='btn btn-primary' value='Place Bid' name='bid'>
		</form>




";
	

echo "</div>";
?>

	<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
	<div id="blueimp-gallery" class="blueimp-gallery">
		<!-- The container for the modal slides -->
		<div class="slides"></div>
		<!-- Controls for the borderless lightbox -->
		<h3 class="title"></h3>
		<a class="prev">&#9668;</a>
		<a class="next">&#9658;</a>
		<a class="close">X</a>
		<a class="play-pause"></a>
		<ol class="indicator"></ol>
		<!-- The modal dialog, which will be used to wrap the lightbox content -->
		<div class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" aria-hidden="true">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body next"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left prev">
							<i class="glyphicon glyphicon-chevron-left"></i>
							Previous
						</button>
						<button type="button" class="btn btn-primary next">
							Next
							<i class="glyphicon glyphicon-chevron-right"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
echo "</div>";

// include page footer HTML
include_once 'layout_foot.php';
?>
