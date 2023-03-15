 <?php
// 'Price' object
class Price{

	// database connection and table name
	private $conn;
	private $table_name = "price";

	// object properties
	public $id;
  public $product_id;
	public $price;
  public $created;

	// constructor
	public function __construct($db){
		$this->conn = $db;
	}

	// update stock
	function updatePrice(){

		// product update query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					price = :price
				WHERE
					product_id = :id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->stock=htmlspecialchars(strip_tags($this->price));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind variable values
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':id', $this->product_id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// delete the product
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind record id
		$stmt->bindParam(1, $this->id);

		// execute the query
		if($result = $stmt->execute()){
			return true;
		}

		return false;
	}

	// delete all the variations
	 function deleteAll(){

		// delete product image query
		$query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->product_id=htmlspecialchars(strip_tags($this->product_id));

		// bind product image id variable
		$stmt->bindParam(1, $this->product_id);

		// execute query
		if($result = $stmt->execute()){
			return true;
		}

		return false;
	}

	// update the variation
	function update(){

		// update query
		$query = "UPDATE " . $this->table_name . "
				SET name=:name, price=:price, stock=:stock
				WHERE id=:id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->stock=htmlspecialchars(strip_tags($this->stock));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':stock', $this->stock);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// create variation
	function create(){

		// to get time-stamp for 'created' field
		$this->getTimestamp();

		// insert query
		$query = "INSERT INTO " . $this->table_name . "
				SET product_id=:product_id,  price=:price, created=:created";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->product_id=htmlspecialchars(strip_tags($this->product_id));
    $this->price=htmlspecialchars(strip_tags($this->price));

		// bind values
		$stmt->bindParam(":product_id", $this->product_id);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":created", $this->timestamp);

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

	// read variation details
	function readOne(){

		// select single record query
		$query = "SELECT id, product_id, price, created
				FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind selected record id
		$stmt->bindParam(1, $this->id);

		// execute the query
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id = $row['id'];
		$this->product_id = $row['product_id'];
		$this->price = $row['price'];
		
	}

	// read product price
	function readByProductId(){

		// select single record query
		$query = "SELECT id, product_id, price
				FROM " . $this->table_name . "
				WHERE product_id = ?
				";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->product_id=htmlspecialchars(strip_tags($this->product_id));

		// bind selected record id
		$stmt->bindParam(1, $this->product_id);

		// execute the query
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id = $row['id'];
		$this->product_id = $row['product_id'];
		$this->price = $row['price'];
		
	}

	

	// used for paging variations
	public function countAll(){

		// query to count all data
		$query = "SELECT count(*) FROM " . $this->table_name;

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// execute query
		$stmt->execute();

		// get row value
		$rows = $stmt->fetch(PDO::FETCH_NUM);

		// return all data count
		return $rows[0];
	}

	// used to read variation name by its ID
	function readName(){

		// select single record query
		$query = "SELECT name FROM " . $this->table_name . " WHERE id = ? limit 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

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
}
?>
