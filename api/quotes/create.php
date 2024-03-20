<?php 
  // Set headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Connect to the database
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quo = new Quote($db);

  // Instantiate author and category objects
  $auth = new Author($db);
  $cat = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check if all required data is set, if not, send error message and exit
  if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
      echo json_encode(array('message' => 'Missing Required Parameters'));
      exit();
  }

  // Set quote, author and category data
  $quo->quote = $data->quote;
  $quo->author_id = $data->author_id;
  $quo->category_id = $data->category_id;
  $auth->id = $data->author_id;
  $cat->id = $data->category_id;

  // Check if category exists
  $cat->read_single();
  if(!$cat->category){
      echo json_encode(array('message' => 'category_id Not Found'));
      exit ();
  }

  // Check if author exists
  $auth->read_single();
  if(!$auth->author){
      echo json_encode(array('message' => 'author_id Not Found'));
      exit();
  }

  // Create quote and return the result
  if($quo->create()) {
    // Get the last inserted id
    $quo->id = $db->lastInsertId();
    $quo_arr = array(
      'id' => $quo->id,
      'quote' => $quo->quote,
      'author_id' => $quo->author_id,
      'category_id' => $quo->category_id
    );

    // Encode quote details to JSON and output
    echo json_encode($quo_arr);
  } else {
    echo json_encode(array('message' => 'Quote not created'));
  }