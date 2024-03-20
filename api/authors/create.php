<?php 
  // Set headers for cross-origin, content type, and allowed methods
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $auth = new Author($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check if author is set in posted data, if not, send error message and exit
  if (!isset($data->author)) {
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
  }

  // Set author data
  $auth->author = $data->author;

  // Create author and return the result
  if($auth->create()) {
    echo json_encode(array('id' => $db->lastInsertId(), 'author' => $auth->author));
  }