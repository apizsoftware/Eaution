<?php
echo "<!-- search page -->";
	echo "<div class='row'>";
	echo "<div class='col-md-3 pull-left m-b-20px'>";
		echo "<form role='search' action='search_messages.php'>";
			echo "<div class='input-group'>";
				echo "<input type='text' value=\"{$search_term}\"class='form-control' placeholder='Type keywords here...' name='s' id='srch-term' required />";
				echo "<div class='input-group-btn'>";
					echo "<button class='btn btn-primary' type='submit'><i class='glyphicon glyphicon-search'></i></button>";
				echo "</div>";
			echo "</div>";
		echo "</form>";
	echo "</div>";
echo "</div>";

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
						echo "<a href='{$home_url}admin/read_message.php?id={$id}' class='btn btn-info left-margin'>";
							echo "<span class='glyphicon glyphicon-envelope'></span> Read Message";
						echo "</a>";


						// delete page button
						echo "<a delete-id='{$id}' delete-file='delete_message.php' class='btn btn-danger delete-object'>";
							echo "<span class='glyphicon glyphicon-remove'></span> Delete";
						echo "</a>";

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
