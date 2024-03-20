<?php
  // Set headers for cross-origin and content type
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  // Include database and model files
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $cat = new Category($db);

  // Get ID from GET request
  $cat->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Fetch category using the ID
  $cat->read_single();

  // Create array with category details
  $category_arr = array(
    'id' => $cat->id,
    'category' => $cat->category
  );

  // Check if category exists, if not, send error message
  if($category_arr['category']!=null){
    // Encode category details to JSON
    echo json_encode($category_arr);
  } else {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
  }