<?php
// core configuration
include_once "../../config/core.php";

// check if logged in as admin
include_once "../auth/login_checker.php";

// get ID of the product to be edited
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : die('Missing product ID.');

// include classes

include_once "../../config/database.php";
include_once "../../libs/utils.php";
include_once "../../classes/Product.php";
include_once "../../classes/User.php";
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


// initialize objects
$user = new User($db);


// set page title
$page_title = "Bidders";

// include page header HTML
include_once "layout_head.php";

// read all users from the database
$stmt = $product->readHighestBidderDetails($from_record_num, $records_per_page);

// count retrieved users
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_users.php?";

// include products table HTML template
include_once "read_users_template.php";

// include page footer HTML
include_once "layout_foot.php";
?>
