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


$page_title="My Bids";

// include page header HTML
include 'layout_head.php';

echo "<div class='col-md-12'>";

	// set user id as order object property
	$bid->bidder_id=$_SESSION['user_id'];
	// echo ($_SESSION['user_id']);
	// exit;
	$bid->status=1;
	// to get orders under that user id
	$stmt=$bid->readAll_ByUser($from_record_num, $records_per_page);

	// count number or rows returned
	$num = $stmt->rowCount();
	// if count more than zero
	if($num>0){

		// display Bids table
		echo "<table class='table table-hover table-responsive'>";

			// our table heading
			echo "<tr>";
				echo "<th>Product Name</th>";
				echo "<th>Bidding Price</th>";
				echo "<th>Date</th>";
				echo "<th>Status</th>";
			echo "</tr>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// assigned values to object properties
		$name = $row['name'];
		$bid_id= $row['id'];
		$price = $row['price'];
		$pid = $row['pid'];
		$created = $row['created'];
		$status = $row['status'];
			 switch (	$status) {
				case 1:
					$bid_status="Open";
					break;
				case 0:
					$bid_status="Closed";
					break;
				}
				//creating new table row per record
				echo "<tr>";
					echo "<td>{$name}</td>";
					echo "<td>{$price}</td>";
					echo "<td>{$created}</td>";
					echo "<td>{$bid_status}</td>";
					echo "<td>";
					echo "<a href='read_one_bid.php?bid_id={$bid_id}&status={$status}&name={$name}&id={$pid}' class='btn btn-primary'>View Details</a>";
					echo "</td>";
				echo "</tr>";
			}

		echo "</table>";
	}

	// tell the user no bids made yet
	else{
		echo "<div class='alert alert-danger'>";
			echo "<strong>No bids found</strong>";
		echo "</div>";
	}

echo "</div>";

// include page footer HTML
include_once 'layout_foot.php';
?>
