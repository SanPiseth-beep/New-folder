<?php 
  // Set headers for PUT request and allowed headers
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quo = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check if all required data is set, if not, send error message and exit
  if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
  }

  // Set quote data
  $quo->id = $data->id;
  $quo->quote = $data->quote;
  $quo->author_id = $data->author_id;
  $quo->category_id = $data->category_id;

  // Instantiate author and category objects
  $auth = new Author($db);
  $cat = new Category($db);
  $auth->id = $quo->author_id;
  $cat->id = $quo->category_id;

  // Check if category exists
  $cat->read_single();
  if(!$cat->category){
    echo json_encode(array('message' => 'category_id Not Found'));
    exit();
  }

  // Check if author exists
  $auth->read_single();
  if(!$auth->author){
    echo json_encode(array('message' => 'author_id Not Found'));
    exit();
  }

  // Initialize cURL session to fetch quote by ID
  $test = curl_init('http://localhost/api/quotes/?id=' . $quo->id);
  curl_setopt($test, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($test);
  curl_close($test);
  $test2 = array_values(json_decode($response,true));

  // Check if quote exists, if not, send error message and exit
  if($test2[0] != $quo->id){
    echo json_encode(array('message' => 'No Quotes Found'));
    exit();
  }

  // Update quote and return the result
  if($quo->update()) {
    $quo_arr = array(
      'id' => $quo->id,
      'quote' => $quo->quote,
      'author_id' => $quo->author_id,
      'category_id' => $quo->category_id,
    );
    echo json_encode($quo_arr);
  }