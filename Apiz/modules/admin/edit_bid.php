<?php
// core configuration
include_once "../../config/core.php";

// check if logged in as admin
include_once "../auth/login_checker.php";

// get ID of the product to be edited
$product_id = isset($_GET['id']) ? $_GET['id'] : die('Missing product ID.');

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
$page_title = "Update Product";

// include page header HTML
include_once "layout_head.php";

// read products button
echo "<div class='row'>";
	echo "<div class='col-md-12 pull-right m-b-20px'>";
		echo "<a href='read_products.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-list'></span> Read Products";
		echo "</a>";
	echo "</div>";
echo "</div>";

// set ID property of product to be edited
$product->id = $product_id;

// read the details of product to be edited
$product->readOne();

// if the form was submitted
if($_POST){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";

		// set product property values
		$product->name = $_POST['name'];
		$product->description = $_POST['description'];
		$product->category_id = $_POST['category_id'];
		$product->active_until = $_POST['active_until'];

		if(isset($_POST['close_bid'])){
			
			if($bid->closeBid($product_id,0) && $product->closeBid($product_id)){
		     
				echo "<div class='alert alert-success'>";
				echo "Bid was closed.";
				echo "</div>";
		}

			// if unable to update the product, tell the user
			else{
					echo "<div class='alert alert-danger'>";
					echo "Unable to close bid.";
					echo "</div>";
			}
			
		}

		// update the product
		else{
			if($product->update()){
				
			echo "<div class='alert alert-success'>";
			echo "Product was updated.";
			echo "</div>";
			}

		// if unable to update the product, tell the user
		else{
				echo "<div class='alert alert-danger'>";
				echo "Unable to update product.";
				echo "</div>";
		}
	}
		echo "</div>";
	echo "</div>";
}

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	?>

	<!-- HTML form for updating a product -->
	<form action='edit_bid.php?id=<?php echo $product_id; ?>' method='post' enctype="multipart/form-data">

		<table class='table table-hover table-responsive table-bordered'>

			<tr>
				<td class='w-30-pct'>Name</td>
				<td><input type='text' name='name' value="<?php echo htmlentities($product->name); ?>" class='form-control' required /></td>
			</tr>

			<tr>
				<td>Description</td>
				<td><textarea name='description' class='form-control activate-tinymce'><?php echo htmlspecialchars_decode($product->description); ?></textarea></td>
			</tr>

			<tr>
				<td>Category</td>
				<td>
					<?php
					// read the product categories from the database
					

					$category = new Category($db);
					$stmt = $category->readAll_WithoutPaging();

					// put them in a select drop-down
					echo "<select class='form-control' name='category_id'>";

						echo "<option>Please select...</option>";
						while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
							extract($row_category);

							// current category of the product must be selected
							if($product->category_id==$id){
								echo "<option value='$id' selected>";
							}else{
								echo "<option value='$id'>";
							}

							echo "$name</option>";
						}
					echo "</select>";
					?>
				</td>
			</tr>

			<tr>
				<td>Close Bid</td>
				<td>
					<button type="submit" name="close_bid" class="btn btn-danger">
						<span class='glyphicon glyphicon-edit'></span> Close Bid
					</button>
				</td>
			</tr>

			<tr>
				<td>Extend Bid:</td>
				<td>
					<!-- we are using jQuery UI as data picker -->
					<input type="text" name='active_until' id="active-until" value='<?php echo htmlentities(substr($product->active_until, 0, 10)); ?>' class='form-control' placeholder="Click to pick date" />
				</td>
			</tr>

		

			<tr>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary">
						<span class='glyphicon glyphicon-edit'></span> Update
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
