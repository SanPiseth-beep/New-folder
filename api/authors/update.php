<?php
  // Set headers for cross-origin, content type, and allowed methods
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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

  // Check if ID and author are set in posted data, if not, send error message and exit
  if (!isset($data->id) || !isset($data->author)) {
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
  }

  // Set author data
  $auth->id = $data->id;
  $auth->author = $data->author;

  // Create array with author details
  $auth_arr = array(
    'id' => $auth->id,
    'author' => $auth->author
  );

  // Update author and return the result
  if($auth->update()) {
    echo json_encode($auth_arr);
  } else {
    echo json_encode(array('message' => 'Author not updated'));
  }