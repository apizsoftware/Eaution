<?php


// if the form was submitted
if($_POST){
	echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    
		if(isset($_POST['delete_message'])){
			$message->id = $_GET['id'];
			if($message->delete()){
		     
				echo "<div class='alert alert-success'>";
				echo "Message was deleted.";
				echo "</div>";
		}

			// if unable to update the product, tell the user
			else{
					echo "<div class='alert alert-danger'>";
					echo "Unable to delete message.";
					echo "</div>";
			}
			
    }
  }



echo "<!-- search page -->";


// display the pages if there are any
if($total_rows>0){
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		echo "<table class='table table-hover table-responsive table-bordered'>";
			echo "<tr>";
				echo "<th class='w-10-pct'>Name</th>";
				echo "<th class='w-25-pct'>Subject</th>";
				echo "<th class='w-10-pct'>Sent</th>";
				echo "<th class='w-15-pct'>Actions</th>";
			echo "</tr>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				extract($row);

				echo $status==0 ? "<tr>" : "<tr class='unread-msg'>";
					echo "<td>{$name}</td>";
					echo "<td>{$subject}</td>";
					echo "<td>" . date('F d, Y H:i:s') . "</td>";
					echo "<td>";

						// view page button
						echo "<a href='{$home_url}modules/admin/read_message.php?id={$id}' class='btn btn-info mr-4'>";
							echo "<span class='glyphicon glyphicon-envelope'></span> Read Message";
						echo "</a>";


							// delete product button
							echo "<form action='read_messages.php?id={$id}' method='post'> ";
							echo "<button type='submit' name='delete_message' class='btn btn-danger'>";
							echo "<span class='glyphicon glyphicon-remove'></span> Delete Message";
							echo "</button>";
							echo "</form>";

					echo "</td>";

				echo "</tr>";

			}

		echo "</table>";
		echo "</div>";
	echo "</div>";

	// paging buttons
	include_once 'paging.php';
}

// tell the user there are no messages
else{
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
			echo "<div class='alert alert-info'>No messages found.</div>";
		echo "</div>";
	echo "</div>";
}
?>
