<?php
// 'cart_item' object
class Charge{

	// database connection and table name
	private $conn;
	private $table_name = "charges";

	// object properties
	public $id;
	public $product_id;
	public $amount;
	public $status;

	// constructor
	public function __construct($db){
		$this->conn = $db;

  }
  
}