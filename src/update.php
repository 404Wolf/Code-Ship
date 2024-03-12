<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body                                        
    $data = json_decode(file_get_contents('php://input'), true);

    // Dump the contents to the contents file
    file_put_contents("contents.json", json_encode($data));

    http_response_code(200);
    echo json_encode(["success" => true]);
}
?>