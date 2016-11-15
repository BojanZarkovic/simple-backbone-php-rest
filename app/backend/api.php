<?php 

include_once('app.php');
include_once('articles.php');

$request_method = $_SERVER['REQUEST_METHOD'];
// Backbone.emulateHTTP
if ($request_method == "POST" && isset($_POST["_method"])) {
	$request_method = $_POST["_method"];
}

$articles = new Articles();

switch($request_method) {
	case 'POST':
		$articles->add_new_post();
		break;
	case 'GET':
		$articles->get_post();
		break;
	case 'DELETE':
		$articles->delete_post();
		break;
	case 'PUT':
		$articles->edit_post();
		break;
	default:
		die("Unknown request.");
}
