<?php

	// loop through list of retrieved products from the database
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

		echo "<div class='col-sm-6 col-md-4'>";

			echo "<div class='thumbnail'>";

				echo "<a href='{$home_url}modules/bidder/product.php?id={$row['id']}&price={$row['price']}' >";
					// related image files to a product
					$product_image->product_id=$row['id'];
					$stmt_product_image = $product_image->readFirst();
					$num_product_image = $stmt_product_image->rowCount();

					if($num_product_image>0){
						$row_product_image = $stmt_product_image->fetch(PDO::FETCH_ASSOC);
							$product_image_name = $row_product_image['name'];
							echo "<img src='../uploads/{$product_image_name}' class='w-100-pct' />";
							
					}else{
						echo "No images.";
					}
				echo "</a>";

				echo "<div class='caption'>";

					echo "<div class='display-none product-id'>{$row['id']}</div>";
					echo "<div class='display-none product-name'>{$row['name']}</div>";

					echo "<h3 title='{$row['name']}' class='product-title'>{$row['name']}</h3>";

					echo "<p>";

						$price->product_id=$row['id'];
						$price->readByProductId();
						//echo "Kshs" . number_format($price->price, 2, '.', ',');
						echo "Kshs." . number_format($price->price);
						echo " / ";
						echo "{$row['category_name']}";
						echo " / ";
						if($row['active_until']!="0000-00-00 00:00:00"){
							$date1 = new DateTime($row['active_until']);
							$date2 = new DateTime(date('Y-m-d'));
							$interval = $date1->diff($date2);

							echo $interval->days . " days left";
						}else{
							echo "Not set.";
						}
					echo "</p>";

				echo "</div>";

			echo "</div>";

		echo "</div>";

	}

// pagination
// the page where this paging is used
$page_dom ="";

// count of all records
$total_rows=0;

// count all products in the database to calculate total pages
if($page_title=='Product Search Results'){
	$page_dom = "search.php?s={$search_term}&";
	$total_rows = $product->countAll_BySearch($search_term);
}

// all products page
else if(strpos($page_title, 'Product')!==false && !isset($category_name)){
	$page_dom = "products.php?";
	$total_rows = $product->countAll();
}

// it's a product category page
else{
	$page_dom = "category.php?id={$category_id}&";
	$product->category_id=$category_id;
	$total_rows = $product->countAll_ByCategory();
}

// actual paging buttons
echo "<div class='col-sm-12 col-md-12'>";
include_once "paging.php";
echo "</div>";
?>
