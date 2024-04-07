<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'functions.php';
include '../config/connectionFile.php';
include('../config/session.php');




$request_method = $_SERVER['REQUEST_METHOD'];
// echo "Happy ";
// print_r($_SERVER);
// die();
// $path_info = $_SERVER['PATH_INFO'] ?? '';

// Access the path_info
$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
// print($conn);die();

// print_r($request_method);
// print_r($path_info);
// die();

switch ($request_method) {
    case 'GET':

        if ($path_info === '/accounts') {

            header('Content-Type: application/json');
            echo json_encode(get_accounts($conn));

        } elseif ($path_info === '/users'){
            
            $data = [] ;
            header('Content-Type: application/json');
            echo json_encode(get_customers($conn));

        } elseif ($path_info === '/accounts'){

            $data = json_decode(file_get_contents('php://input'), true);
            header('Content-Type: application/json');
            echo json_encode(get_customersTransaction($conn, $data));

        }

        else if ($path_info === '/books') {

            header('Content-Type: application/json');
            echo json_encode(get_books());

        } elseif (preg_match('/\/books\/(\d+)/', $path_info, $matches)) {
            $id = intval($matches[1]);
            // print_r($id);
            // die();
            $book = get_book($id);

            if ($book) {
                header('Content-Type: application/json');
                echo json_encode($book);
            } else {
                http_response_code(404);
                echo 'Not Found';
            }
        }
        break;
    case 'POST':
        if ($path_info === '/accounts') {

            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(add_transaction($conn,$data));
            http_response_code(201); // Created

        }elseif ($path_info === '/users') {

            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(add_user($conn,$data));
            http_response_code(201); // Created
            




        }elseif ($path_info === '/books') {

            $data = json_decode(file_get_contents('php://input'), true);
            // print_r($data);die();
            add_book($data);
            http_response_code(201); // Created
        }
        break;
   case 'PUT':
    if (preg_match('/\/books\/(\d+)/', $path_info, $matches)) {
        $id = intval($matches[1]);
        $data = json_decode(file_get_contents('php://input'), true);
        if (update_book($id, $data)) {
            http_response_code(204); // No Content
        } else {
            http_response_code(404);
            echo 'Not Found';
        }
    }
    break;

    case 'DELETE':
        if (preg_match('/\/books\/(\d+)/', $path_info, $matches)) {
            $id = intval($matches[1]);
            
            if (delete_book($id)) {
                http_response_code(204); // No Content
            } else {
                http_response_code(404);
                echo 'Not Found';
            }
        }
        break;
        case 'OPTIONS':
        // Respond with a 200 status code
        http_response_code(200);
        // Set additional CORS headers if needed
        // For example:
        // header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo 'Method Not Allowed';
        break;
}
