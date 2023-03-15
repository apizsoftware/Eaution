<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// include classes
include_once "../config/database.php";
include_once "../objects/category.php";
include_once "../objects/message.php";
include_once "../objects/variation.php";
include_once "../objects/order.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare category object
$category = new Category($db);
$message = new Message($db);
$variation = new Variation($db);
$order_obj = new Order($db);

// count unread message
$unread_message_count=$message->countUnread();

// count pending orders
$pending_orders_count=$order_obj->countPending();

// set page title
$page_title="Categories";

// include page header HTML
include_once "layout_head.php";

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 5; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query LIMIT clause

// read all categories from the database
$stmt=$category->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_categories.php?";

// include categories HTML table template
include_once "read_categories_template.php";

// include page footer HTML
include_once "layout_foot.php";
?>
