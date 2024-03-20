<?php
  // Set headers for DELETE request and allowed headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $cat = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID of the category to delete
  $cat->id = $data->id;

  // Initialize cURL session to fetch category by ID
  $test = curl_init('http://localhost/api/categories/?id=' . $cat->id);
  curl_setopt($test, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($test);
  curl_close($test);
  $test2 = array_values(json_decode($response,true));

  // Check if category exists, if not, send error message and exit
  if($test2[0] != $cat->id){
    echo json_encode(array('message' => 'No Category Found'));
    exit();
  }

  // Delete category and return the result
  if($cat->delete()) {
    echo json_encode(array('id' => $cat->id));
  } 