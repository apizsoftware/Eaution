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

// set page headers
$page_title = "Create Page";
include_once "layout_head.php";

// read pages button
echo "<div class='row'>";
	echo "<div class='right-button-margin'>";
		echo "<a href='read_pages.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-list'></span> Read pages";
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
		$page_obj->user_id = $_SESSION['user_id'];

		// check if slug exists
		// if(!$page_obj->slugExists()){
		if(1==1){

			//if they DID upload a file...
		    if(!empty($_FILES['featured_image']['name'])){

		        //if no errors...
		        if(!$_FILES['featured_image']['error']){

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
		        }

		        // tell the user what was the error
		        else{
					echo "<div class='alert alert-danger'>";
		            	echo "Error uploading featured image: " . $_FILES['featured_image']['error'];
					echo "</div>";
		        }
		    }

			// create the page
			if($page_obj->create()){
				echo "<div class='alert alert-success'>";
					echo "Page was created.";
				echo "</div>";

				// empty form values
				$page_obj->title = "";
				$page_obj->slug = "";
				$page_obj->body = "";
				$page_obj->meta_description = "";
				$page_obj->status = "";
			}

			// if unable to create the page, tell the user
			else{
				echo "<div class='alert alert-danger'>";
					echo "Unable to create page.";
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

		<!-- HTML form for creating a page -->
		<form action='create_page.php' method='post' enctype='multipart/form-data'>

			<table class='table table-responsive'>

				<tr>
					<td class='w-35-pct'>Title</td>
					<td><input type='text' value="<?php echo isset($page_obj->title) ? htmlentities($page_obj->title) : ""; ?>" name='title' class='form-control' id='title' required /></td>
				</tr>
				<tr>
					<td>
						Slug<br />
						<small>For SEO friendly URLs</small>
					</td>
					<td><input type='text' value="<?php echo isset($page_obj->slug) ? htmlentities($page_obj->slug) : ""; ?>" name='slug' class='form-control' id='slug' required /></td>
				</tr>
				<tr>
					<td>Body</td>
					<td><textarea name='body' class='form-control activate-tinymce'><?php echo isset($page_obj->body) ? htmlspecialchars_decode($page_obj->body) : ""; ?></textarea></td>
				</tr>
				<tr>
					<td>
						Meta Description<br />
						<small>Short description of this page.</small>
					</td>
					<td><textarea name='meta_description' class='form-control'><?php echo isset($page_obj->meta_description) ? htmlentities($page_obj->meta_description) : ""; ?></textarea></td>
				</tr>
				<tr>
					<td>
						Featured Image<br />
						<small>Image to show when shared on social media</small>
					</td>
					<td><input type='file' name='featured_image' class='form-control' /></td>
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
							<span class="glyphicon glyphicon-plus"></span> Create
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
