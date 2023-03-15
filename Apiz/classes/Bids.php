<?php
// 'cart_item' object
class Bid{

	// database connection and table name
	private $conn;
	private $table_name = "bids";

	// object properties
	public $id;
	public $product_id;
	public $price_id;
	public $bidding_price;
	public $bidder_id;
	public $status;

	// constructor
	public function __construct($db){
		$this->conn = $db;

	}




	function placeBid(){

		
		// to get time-stamp for 'created' field
		
		$date = date('Y/m/d H:i:s');
		// insert product query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
				product_id=:product_id,
				price_id=:price_id,
				bidding_price=:bidding_price,
				bidder_id=:bidder_id,
				created=:created,
				status=:status";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->	product_id=htmlspecialchars(strip_tags($this->	product_id));
		$this->price_id=htmlspecialchars($this->price_id);
		$this->bidding_price=htmlspecialchars(strip_tags($this->bidding_price));
		$this->bidder_id=htmlspecialchars(strip_tags($this->bidder_id));
		$this->status=htmlspecialchars(strip_tags($this->status));

		// bind values
		$stmt->bindParam(":product_id", $this->product_id);
		$stmt->bindParam(":price_id", $this->price_id);
		$stmt->bindParam(":bidding_price", $this->bidding_price);
		$stmt->bindParam(":bidder_id", $this->bidder_id);
		$stmt->bindParam(":status", $this->status);
		$stmt->bindParam(":created", $date);


		// execute query
		if($stmt->execute()){
			return true;
		}else{
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "</pre>";
			return false;
		}

	}

	// read all bids by user id
	function readAll_ByUser($from_record_num, $records_per_page){

		// query to select all orders related to a user
		$query = "SELECT
					b.id as id,
					p.name as name,
					p.id as pid,
					b.bidding_price as price,
					b.created as created,
					b.status as status
				FROM
					" . $this->table_name . " b
					LEFT JOIN products p
					ON b.product_id = p.id
				WHERE
					b.bidder_id=?
				ORDER BY
					b.created DESC
				LIMIT
					?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->bidder_id=htmlspecialchars(strip_tags($this->bidder_id));
		$this->status=htmlspecialchars(strip_tags($this->status));

		// bind values
		$stmt->bindParam(1, $this->bidder_id);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}
	// delete the product
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE product_id = ? AND user_id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind record id
		$stmt->bindParam(1, $this->product_id);
		$stmt->bindParam(2, $this->user_id);

