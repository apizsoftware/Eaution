<?php
class Message {

	// database connection and table name
	private $conn;
	private $table_name = "messages";

	// object properties
	public $id;
	public $name;
	public $email;
	public $subject;
	public $message;
	public $status;
	public $created;

	// constructor
	public function __construct($db){
		$this->conn = $db;
	}

	function sendEmailToAdmin($utils, $setting){

		$subject="{$this->subject} | Website Contact Form";

		$body.="Subject: {$this->subject}<br /><br />";
		$body.="Message: {$this->message}<br /><br />";
		$body.="This message was sent using the contact form on your website.";

		// using sendEmailViaPhpMailerLibrary() needs your google account credentials in /libs/php/utils.php
		// $utils->sendEmailViaPhpMailerLibrary($this->name, $this->email, $send_to_email, $subject, $body)
		if($utils->sendEmailViaPhpMail(
			$this->name,
			$this->email,
			$setting->contact_email,
			$subject,
			$body
		)){
			return true;
		}

		return false;
	}

	function sendEmailToUser($utils, $setting){

		$subject="{$this->name}, we got you message!";

		$body="Hello {$this->name},<br /><br />";
		$body.="Thank you for sending a message. We will contact you as soon as possible.<br /><br />";
		$body.="Kind Regards,<br />";
		$body.="{$setting->contact_firstname} {$setting->contact_lastname}";

		if($utils->sendEmailViaPhpMail(
			$setting->contact_firstname,
			$setting->contact_email,
			$this->email,
			$subject,
			$body
		)){
			return true;
		}

		return false;
	}

	// send message
	public function create(){

		// insert query
		$query="INSERT INTO " . $this->table_name . "
				  SET name=:name, email=:email, subject=:subject, message=:message,  created=:created";

		// prepare the query
		$stmt=$this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags(trim($this->name)));
		$this->email=htmlspecialchars(strip_tags(trim($this->email)));
		$this->subject=htmlspecialchars(strip_tags(trim($this->subject)));
		$this->message=htmlspecialchars(strip_tags(trim($this->message)));

		// bind values
		$stmt->bindParam(":name" , $this->name);
		$stmt->bindParam(":email" , $this->email);
		$stmt->bindParam(":subject", $this->subject);
		$stmt->bindParam(":message", $this->message);

		$this->created=date("Y-m-d H:i:s");
		$stmt->bindParam(":created", $this->created);

		if($stmt->execute()){
			return true;
		}

