<?php
// core configuration
include_once "../../config/core.php";

// include classes

include_once "../../config/database.php";
include_once "../../libs/utils.php";
include_once "../../classes/Product.php";
include_once "../../classes/Message.php";
include_once "../../classes/Product_image.php";
include_once "../../classes/Category.php";
include_once '../../classes/Bids.php';
include_once '../../classes/Price.php';
include_once '../../classes/User.php';


// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare page object
$category = new Category($db);
$message = new Message($db);


// count unread message
$unread_message_count=$message->countUnread();




// set page title
$page_title="Messages";

// include page header HTML
include 'layout_head.php';

// get parameter values, and to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";
$search_term = isset($_GET['s']) ? $_GET['s'] : "";

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
		// tell the user he's already logged in
		if($action=='already_logged_in'){
			echo "<div class='alert alert-info'>";
				echo "<strong>You</strong> are already logged in.";
			echo "</div>";
		}

		// if a user tries to edit a page which he did not created
		else if($action=='no_page_edit'){
			echo "<div class='alert alert-info'>";
				echo "<strong>You</strong> cannot edit that page.";
			echo "</div>";
		}
	echo "</div>";
echo "</div>";

// query pages
$stmt = $message->read($from_record_num, $records_per_page);
$total_rows=$message->count();

// paging settings
$page_url="read_messages.php?";

// read products template
include_once 'read_messages_template.php';

// include page footer HTML
include_once 'layout_foot.php';
?>
