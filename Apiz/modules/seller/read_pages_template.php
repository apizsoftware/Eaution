<?php
echo "<!-- search page -->";
echo "<div class='row'>";
	echo "<div class='col-md-3 pull-left m-b-20px'>";
		echo "<form role='search' action='search_pages.php'>";
			echo "<div class='input-group'>";
				echo "<input type='text' value=\"{$search_term}\"class='form-control' placeholder='Type keywords here...' name='s' id='srch-term' required />";
				echo "<div class='input-group-btn'>";
					echo "<button class='btn btn-primary' type='submit'><i class='glyphicon glyphicon-search'></i></button>";
				echo "</div>";
			echo "</div>";
		echo "</form>";
	echo "</div>";

	// create page button
	echo "<div class='col-md-9 pull-right'>";
		echo "<a href='create_page.php' class='btn btn-primary pull-right'>";
			echo "<span class='glyphicon glyphicon-plus'></span> Create Page";
		echo "</a>";
	echo "</div>";
echo "</div>";

// display the pages if there are any
if($total_rows>0){

	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		echo "<table class='table table-hover table-responsive table-bordered'>";
			echo "<tr>";
				echo "<th class='w-15-pct'>Featured Image</th>";
				echo "<th class='w-20-pct'>Title</th>";
				echo "<th class='w-10-pct'>Status</th>";
				echo "<th class='w-10-pct'>Author</th>";
				echo "<th class='w-10-pct'>Created</th>";
				echo "<th class='w-25-pct'>Actions</th>";
			echo "</tr>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				extract($row);

				echo "<tr>";
					echo "<td>";
						if(!empty($featured_image)){
							echo "<a href='{$home_url}images/{$featured_image}' title='{$title}' data-gallery>";
								echo "<img src='{$home_url}images/{$featured_image}' class='featured-img' />";
							echo "</a>";
						}
					echo "</td>";
					echo "<td>{$title}</td>";
					echo "<td>";
						echo $status==0 ? "Draft" : "Published";
					echo "</td>";
					echo "<td>{$firstname} {$lastname}</td>";
					echo "<td>";
						echo date("Y/m/d", strtotime($created));
					echo "</td>";
					echo "<td>";

						// view page button
						echo "<a href='{$home_url}page/{$slug}' class='btn btn-success left-margin' target='_blank'>";
							echo "<span class='glyphicon glyphicon-globe'></span> View Page";
						echo "</a>";

						if($_SESSION['user_id']==$user_id){
							// edit page button
							echo "<a href='update_page.php?id={$id}' class='btn btn-info left-margin'>";
								echo "<span class='glyphicon glyphicon-edit'></span> Edit";
							echo "</a>";

							// delete page button
							echo "<a delete-id='{$id}' delete-file='delete_page.php' class='btn btn-danger delete-object'>";
								echo "<span class='glyphicon glyphicon-remove'></span> Delete";
							echo "</a>";
						}

					echo "</td>";

				echo "</tr>";

			}

		echo "</table>";
		echo "</div>";
	echo "</div>";

	// paging buttons
	include_once 'paging.php';
}

// tell the user there are no pages
else{
	echo "<div class='row'>";
		echo "<div class='col-md-12'>";
			echo "<div class='alert alert-info'>No pages found.</div>";
		echo "</div>";
	echo "</div>";
}
?>
