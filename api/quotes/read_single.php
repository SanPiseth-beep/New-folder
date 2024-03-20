<?php 
  // Set headers for cross-origin and content type
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quo = new Quote($db);

  // Get ID from GET request
  $quo->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Fetch quote using the ID
  $quo->read_single();

  // Create array with quote details
  $quo_arr = array(
    'id' => $quo->id,
    'quote' => $quo->quote,
    'author' => $quo->author_name,
    'category' => $quo->category_name,
  );

  // Check if quote exists, if not, send error message
  if($quo_arr['quote']!=null){
    // Encode quote details to JSON
    $json_data = json_encode($quo_arr);

    // Decode HTML entities in JSON and echo
    echo htmlspecialchars_decode($json_data);
  } else {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }