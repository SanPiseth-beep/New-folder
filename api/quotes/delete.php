<?php
  // Set headers for DELETE request and allowed headers
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quo = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID of the quote to delete
  $quo->id = $data->id;

  // Initialize cURL session to fetch quote by ID
  $test = curl_init('http://localhost/api/quotes/?id=' . $quo->id);
  curl_setopt($test, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($test);
  curl_close($test);
  $test2 = array_values(json_decode($response,true));

  // Check if quote exists, if not, send error message and exit
  if($test2[0] != $quo->id){
    echo json_encode(array(
        'message' => 'No Quotes Found'
    ));
    exit();
  }

  // Delete quote and return the result
  if($quo->delete()) {
    echo json_encode(
      array('id' => $quo->id)
    );
  }
?>