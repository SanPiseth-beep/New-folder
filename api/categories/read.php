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

  // Execute read query
  $result = $cat->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any categories are found
  if($num > 0) {
    // Initialize array to store categories
    $cat_arr = array();

    // Fetch each row and extract details
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      // Create array with category details
      $cat_item = array(
        'id' => $id,
        'category' => $category
      );

      // Add category to array
      array_push($cat_arr, $cat_item);
    }

    // Encode array to JSON and output
    echo json_encode($cat_arr);

  } else {
    // No categories found
    echo json_encode(
      array('message' => 'No Categories Found')
    );
  }