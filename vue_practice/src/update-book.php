<?php
// Read JSON file
$jsonFile = 'data.json';
$data = json_decode(file_get_contents($jsonFile), true);

// Get POST data
$postData = json_decode(file_get_contents('php://input'), true);

// Find the index of the book to update
$index = array_search($postData['book_id'], array_column($data['bookdetails'], 'book_id'));

if ($index !== false) {
    // Update the book details
    $data['bookdetails'][$index] = $postData;

    // Write updated data back to the JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

    // Respond with success message
    http_response_code(200);
    echo json_encode(array("message" => "Book details updated successfully."));
} else {
    // Respond with error message if book not found
    http_response_code(404);
    echo json_encode(array("message" => "Book not found."));
}
?>
