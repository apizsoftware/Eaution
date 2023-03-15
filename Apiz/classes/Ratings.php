<?php
// 'cart_item' object
class Rating{

	// database connection and table name
	private $conn;
	private $table_name = "ratings";

	// object properties
  public $id;
  public $user_id;
	public $product_id;
	public $rating;
	public $comment;

	// constructor
	public function __construct($db){
		$this->conn = $db;

  }
  
}