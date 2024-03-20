<?php
  // Set headers for cross-origin, content type, and allowed methods
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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

  // Check if ID and category are set in posted data, if not, send error message and exit
  if (!isset($data->id) || !isset($data->category)) {
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
  }

  // Set category data
  $cat->id = $data->id;
  $cat->category = $data->category;

  // Create array with category details
  $category_arr = array(
    'id' => $cat->id,
    'category' => $cat->category
  );

  // Update category and return the result
  if($cat->update()) {
    echo json_encode($category_arr);
  } else {
    echo json_encode(array('message' => 'Category not updated'));
  }