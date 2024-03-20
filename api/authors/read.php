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

  // Execute read query
  $result = $auth->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any authors are found
  if($num > 0) {
    // Initialize array to store authors
    $auth_arr = array();

    // Fetch each row and extract details
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      // Create array with author details
      $auth_item = array(
        'id' => $id,
        'author' => $author
      );

      // Add author to array
      array_push($auth_arr, $auth_item);
    }

    // Encode array to JSON and output
    echo json_encode($auth_arr);

  } else {
    // No authors found
    echo json_encode(
      array('message' => 'No Authors Found')
    );
  }