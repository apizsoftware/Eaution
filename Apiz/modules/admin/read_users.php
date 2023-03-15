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

// initialize objects
$user = new User($db);
$category = new Category($db);
$message = new Message($db);


// count unread message
$unread_message_count=$message->countUnread();


// set page title
$page_title = "Users";

// include page header HTML
include_once "layout_head.php";

// read all users from the database
$stmt = $user->readAll($from_record_num, $records_per_page);

// count retrieved users
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_users.php?";

// include products table HTML template
include_once "read_users_template.php";

// include page footer HTML
include_once "layout_foot.php";
?>
