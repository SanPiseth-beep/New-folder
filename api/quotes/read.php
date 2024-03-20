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

  // Check if author_id or category_id is set in GET request
  if (isset($_GET['author_id'])){
    $quo->author_id = $_GET['author_id'];
  }
  if (isset($_GET['category_id'])){
    $quo->category_id = $_GET['category_id'];
  }

  // Execute read query
  $result = $quo->read();

  // Get row count
  $num = $result->rowCount();

  // Check if any quotes are found
  if($num > 0) {
    // Initialize array to store quotes
    $quo_arr = array();

    // Fetch each row and extract details
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      // Create array with quote details
      $quo_item = array(
        'id' => $id,
        'quote' => $quote,
        'author' => $author_name,
        'category' => $category_name
      );

      // Add quote to array
      array_push($quo_arr, $quo_item);
    }

    // Encode array to JSON and decode HTML entities before output
    echo htmlspecialchars_decode(json_encode($quo_arr));
  } else {
    // No quotes found
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }