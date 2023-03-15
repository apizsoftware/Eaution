<?php
// check if value was posted
if($_POST){

	// include database and object file
	include_once '../config/database.php';
	include_once '../objects/message.php';

	// get database connection
	$database = new Database();
	$db = $database->getConnection();

	// prepare page_obj object
	$message_obj = new Message($db);

	// set page_obj id to be deleted
	$message_obj->id = $_POST['object_id'];

	// delete the page_obj
	if($message_obj->delete()){
		echo "Object was deleted.";
	}

	// if unable to delete the page_obj
	else{
		echo "Unable to delete object.";

	}
}
?>
