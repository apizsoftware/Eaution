<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// include database and object files
include_once '../config/database.php';
include_once "../objects/category.php";
include_once '../objects/message.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare page object
$category = new Category($db);
$message_obj = new Message($db);

// get parameter values, and to prevent undefined index notice
$search_term = isset($_GET['s']) ? $_GET['s'] : "";

// set page title
$page_title="Search Messages";

// include page header HTML
include 'layout_head.php';

// query pages
$stmt = $message_obj->search($search_term, $from_record_num, $records_per_page);
$total_rows=$message_obj->count_BySearch($search_term);

// paging settings
$page_url="search_messages.php?s={$search_term}&";

// read products template
include_once 'read_messages_template.php';

// include page footer HTML
include_once 'layout_foot.php';
?>
