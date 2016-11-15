<?php

$request_method = $_SERVER['REQUEST_METHOD'];
// Backbone.emulateHTTP
if ($request_method == "POST" && isset($_POST["_method"])) {
	$request_method = $_POST["_method"];
}

switch($request_method) {
	case 'POST':
		add_new_post();
		break;
	case 'GET':
		get_post();
		break;
	case 'DELETE':
		delete_post();
		break;
	case 'PUT':
		edit_post();
		break;
	default:
		die("Unknown request.");
}

function connect_to_db() {
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
	return $conn;
}

/**
 * Creates a new Muppet model based on the posted data.
 * Returns the newly created model
 */
function add_new_post() {
	$article_model = json_decode($_POST['model']);
	$done = $article_model->done ? 1 : 0;

	$conn = connect_to_db();
	$sql = "INSERT INTO articles (name, done) VALUES ('" . $article_model->name . "', '" . $done . "');";

	if ($conn->query($sql) === TRUE) {
		$new_article_id = $conn->insert_id;
		$response = array(
			"id" => $new_article_id,
			"name" => $article_model->name,
			"done" => $article_model->done,
		);
	    echo json_encode($response);
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
}

function edit_post() {
	$id = $_GET['id'];
	$article_model = json_decode($_POST['model']);
	$done = $article_model->done ? 1 : 0;

	$conn = connect_to_db();
	$sql = "UPDATE articles SET name = '" . $article_model->name . "', done = '" . $done . "' WHERE id = " . $id . ";";

	if ($conn->query($sql) === TRUE) {
		$response = array(
			"id" => $id,
			"name" => $article_model->name,
			"done" => $article_model->done,
		);
	    echo json_encode($response);
	} else {
		 echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();

}

function delete_post() {
	$id = $_GET['id'];

	$conn = connect_to_db();

	$sql = "DELETE FROM articles WHERE id = " .$id. ";" ;

	if ($conn->query($sql) === TRUE) {
	    $response = array(
			"id" => $id
		);
	    echo json_encode($response);
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
}

function get_post() {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$where = " WHERE id = " . $id;
	} else {
		$articles = array();
		$where = "";
	}

	$conn = connect_to_db();

	$sql = "SELECT * FROM articles" . $where ;

	if ($conn->query($sql) === FALSE) {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	} 

	$result = $conn->query($sql);

	if ($result->num_rows > 1) {
	    while($article_obj = $result->fetch_object()) {
	        $articles[] = $article_obj;
	    }
	    echo json_encode($articles);
	} else if ($result->num_rows == 1) {
		$article_obj = $result->fetch_object();
	    $response = array(
			"id" => $id,
			"name" => $article_obj->name,
			"done" => $article_obj->done,
		);
	    echo json_encode($response);
	} else {
		echo "0 results";
	}

	$conn->close();
}
