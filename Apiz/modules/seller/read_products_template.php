<?php



// if the form was submitted
if($_POST){
	echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    
		if(isset($_POST['delete_product'])){
			$product_id = $_GET['id'];
			if($product->delete($product_id)){
		     
				echo "<div class='alert alert-success'>";
				echo "Product was deleted.";
				echo "</div>";
		}

			// if unable to update the product, tell the user
			else{
					echo "<div class='alert alert-danger'>";
					echo "Unable to delete product.";
					echo "</div>";
			}
			
    }
  }
?>


<!-- search product function -->
<div class="row">
	
	

	<!-- create product button -->
	<div class='col-md-9 pull-right'>
		<a href='create_product.php' class="btn btn-primary pull-right margin-bottom-1em">
			<span class="glyphicon glyphicon-plus"></span> Create Product
		</a>
	</div>
</div>

<?php
// if number of products returned is more than 0
if($num>0){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";

		// order opposite of the current order
		$reverse_order=isset($order) && $order=="asc" ? "desc" : "asc";

		// field name
		$field=isset($field) ? $field : "";

		// field sorting arrow
		$field_sort_html="";

		if(isset($field_sort) && $field_sort==true){
			$field_sort_html.="<span class='badge'>";
				$field_sort_html.=$order=="asc"
						? "<span class='glyphicon glyphicon-arrow-up'></span>"
						: "<span class='glyphicon glyphicon-arrow-down'></span>";
			$field_sort_html.="</span>";
		}

		// show list of products to user
		echo "<table class='table table-hover table-responsive table-bordered'>";

			// product table header
			echo "<tr>";
				echo "<th class='w-20-pct'>";
						echo "Name ";
						echo $field=="name" ? $field_sort_html : "";
				echo "</th>";
				echo "<th class='w-15-pct'>";
						echo "Category ";
						echo $field=="category_name" ? $field_sort_html : "";
				echo "</th>";
				echo "<th class='w-10-pct'>";
						echo "Days Left ";
						echo $field=="active_until" ? $field_sort_html : "";
				echo "</th>";
				echo "<th class='w-10-pct'>";
						echo "Initial Price ";
						echo $field=="highest" ? $field_sort_html : "";
				echo "</th>";
				echo "<th class='w-15-pct'>Actions</th>";
			echo "</tr>";

			// list products from the database
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				extract($row);
           
				echo "<tr>";

					// product details
					echo "<td>{$name}</td>";
					echo "<td>{$category_name}</td>";

					// until when a product is active
					echo "<td>";
						if($active_until!="0000-00-00 00:00:00"){
							$date1 = new DateTime($active_until);
							$date2 = new DateTime(date('Y-m-d'));
							$interval = $date1->diff($date2);

							if($date1 <= $date2){
								echo "Closed";
							}

							else{
								echo $interval->days . " days ";
							}

						}else{
							echo "Closed.";
						}
         
					echo "</td>";
    
					echo "<td>{$price}</td>";
					echo "<td>";

						// edit product button
						echo "<a href='edit_product.php?id={$id}' class='btn btn-info m-b-10px w-100-pct'>";
						echo "<span class='glyphicon glyphicon-edit'></span> Edit Product";
						echo "</a>";


						// delete product button
						echo "<form action='read_products.php?id={$id}' method='post'> ";
						echo "<button type='submit' name='delete_product' class='btn btn-danger w-100-pct'>";
						echo "<span class='glyphicon glyphicon-remove'></span> Delete Product";
						echo "</button>";
						echo "</form>";
					echo "</td>";

				echo "</tr>";

			}

		echo "</table>";
		echo "</div>";
	echo "</div>";

	// the number of rows retrieved on that page
	$total_rows=0;

	// product search results
	if(isset($search_term) && $page_url="search_products.php?s={$search_term}&"){
		$total_rows = $product->countAll_BySearch($search_term);
	}

	// all inactive products
	else if($page_url=="read_inactive_products.php?"){
		$total_rows = $product->countAll_Inactive();
	}

	// all active products
	else if($page_url=="read_products.php?"){
		$total_rows = $product->countAll();
	}

	else if(isset($field) && isset($order) && $page_url=="read_products_sorted_by_fields.php?field={$field}&order={$order}&"){
		$total_rows=$product->countAll();
	}

	// it's a product category
	else if(isset($category_id) && $page_url=="category.php?id={$category_id}&"){
		$product->category_id=$category_id;
		$total_rows = $product->countAll_ByCategory();
	}

	// actual paging buttons
	include_once 'paging.php';

}

// tell the user if there's no products in the database
else{
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
			echo "<div class='alert alert-danger'>";
				echo "<strong>No products found.</strong>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}


?>
