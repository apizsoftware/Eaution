<?php
// core configuration
include_once "../config/core.php";

// check if logged in as admin
include_once "login_checker.php";

// get database connection
include_once '../config/database.php';
include_once "../objects/category.php";
include_once '../objects/page.php';
include_once "../objects/message.php";
include_once "../objects/order.php";

include_once '../libs/php/utils.php';

// get databae connection
$database = new Database();
$db = $database->getConnection();

// instantiate page object
$category = new Category($db);
$page_obj = new Page($db);
$message = new Message($db);
$order = new Order($db);

// utilities
$utils = new Utils();

// get ID of the page to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// set ID property of page to be edited
$page_obj->id = $id;

// read the details of page to be edited
$page_obj->readOne();

// check if user owns the page he tries to edit
if($_SESSION['user_id']!=$page_obj->user_id){
	header("Location: {$home_url}admin/index.php?action=no_page_edit");
}

// set page headers
$page_title = "Update Page";
include_once "layout_head.php";

// read pages button
echo "<div class='row'>";
	echo "<div class='col-md-12 m-b-20px'>";
		echo "<a href='read_pages.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-list'></span> Read Pages";
		echo "</a>";
		echo "<a href='{$home_url}page/{$page_obj->slug}' class='btn btn-success pull-right m-r-15px'>";
			echo "<span class='glyphicon glyphicon-globe'></span> View Page";
		echo "</a>";
	echo "</div>";
echo "</div>";

// if the form was submitted
if($_POST){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";

		// set page property values
		$page_obj->title = $_POST['title'];
		$page_obj->slug = $utils->slugify($_POST['slug']);
		$page_obj->body = $_POST['body'];
		$page_obj->meta_description = $_POST['meta_description'];
		$page_obj->status = $_POST['status'];

		// check if slug exists
		if(!$page_obj->slugExists()){

			//if they DID upload a file...
			if(!empty($_FILES['featured_image']['name'])){

				// make sure the file type is allowed
				$target_dir = "../images/";
				$target_file_name = substr(md5(uniqid(date('YmdHis'), true)), 0, 10) . basename($_FILES["featured_image"]["name"]);
				$target_dir_file_name = $target_dir . $target_file_name;

				$image_file_type = pathinfo($target_dir_file_name, PATHINFO_EXTENSION);

				if(
					$image_file_type!="jpg" && $image_file_type!="jpeg"
					&& $image_file_type!="png" && $image_file_type!="gif"
				){
					echo "<div class='alert alert-danger'>";
						echo "Image file types allowed are JPG, PNG, GIF only.";
					echo "</div>";
				}

				else{

					//if no errors...
					if(!$_FILES['featured_image']['error']){

						// now is the time to modify the future file name and validate the file
						// rename file
						$new_file_name = strtolower($_FILES['featured_image']['tmp_name']);

						// can't be larger than 1 MB
						if($_FILES['featured_image']['size'] > (1024000))
						{
							echo "<div class='alert alert-danger'>";
								echo "Featured image must be less than 1MB in size. ";
							echo "</div>";
						}

						// if valid file
						else{

							//move it to where we want it to be
							if(move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_dir_file_name)){
								// assign values
								$page_obj->featured_image=$target_file_name;
							}

							else{
								echo "<div class='alert alert-danger'>";
									echo "Unable to upload featured image. ";
								echo "</div>";
							}

						}
					}

					// tell the user what was the error
					else{
						echo "<div class='alert alert-danger'>";
							echo "Error uploading featured image: " . $_FILES['featured_image']['error'];
						echo "</div>";
					}

				}
			}

			// update without new featured image
			if(empty($page_obj->featured_image)){
				$update_result=$page_obj->updateWithoutFeaturedImage();
			}

			// update with new featured image
			else{
				$update_result=$page_obj->update();
			}

			// update the page
			if($page_obj->update()){
				echo "<div class='alert alert-success'>";
					echo "Page was updated. ";
					echo "<a href='{$home_url}page/{$page_obj->slug}'>View page</a>.";
				echo "</div>";
			}

			// if unable to udpate the page, tell the user
			else{
				echo "<div class='alert alert-danger'>";
					echo "Unable to update page.";
				echo "</div>";
			}
		}

		else{
			echo "<div class='alert alert-danger'>";
				echo "Slug must be unique. ";
			echo "</div>";
		}

		echo "</div>";
	echo "</div>";
}

echo "<div class='row'>";
	echo "<div class='col-md-12'>";
	?>

	<!-- HTML form for updating a page -->
	<form action='update_page.php?id=<?php echo $id; ?>' method='post' enctype='multipart/form-data'>

		<table class='table table-responsive'>

			<tr>
				<td class='w-35-pct'>Title</td>
				<td><input type='text' value="<?php echo htmlentities($page_obj->title); ?>" name='title' class='form-control' id='title' required /></td>
			</tr>
			<tr>
				<td>
					Slug<br />
					<small>For SEO friendly URLs</small>
				</td>
				<td><input type='text' value="<?php echo htmlentities($page_obj->slug); ?>" name='slug' class='form-control' id='slug' required /></td>
			</tr>
			<tr>
				<td>Body</td>
				<td><textarea name='body' class='form-control activate-tinymce'><?php echo htmlspecialchars_decode($page_obj->body); ?></textarea></td>
			</tr>
			<tr>
				<td>
					Meta Description<br />
					<small>Short description of this page.</small>
				</td>
				<td><textarea name='meta_description' class='form-control'><?php echo htmlentities($page_obj->meta_description); ?></textarea></td>
			</tr>
			<tr>
				<td>
					Featured Image<br />
					<small>Image to show when shared on social media</small>
				</td>
				<td>
					<input type='file' name='featured_image' class='form-control' />
					<?php
					if(!empty($page_obj->featured_image)){
						echo "<a href='{$home_url}images/{$page_obj->featured_image}' title='{$page_obj->title}' data-gallery>";
							echo "<img src='../images/" . htmlentities($page_obj->featured_image) . "' id='featured-img' />";
						echo "</a>";
						echo "<div>";
							echo "<button id='remove-featured-image' class='btn btn-danger btn-xs' delete-id='{$page_obj->id}'>Remove Featured Image</button>";
						echo "</div>";
					}
					?>
				</td>
			</tr>
			<tr>
			<td>Status</td>
				<td>
					<div class="btn-group" data-toggle="buttons">
						<?php
						if($page_obj->status==0){
						?>
							<label class="btn btn-default active">
								<input type="radio" name="status" value="0" checked /> Draft
							</label>

							<label class="btn btn-default">
								<input type="radio" name="status" value="1" /> Published
							</label>
						<?php
						}else{
						?>
							<label class="btn btn-default">
								<input type="radio" name="status" value="0"> Draft
							</label>

							<label class="btn btn-default active">
								<input type="radio" name="status" value="1" checked /> Published
							</label>
							<?php
						}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-edit"></span> Save Changes
					</button>
				</td>
			</tr>

		</table>
	</form>

	<?php
	echo "</div>";
echo "</div>";

include_once "layout_foot.php";
?>
