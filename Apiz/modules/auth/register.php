<?php
// core configuration
include_once "../../config/core.php";

// make it work in PHP 5.4
include_once "../../libs/zgpwhwzn/passwordLib.php";

// include login checker
include_once "login_checker.php";

// include classes
include_once '../../config/database.php';
include_once '../../classes/User.php';
include_once '../../classes/Category.php';
include_once '../../classes/Bids.php';




// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);



// set page title
$page_title = "Register";

// include page header HTML
include_once "../auth/layout_head.php";

echo "<div class='col-md-12'>";
	// if form was posted
	if($_POST){

		// set user email to detect if it already exists
		$user->email=$_POST['email'];

		// check if email already exists
		if($user->emailExists()){
			echo "<div class=\"alert alert-danger\">";
			echo "The email you specified is already registered. Please try to <a href='register.php'>register again.</a>";
			echo "</div>";
		}

		//create new user
		else{

			// set values to object properties
			$user->firstname=$_POST['firstname'];
			$user->lastname=$_POST['lastname'];
			$user->contact_number=$_POST['contact_number'];
			$user->address=$_POST['address'];
			$user->password=$_POST['password'];
			$user->access_level=$_POST['usertype'];
			$user->status=0;

			// create the user
			if($user->create()){
				echo "<div class=\"alert alert-success\">";
				echo "Account was created successfuly. Please <a href='login.php'>login</a> to continue.";
				echo "</div>";
				
				}

				else{
					echo "<div class=\"alert alert-danger\">";
					echo "Failed to create an account. Please try again.</a>";
					echo "</div>";
				}
			}//end of creating user
			
	}//end of if posted

	// if the form wasn't submitted yet, show register form
	else{
		
	?>

	<form action='register.php' method='post' id='register'>

		<table class='table table-hover table-responsive'>

			<tr>
				<td class='width-30-percent'>Firstname</td>
				<td><input type='text' name='firstname' class='form-control' required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "";  ?>" /></td>
			</tr>

			<tr>
				<td>Lastname</td>
				<td><input type='text' name='lastname' class='form-control' required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "";  ?>" /></td>
			</tr>

			<tr>
				<td>Contact Number</td>
				<td><input type='text' name='contact_number' class='form-control' required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "";  ?>" /></td>
			</tr>

			<tr>
				<td>Address</td>
				<td><textarea name='address' class='form-control' required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : "";  ?></textarea></td>
			</tr>

			<tr>
				<td>Email</td>
				<td><input type='email' name='email' class='form-control' required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "";  ?>" /></td>
			</tr>

			<tr>
				<td>Seller/Bidder ?</td>
				<td>		
						<select name="usertype" id="usertype" class="form-control">
						<option value="1">Seller</option>
						<option value="0">Bidder</option>
				</td>
			</tr>

			<tr>
				<td>Password</td>
				<td><input type='password' name='password' class='form-control' required id='passwordInput'></td>
			</tr>

			<tr>
				<td>Confirm Password</td>
				<td>
					<input type='password' name='confirm_password' class='form-control' required id='confirmPasswordInput'>
					<p>
						<div class="" id="passwordStrength"></div>
					</p>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-plus"></span> Register
					</button>
				</td>
			</tr>

		</table>
	</form>

	<?php
	}

echo "</div>";

// include page footer HTML
include_once "../bidder/layout_foot.php";
?>
