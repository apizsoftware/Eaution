

<?php
// display the table if the number of users retrieved was greater than zero
if($num>0){
	echo "<div class='row'>";
	 	echo "<div class='col-md-12'>";
	    echo "<table class='table table-hover table-responsive table-bordered'>";

			// table headers
	     		   echo "<tr>";
							echo "<th>Name</th>";
							echo "<th>Email</th>";
							echo "<th>Address</th>";
	            echo "<th>Contact Number</th>";
	            echo "<th>Bidding Price</th>";
	       			echo "</tr>";

			// loop through the user records
	        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

				// display user details
	            echo "<tr>";
							echo "<td>{$fname} {$lname}</td>";
							echo "<td>{$email}</td>";
							echo "<td>{$address}</td>";
							echo "<td>{$phone}</td>";
							echo "<td>{$highest}</td>";
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
	include_once 'paging.php';
}

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
