

<?php
// if the form was submitted
if($_POST){
	echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    
		if(isset($_POST['approve'])){
			$id = $_GET['id'];
			if($user->updateUser($id,1)){
		     
				echo "<div class='alert alert-success'>";
				echo "User was Approved.";
				echo "</div>";
	  	}

			// if unable to update the product, tell the user
			else{
					echo "<div class='alert alert-danger'>";
					echo "Unable to approve user.";
					echo "</div>";
			}
		}
		if(isset($_POST['suspend'])){
			$id = $_GET['id'];
			if($user->updateUser($id ,2)){
		     
				echo "<div class='alert alert-success'>";
				echo "User was suspended.";
				echo "</div>";
		}

			// if unable to update the product, tell the user
			else{
					echo "<div class='alert alert-danger'>";
					echo "Unable to suspend user.";
					echo "</div>";
			}
			
	}
}

// display the table if the number of users retrieved was greater than zero
if($num>0){
	echo "<div class='row'>";
	 	echo "<div class='col-md-12'>";
	    echo "<table class='table table-hover table-responsive table-bordered'>";

			// table headers
	        echo "<tr>";
				echo "<th>Name</th>";
	            echo "<th>Email</th>";
	            echo "<th>Contact Number</th>";
							echo "<th>User Type</th>";
							echo "<th>Status</th>";
							
				echo "<th>Actions</th>";
	        echo "</tr>";

			// loop through the user records
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

				// display user details
	            echo "<tr>";
					echo "<td>{$firstname} {$lastname}</td>";
	                echo "<td>{$email}</td>";
					echo "<td>{$contact_number}</td>";
					switch($access_level)
							{
								case 0:
									$usertype="Bidder";
								break;
								case 1:
									$usertype="Seller";
								break;
							}					echo "<td>{$usertype}</td>";
							switch(	$status)
							{
								case 0:
									$status="Pending";
								break;
								case 1:
									$status="Active";
								break;
								case 2:
									$status="Suspended";
								break;
							}	
					echo "<td>{$status}</td>";
	                echo "<td>";

							// Approve User
							echo "<form action='read_users.php?id={$id}' method='post'> ";
							echo "<button type='submit' name='approve' class='btn btn-info'>";
							echo "<span class='glyphicon glyphicon-check'></span> Approve User";
							echo "</button>";
							echo "</form>";
          	// Suspend user
							echo "<form action='read_users.php?id={$id}' method='post'> ";
							echo "<button type='submit' name='suspend' class='btn btn-warning'>";
							echo "<span class='glyphicon glyphicon-remove'></span> Suspend User";
							echo "</button>";
							echo "</form>";

						
						}
	                echo "</td>";
	            echo "</tr>";
	        }
				
	    echo "</table>";
		echo "</div>";
	echo "</div>";

	// the number of rows retrieved on that page
	$total_rows=0;
				
	// user search results
	if(isset($search_term) && $page_url=="search_users.php?s={$search_term}&"){
		$total_rows = $user->countAll_BySearch($search_term);
	}

	// all users
	else if($page_url=="read_users.php?"){
		$total_rows = $user->countAll();
	}

	// actual paging buttons

// tell the user there are no selfies
else{
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		    echo "<div class=\"alert alert-danger\" role=\"alert\">";
				echo "<strong>No users found.</strong>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}
?>
