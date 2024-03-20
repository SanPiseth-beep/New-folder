<?php
  // Set headers for cross-origin and content type
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $auth = new Author($db);

  // Get ID from GET request
  $auth->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Fetch author using the ID
  $auth->read_single();

  // Create array with author details
  $auth_arr = array(
    'id' => $auth->id,
    'author' => $auth->author
  );

  // Check if author exists, if not, send error message
  if($auth_arr['author']!=null){
    // Encode author details to JSON
    echo json_encode($auth_arr);
  } else {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  }