		return false;
	}



	// read all message
	function read($from_record_num, $records_per_page){

		// select query
		$query="SELECT * FROM " . $this->table_name . "
				ORDER BY id desc
				LIMIT ?, ?";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// bind values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	// count all unread messages
	public function countUnread(){

		// select all message
	    $query = "SELECT id FROM " . $this->table_name . " WHERE status=0";

		// prepare query
	    $stmt = $this->conn->prepare( $query );

		// execute query
	    $stmt->execute();

		// count records returned
	    $num = $stmt->rowCount();

	    return $num;
	}


	// count all messages
	public function count(){

		// select all message
	    $query = "SELECT id FROM " . $this->table_name . "";

		// prepare query
	    $stmt = $this->conn->prepare( $query );

		// execute query
	    $stmt->execute();

		// count records returned
	    $num = $stmt->rowCount();

	    return $num;
	}

	// delete message
	function delete(){

		// delete query
		$query="DELETE FROM " . $this->table_name . " WHERE id = ?";

		// prepare query
		$stmt=$this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind id of record to be deleted
		$stmt->bindParam(1, $this->id);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}



	// view message details
	function readOne(){

		// read one record query
		$sql="SELECT * FROM " . $this->table_name .  " WHERE id = ? LIMIT 0,1";

		// prepare query
		$stmt = $this->conn->prepare($sql);

		// bind id of record to be deleted
		$stmt->bindParam(1, $this->id);

		// execute query
		$stmt->execute();

		// read row
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id=$rows['id'];
		$this->name=$rows['name'];
		$this->email=$rows['email'];
		$this->subject=$rows['subject'];
		$this->message=$rows['message'];
		$this->status=$rows['status'];
		$this->created=$rows['created'];
	}


	// change message status
	function changeStatus()	{

		// update query
		$sql="UPDATE " . $this->table_name . "
				SET status = 1 where id = ?";

		// prepare query
		$stmt=$this->conn->prepare($sql);

		// bind id of record to be updated
		$stmt->bindParam(1, $this->id);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// search message
	function search($search_term, $from_record_num, $records_per_page)	{

		// search query
		$query="SELECT * FROM  " . $this->table_name . "
				 WHERE
					 name LIKE :name OR
					 email LIKE :email OR
					 subject LIKE :subject
					 OR message LIKE :message
				 ORDER BY id desc
				 LIMIT :from_record_num, :records_per_page";

		// prepare query
		$stmt=$this->conn->prepare($query);

		// sanitize
		$search_term=htmlspecialchars(strip_tags($search_term));

		// search term
		$search_term = "%{$search_term}%";

		// bind search term
		$stmt->bindParam(":name", $search_term);
		$stmt->bindParam(":email", $search_term);
		$stmt->bindParam(":subject", $search_term);
		$stmt->bindParam(":message", $search_term);
		$stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	// cound search message
	function count_BySearch($search_term) {

		// search query
		$query="SELECT * FROM  " .$this->table_name ."
				 WHERE
					 name LIKE :name OR
					 email LIKE :email OR
					 subject LIKE :subject
					 OR message LIKE :message
				 ORDER BY id desc";

		// prepare query
   		$stmt = $this->conn->prepare( $query );

		// sanitize
		$search_term=htmlspecialchars(strip_tags($search_term));

		// search term
		$search_term = "%{$search_term}%";

		// bind search term
		$stmt->bindParam(":name", $search_term);
		$stmt->bindParam(":email", $search_term);
		$stmt->bindParam(":subject", $search_term);
		$stmt->bindParam(":message", $search_term);

		// execute query
		$stmt->execute();

		// count return rows
		$num = $stmt->rowCount();

		// return count
		return $num;
	}

	// search by date range
	function search_ByDateRange($date_from, $date_to, $from_record_num, $records_per_page){

		// search query
		$sql = "SELECT * FROM " . $this->table_name . "
					WHERE
						created BETWEEN :date_from AND :date_to
						OR created LIKE :date_from_query
						OR created LIKE :date_to_query
					ORDER BY created desc
					LIMIT :from_record_num, :records_per_page";

		// prepare query
		$stmt = $this->conn->prepare($sql);

		// sanitize
		$date_from=htmlspecialchars(strip_tags($date_from_query));
		$date_to=htmlspecialchars(strip_tags($date_to_query));
		$from_record_num=htmlspecialchars(strip_tags($from_record_num));
		$records_per_page=htmlspecialchars(strip_tags($records_per_page));

		// bind values
		$stmt->bindParam(":date_from", $date_from);
		$stmt->bindParam(":date_to", $date_to);

		$date_from_query = "%{$date_from}%";
		$date_to_query  = "%{$date_to}%";

		$stmt->bindParam(":date_from_query", $date_from_query);
		$stmt->bindParam(":date_to_query", $date_to_query);

		$stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values
		return $stmt;
	}

	// count all by date range
	function count_ByDateRange($date_to , $date_from)	{

		// search query
		$sql = "SELECT * FROM " . $this->table_name . "
					WHERE
						created BETWEEN :date_from AND :date_to
						OR created LIKE :date_from_query
						OR created LIKE :date_to_query
					ORDER BY created desc";

		// prepare query
		$query = $this->conn->prepare($sql);

		// sanitize
		$date_from=htmlspecialchars(strip_tags($date_from_query));
		$date_to=htmlspecialchars(strip_tags($date_to_query));

		// bind values
		$stmt->bindParam(":date_from", $date_from);
		$stmt->bindParam(":date_to", $date_to);

		$date_from_query = "%{$date_from}%";
		$date_to_query  = "%{$date_to}%";

		$stmt->bindParam(":date_from_query", $date_from_query);
		$stmt->bindParam(":date_to_query", $date_to_query);

		// execute query
		$query->execute();

		// count returned values
		$num = $query->rowCount();

		// return values
		return $num;

	}

}
