<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// get ID of the product to be edited
$category_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// include classes
include_once '../config/database.php';
include_once "../objects/category.php";
include_once "../objects/message.php";
include_once "../objects/order.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare category object
$category = new Category($db);
$message = new Message($db);
$order = new Order($db);

// set page title
$page_title = "Update Category";

// include page header HTML
include_once "layout_head.php";

// read category button
echo "<div class='row'>";
	echo "<div class='col-md-12 pull-right m-b-20px'>";
		echo "<a href='read_categories.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-list'></span> Read Categories";
		echo "</a>";
	echo "</div>";
echo "</div>";

// set ID property of product to be edited
$category->id = $category_id;

// read the details of category to be edited
$category->readOne();

// if the form was submitted
if($_POST){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";

		// set category property values
		$category->name = $_POST['name'];
		$category->description = $_POST['description'];

		// update the category
		if($category->update()){

			echo "<div class=\"alert alert-success alert-dismissable\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
				echo "Category was updated.";
			echo "</div>";
		}

		// if unable to update the category, tell the user
		else{
			echo "<div class=\"alert alert-danger alert-dismissable\">";
				echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
				echo "Unable to update category.";
			echo "</div>";
		}
		echo "</div>";
	echo "</div>";
}

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	?>

	<!-- HTML form for updating a product -->
	<form action='update_category.php?id=<?php echo $category_id; ?>' method='post'>

		<table class='table table-hover table-responsive'>

			<tr>
				<td class='w-30-pct'>Name</td>
				<td><input type='text' name='name' value='<?php echo $category->name; ?>' class='form-control' required></td>
			</tr>

			<tr>
				<td>Description</td>
				<td><textarea name='description' class='form-control h-100-px'><?php echo $category->description; ?></textarea></td>
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
