<?php 

class App {
	protected $connection;

	public function __construct() {
		$this->connection = false;
	}

	public function connect_to_db() {
		$servername = "localhost";
		$username = "db_user";
		$password = "db_pass";
		$database_name = "articles_db";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $database_name);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		$this->connection = $conn; 
	}
}
