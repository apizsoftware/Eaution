<?php
class Setting{

    // database connection and table name
    private $conn;
	private $table_name	= "settings";

	// object properties
	public $contact_firstname;
	public $contact_lastname;
    public $contact_email;
	public $contact_number;
    public $show_contact_name;
    public $show_contact_email;
    public $show_contact_number;

    // constructor
	public function __construct($db){
		$this->conn = $db;
	}

    // read settings
	function read(){

        // read query
		$sql = "SELECT * FROM  " . $this->table_name . " WHERE id=1 LIMIT 0 ,1";

        // prepared statement
		$stmt = $this->conn->prepare($sql);

        // execute query
		$stmt->execute();

        // read row
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign to object properties
		$this->contact_firstname=$rows['contact_firstname'];
		$this->contact_lastname=$rows['contact_lastname'];
        $this->contact_email=$rows['contact_email'];
		$this->contact_number=$rows['contact_number'];
        $this->show_contact_name=$rows['show_contact_name'];
        $this->show_contact_email=$rows['show_contact_email'];
        $this->show_contact_number=$rows['show_contact_number'];

	}

    // update settings
	function update() {

        // update query
		$sql="UPDATE " . $this->table_name . "
                SET
                    contact_firstname=:contact_firstname,
                    contact_lastname=:contact_lastname,
                    contact_gender=:contact_gender,
                    contact_email=:contact_email,
                    contact_number=:contact_number,
                    show_contact_name=:show_contact_name,
                    show_contact_email=:show_contact_email,
                    show_contact_number=:show_contact_number
                WHERE id=1";

        // prepare query
		$stmt = $this->conn->prepare($sql);

        // sanitize
        $this->contact_firstname=htmlspecialchars(strip_tags($this->contact_firstname));
		$this->contact_lastname=htmlspecialchars(strip_tags($this->contact_lastname));
        $this->contact_gender=htmlspecialchars(strip_tags($this->contact_gender));
        $this->contact_email=htmlspecialchars(strip_tags($this->contact_email));
		$this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->show_contact_name=htmlspecialchars(strip_tags($this->show_contact_name));
        $this->show_contact_email=htmlspecialchars(strip_tags($this->show_contact_email));
        $this->show_contact_number=htmlspecialchars(strip_tags($this->show_contact_number));

        // bind values
		$stmt->bindParam(':contact_firstname', $this->contact_firstname);
		$stmt->bindParam(':contact_lastname', $this->contact_lastname);
        $stmt->bindParam(':contact_gender', $this->contact_gender);
        $stmt->bindParam(':contact_email', $this->contact_email);
		$stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':show_contact_name', $this->show_contact_name);
        $stmt->bindParam(':show_contact_email', $this->show_contact_email);
        $stmt->bindParam(':show_contact_number', $this->show_contact_number);

		if($stmt->execute()){
			return true;
		}else{
            print_r($stmt->errorInfo());
        }

		return false;
	}

}
