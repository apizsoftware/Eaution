<?php
// core configuration
include_once "../../config/core.php";

// check if logged in as admin
include_once "../auth/login_checker.php";


// include classes

include_once "../../config/database.php";
include_once "../../libs/utils.php";
include_once "../../classes/Product.php";
include_once "../../classes/Product_image.php";
include_once "../../classes/Category.php";
include_once '../../classes/Bids.php';
include_once '../../classes/Price.php';

// get database connection
$database = new Database();
$db = $database->getConnection();


// initialize objects
$product = new Product($db);
$utils = new Utils($db);
$product_image = new ProductImage($db);
$category = new Category($db);
$bid = new Bid($db);
$price = new Price($db);

// set page title
$page_title = "Create Product";

// import page header HTML
include_once "layout_head.php";

// read products button
echo "<div class='row'>";
	echo "<div class='col-md-12 pull-right m-b-20px'>";
		echo "<a href='read_products.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-list'></span> Read Products";
		echo "</a>";
	echo "</div>";
echo "</div>";

// if the form was submitted
if($_POST){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
	

		// set product property values
		$product->name = $_POST['name'];
		$price->price = $_POST['initial_price'];
		$product->description = $_POST['description'];
		$product->category_id = $_POST['category_id'];
		$product->active_until = $_POST['active_until'];

		// create the product
		if($product->create()){

			// get last inserted id
			$product_id=$db->lastInsertId();
			// save the images
			$product_image->product_id = $product_id;
			$product_image->upload();

			$price->product_id = $product_id;
			$price->create();


			echo "<div class='alert alert-success'>";
			echo "Product was created.";
			echo "</div>";
		}

		// if unable to create the product, tell the user
		else{
			echo "<div class='alert alert-danger'>";
				echo "Unable to create product.";
			echo "</div>";
		}

		echo "</div>";
	echo "</div>";
}

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	?>

	<!-- HTML form for creating a product -->
	<form action='create_product.php' method='post' enctype="multipart/form-data">

		<table class='table table-hover table-responsive table-bordered'>

			<tr>
				<td class='w-30-pct'>Name</td>
				<td><input type='text' name='name' class='form-control' required></td>
			</tr>

			<tr>
				<td class='w-30-pct'>Initial Price</td>
				<td><input type='number' name='initial_price' class='form-control' required></td>
			</tr>

			<tr>
				<td>Description</td>
				<td><textarea name='description' class='form-control activate-tinymce'></textarea></td>
			</tr>

			<tr>
				<td>Category</td>
				<td>
				<?php
				// read the categories from the database
				$stmt = $category->readAll_WithoutPaging();

				// put them in a select drop-down
				echo "<select class='form-control' name='category_id'>";
					echo "<option>Select category...</option>";

					// loop through the caregories
					while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
						extract($row_category);
						echo "<option value='{$id}'>{$name}</option>";
					}

				echo "</select>";
				?>
				</td>
			</tr>

			<tr>
				<td>Active Until:</td>
				<td>
					<!-- uses jQuery UI date picker -->
					<input type="date" name='active_until' id="active-until" class='form-control' placeholder="Click to pick date" />
				</td>
			</tr>

			<tr>
				<td>Image(s):</td>
				<td>
					<!-- browse multiple image files -->
					<input type="file" name="files[]" class='form-control' multiple>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary">
						<span class='glyphicon glyphicon-plus'></span> Create
					</button>
				</td>
			</tr>

		</table>
	</form>

	<?php
echo "</div>";
echo "</div>";

// include page footer HTML
include_once "layout_foot.php";
?>
