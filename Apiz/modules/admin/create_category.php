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

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize category object
$category = new Category($db);
$message = new Message($db);


// set page title
$page_title = "Create Category";

// import page header HTML
include_once "layout_head.php";

// read products button

// if the form was submitted
if($_POST){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";

		// set category property values
		$category->name=$_POST['name'];
		$category->description=$_POST['description'];

		// create the category
		if($category->create()){

			// tell the user new category was created
			echo "<div class=\"alert alert-success alert-dismissable\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
				echo "Category was created.";
			echo "</div>";
		}

		// if unable to create the category, tell the user
		else{
			echo "<div class=\"alert alert-danger alert-dismissable\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
				echo "Unable to create category.";
			echo "</div>";
		}
		echo "</div>";
	echo "</div>";
}

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	?>

	<!-- HTML form for creating a category -->
	<form action='create_category.php' method='post'>

		<table class='table table-hover table-responsive'>

			<tr>
				<td class='w-30-pct'>Name</td>
				<td><input type='text' name='name' class='form-control' required></td>
			</tr>

			<tr>
				<td>Description</td>
				<td><textarea name='description' class='form-control h-100-px'></textarea></td>
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
