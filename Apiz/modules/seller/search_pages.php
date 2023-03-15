<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// include database and object files
include_once '../config/database.php';
include_once "../objects/category.php";
include_once '../objects/page.php';
include_once "../objects/message.php";
include_once "../objects/order.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare page object
$category = new Category($db);
$page_obj = new Page($db);
$message = new Message($db);
$order = new Order($db);

// set page title
$page_title="Pages";

// include page header HTML
include 'layout_head.php';

// get parameter values, and to prevent undefined index notice
$search_term = isset($_GET['s']) ? $_GET['s'] : "";

// query pages
$stmt = $page_obj->search($search_term, $from_record_num, $records_per_page);
$total_rows=$page_obj->countAll_BySearch($search_term);

// paging settings
$page_url="search_pages.php?s={$search_term}&";

// read products template
include_once 'read_pages_template.php';

// include page footer HTML
include_once 'layout_foot.php';
?>