		// execute the query
		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// delete the product
	function deleteAllByUser(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind record id
		$stmt->bindParam(1, $this->user_id);

		// execute the query
		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// count all categories based on search term
	public function countAll_BySearch($search_term){

		// search query
		$query = "SELECT id FROM " . $this->table_name . " WHERE name LIKE ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind search term
		$search_term = "%{$search_term}%";
		$stmt->bindParam(1, $search_term);

		// execute query
		$stmt->execute();

		// get row count
		$num = $stmt->rowCount();

		// return row count
		return $num;
	}

	// search categories
	function search($search_term, $from_record_num, $records_per_page){

		// search query
		$query = "SELECT id, name, description
				FROM " . $this->table_name . "
				WHERE name LIKE ?
				ORDER BY name ASC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind  variables
		$search_term = "%{$search_term}%";
		$stmt->bindParam(1, $search_term);
		$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	// update the cart_item
	function update(){

		// update query
		$query = "UPDATE " . $this->table_name . "
				SET quantity = :quantity
				WHERE user_id = :user_id AND product_id = :product_id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind values
		$stmt->bindParam(':quantity', $this->quantity);
		$stmt->bindParam(':user_id', $this->user_id);
		$stmt->bindParam(':product_id', $this->product_id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// update user id
	function updateUserId(){

		// update query
		$query = "UPDATE " . $this->table_name . "
				SET user_id = ?
				WHERE user_id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind values
		$stmt->bindParam(1, $this->user_id);
		$stmt->bindParam(2, $_SESSION['user_id']);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// create cart_item
	function create(){

		$created=$this->getTimestamp();

		// insert query
		$query = "INSERT INTO " . $this->table_name . "
					SET product_id=:product_id, quantity=:quantity, price=:price,
						user_id=:user_id, variation_id=:variation_id, variation_name=:variation_name, created=:created";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->product_id=htmlspecialchars(strip_tags($this->product_id));
		$this->quantity=htmlspecialchars(strip_tags($this->quantity));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->user_id=htmlspecialchars(strip_tags($this->user_id));
		$this->variation_id=htmlspecialchars(strip_tags($this->variation_id));
		$this->variation_name=htmlspecialchars(strip_tags($this->variation_name));

		// bind values
		$stmt->bindParam(":product_id", $this->product_id);
		$stmt->bindParam(":quantity", $this->quantity);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":user_id", $this->user_id);
		$stmt->bindParam(":variation_id", $this->variation_id);
		$stmt->bindParam(":variation_name", $this->variation_name);
		$stmt->bindParam(":created", $this->timestamp);

		// execute query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// read cart_item details
	function readOne(){

		// select single record query
		$query = "SELECT name, description
				FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind selected record id
		$stmt->bindParam(1, $this->id);

		// execute the query
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->name = $row['name'];
		$this->description = $row['description'];
	}

	// read all available categories (with limit clause for paging)
	function readAll($from_record_num, $records_per_page){

		// query select all categories
		$query = "SELECT id, name, description
				FROM " . $this->table_name . "
				ORDER BY name
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	// read all cart items without limit clause, used drop-down list
	function readAll_WithoutPaging(){

		// select all data
		$query="SELECT p.id, p.name, ci.price, ci.variation_id, ci.variation_name, ci.quantity, ci.quantity * ci.price AS subtotal
			FROM " . $this->table_name . " ci
				LEFT JOIN products p
					ON ci.product_id = p.id
			WHERE user_id = :user_id";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind values
		$stmt->bindParam(':user_id', $this->user_id);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	public function checkIfExists(){

		// query to count all data
		$query = "SELECT quantity FROM " . $this->table_name . " WHERE user_id = ? and product_id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind values
		$stmt->bindParam(1, $this->user_id);
		$stmt->bindParam(2, $this->product_id);

		// execute query
		$stmt->execute();
		if($stmt->rowCount()){
				// get row value
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				// set quantity
				$this->quantity=$row['quantity'];
		}
		

		// return all data count
		return $stmt->rowCount();
	}

	

	// used to read cart_item name by its ID
	function readName(){

		// select single record query
		$query = "SELECT name FROM " . $this->table_name . " WHERE id = ? limit 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind selected record id
		$stmt->bindParam(1, $this->id);

		// execute query
		$stmt->execute();

		// read row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set value to 'name' property
		$this->name = $row['name'];
	}

	// used for the 'created' field
	function getTimestamp(){
		date_default_timezone_set('Asia/Manila');
		$this->timestamp = date('Y-m-d H:i:s');
	}

	function readOneByBidId(){

		// select single record query
		$query = "SELECT
					MAX(bidding_price) as highestbid

				FROM
					" . $this->table_name . " 
				 WHERE product_id= ?
				";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->product_id=htmlspecialchars(strip_tags($this->product_id));

		// bind transaction id value
		 $stmt->bindParam(1, $this->product_id);

		// execute query
		$stmt->execute();

		// get row values
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assigned values to object properties
		$this->bidding_price = $row['highestbid'];
		
	}

		// update user id
		function updateBid(){

			// update query
			$query = "UPDATE " . $this->table_name . "
					SET bidding_price = ?
					WHERE bidder_id = ? AND product_id = ?";
	
			// prepare query statement
			$stmt = $this->conn->prepare($query);
	
			// bind values
			$stmt->bindParam(1, $this->bidding_price);
			$stmt->bindParam(2, $_SESSION['user_id']);
			$stmt->bindParam(3, $this->product_id);
	
			// execute the query
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}
		// update user id
		function closeBid($product_id, $status){

			// update query
			$query = "UPDATE " . $this->table_name . "
					SET status = ?
					WHERE product_id = ?";
	
			// prepare query statement
			$stmt = $this->conn->prepare($query);
	
			// bind values
			$stmt->bindParam(1, $status);
			$stmt->bindParam(2, $product_id);
			
	
			// execute the query
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}
	
	

}
?>
