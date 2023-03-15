<?php
// core configuration
include_once "config/core.php";

// set page title
$page_title="Order Cancelled";

// include login checker
include_once "login_checker.php";

// include classes
include_once "config/database.php";
include_once "objects/product.php";
include_once "objects/category.php";
include_once "objects/user.php";
include_once "objects/order.php";
include_once "objects/order_item.php";
include_once 'objects/cart_item.php';
include_once 'objects/page.php';
include_once 'objects/variation.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$product = new Product($db);
$category = new Category($db);
$user = new User($db);
$order = new Order($db);
$order_item = new OrderItem($db);
$cart_item = new CartItem($db);
$page_obj = new Page($db);
$variation = new Variation($db);

// include page header HTML
include_once 'layout_head.php';

echo "<div class='col-md-12'>";

	// remove cart items
	$cart_item->user_id=$_SESSION['user_id'];
	$cart_item->deleteAllByUser();

	// tell the user order has been placed
	echo "<div class='alert alert-success'>";
		echo "<strong>Your order has been cancelled.</strong> We hope to see you again.";
	echo "</div>";

echo "</div>";

// include page footer HTML
include_once 'layout_foot.php';
?>
