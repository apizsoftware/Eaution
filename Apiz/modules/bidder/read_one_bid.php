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

$page_title = "New Bidding Price";

// include page header HTML
include_once "layout_head.php";

echo "<div class='col-md-12'>";
  $product_id=isset($_GET['id']) ? $_GET['id'] : "500";
	$bid->product_id=$product_id;
	$bid->readOneByBidId();
	
// if the login form was submitted
if($_POST){
	
	// create the user
	if($_POST['newbidprice'] <=  $_GET['price']){
		echo "<div class=\"alert alert-warning\">";
		echo "Your new bidding price should be higher than the highest bidding price!";
		echo "</div>";
	}
	else{
		$bid->bidding_price = $_POST['newbidprice'];
		$bid->product_id = $_GET['product_id'];
		// read user record base on given id
	

		if($bid->updateBid()){
			echo "<div class=\"alert alert-success\">";
			echo "Your bidding price was updated successfuly!";
			echo "</div>";
			
			}
	
			else{
				echo "<div class=\"alert alert-danger\">";
				echo "Failed to update bid.</a>";
				echo "</div>";
			}
	}

}
?>
	<!-- display order summary / details -->
	<h4>Highest Bid for this product</h4>
 
	
	<?php
	 echo "<h5>{$bid->bidding_price}</h5>";
	 echo "<form action='read_one_bid.php?product_id={$product_id}&price={$bid->bidding_price}'  method='post'>
	 <label for='newbid'>New bidding price:</label>
	 <input type='number' class='form-group' name='newbidprice' id='newbid'>
	 <input type='submit' class='btn btn-primary' value='Update Bid Price' name='newprice'>
	 </form>
";

	echo "</div>";

// include page footer HTML
include_once "layout_foot.php";
?>
