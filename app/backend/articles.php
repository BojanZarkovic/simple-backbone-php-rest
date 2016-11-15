<?php

class Articles extends App {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Creates a new db entry based on the posted data.
	 * Returns the newly created model
	 */
	public function add_new_post() {
		$article_model = json_decode($_POST['model']);
		$done = $article_model->done ? 1 : 0;

		// Call parent method to make a db connection
		$this->connect_to_db();

		$sql = "INSERT INTO articles (name, done) VALUES ('" . $article_model->name . "', '" . $done . "');";

		if ($this->connection->query($sql) === TRUE) {
			$new_article_id = $this->connection->insert_id;
			$response = array(
				"id" 	=> $new_article_id,
				"name" 	=> $article_model->name,
				"done" 	=> $article_model->done,
			);
		    echo json_encode($response);
		} else {
		    echo "Error: " . $sql . "<br>" . $this->connection->error;
		}

		$this->connection->close();
	}

	public function edit_post() {
		$id = $_GET['id'];
		$article_model = json_decode($_POST['model']);
		$done = $article_model->done ? 1 : 0;

		// Call parent method to make a db connection
		$this->connect_to_db();
		$sql = "UPDATE articles SET name = '" . $article_model->name . "', done = '" . $done . "' WHERE id = " . $id . ";";

		if ($this->connection->query($sql) === TRUE) {
			$response = array(
				"id" 	=> $id,
				"name" 	=> $article_model->name,
				"done" 	=> $article_model->done,
			);
		    echo json_encode($response);
		} else {
			 echo "Error: " . $sql . "<br>" . $this->connection->error;
		}

		$this->connection->close();
	}

	public function delete_post() {
		$id = $_GET['id'];

		$this->connect_to_db();

		$sql = "DELETE FROM articles WHERE id = " .$id. ";" ;

		if ($this->connection->query($sql) === TRUE) {
		    $response = array(
				"id" => $id
			);
		    echo json_encode($response);
		} else {
		    echo "Error: " . $sql . "<br>" . $this->connection->error;
		}
		$this->connection->close();
	}

	public function get_post() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$where = " WHERE id = " . $id;
		} else {
			$articles = array();
			$where = "";
		}

		$this->connect_to_db();

		$sql = "SELECT * FROM articles" . $where ;

		if ($this->connection->query($sql) === FALSE) {
		    echo "Error: " . $sql . "<br>" . $this->connection->error;
		} 

		$result = $this->connection->query($sql);

		if ($result->num_rows > 1) {
		    while($article_obj = $result->fetch_object()) {
		        $articles[] = $article_obj;
		    }
		    echo json_encode($articles);
		} else if ($result->num_rows == 1) {
			$article_obj = $result->fetch_object();
		    $response = array(
				"id" 	=> $id,
				"name" 	=> $article_obj->name,
				"done" 	=> $article_obj->done,
			);
		    echo json_encode($response);
		} else {
			echo "0 results";
		}

		$this->connection->close();
	}
}
