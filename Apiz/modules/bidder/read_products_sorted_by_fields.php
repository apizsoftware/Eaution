<?php
// core configuration
include_once "config/core.php";

// include classes
include_once "libs/php/utils.php";
include_once "config/database.php";
include_once "objects/product.php";
include_once "objects/product_image.php";
include_once "objects/category.php";

// initialize utility class
$utils = new Utils();

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$product = new Product($db);
$product_image = new ProductImage($db);
$category = new Category($db);

// ount all products
$products_count=$product->countAll();

// given field and order
$field = isset($_GET['field']) ? $_GET['field'] : "";
$order = isset($_GET['order']) ? $_GET['order'] : "";

// set page title
$page_title="Products <small>{$products_count}</small>";

// include page header HTML
include_once 'layout_head.php';

// read all active products in the database
$stmt = $product->readAll_WithSorting($from_record_num, $records_per_page, $field, $order);

// count number of retrieved products
$num = $stmt->rowCount();

// tell the template it is field sort
$field_sort=true;

// to identify page for paging
$page_url="read_products_sorted_by_fields.php?field={$field}&order={$order}&";

// if products retrieved were more than zero
if($num>0){
	
	// display the list of products
	include_once "read_products_template.php";
}

// tell the user if there's no products in the database
else{
    echo "No products found.";
}

// footer HTML and JavaScript codes
include 'layout_foot.php';
?>