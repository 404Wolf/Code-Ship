<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $data = @json_decode(file_get_contents('php://input'), true);

    // Make sure that the timestamp is of the correct format
    try {
        if (!isset($data['date']) || !is_numeric($data['date'])) {
            throw new Exception('Date must be a numeric timestamp');
        }
        $data = ["text" => $data['text'], "date" => $data['date']];
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
        exit();
    }

    // Make sure that the new text is not too long
    if (count_chars($data['text']) > 100000) {
        http_response_code(400);
        echo json_encode(["error" => "Input text too long"]);
    }

    // Dump the contents to the contents file
    file_put_contents("contents.json", json_encode($data));

    http_response_code(200);
    echo json_encode(["success" => true]);
}
