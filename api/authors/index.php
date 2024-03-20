<?php
  // Set headers for cross-origin and content type
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  // Get the HTTP method from the server
  $method = $_SERVER['REQUEST_METHOD'];

  // If the method is OPTIONS, set additional headers and exit
  if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
  }

  // Handle different request methods
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // If an ID is provided in the GET request, include the 'read_single' script
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      include('read_single.php');
    } else {
      // If no ID is provided, include the 'read' script
      include('read.php');
    }
  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the request method is POST, include the 'create' script
    include('create.php');
  } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // If the request method is DELETE, include the 'delete' script
    include('delete.php');
  } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // If the request method is PUT, include the 'update' script
    include('update.php');
  } else {
    // If the request method is not supported, return a 405 status code and a message
    http_response_code(405);
    echo "Method not allowed";
  }