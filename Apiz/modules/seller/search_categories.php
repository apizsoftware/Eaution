<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// include classes
include_once '../config/database.php';
include_once '../objects/category.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize category object
$category = new Category($db);

// get search term
$search_term=isset($_GET['s']) ? $_GET['s'] : '';

// set page title
$page_title = "Category Search Results";

// include page header HTML
include_once "layout_head.php";

// search category based on search term
$stmt = $category->search($search_term, $from_record_num, $records_per_page);

// count number of categories returned
$num = $stmt->rowCount();

// to identify page for paging
$page_url="search_categories.php?s={$search_term}&";

// include categories table HTML template
include_once "read_categories_template.php";

// include page footer HTML
include_once "layout_foot.php";
?>