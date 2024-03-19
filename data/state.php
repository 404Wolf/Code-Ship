<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'vendor/autoload.php';
Predis\Autoloader::register();

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'redis',  // Use the service name as the hostname                                                                                                                
    'port'   => 6379,  // Default port for Redis                                                                                                                                 
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $data = @json_decode(file_get_contents('php://input'), true);

    // Make sure that the new text is not too long
    if (strlen($data['text']) > 100000) {
        http_response_code(400);
        echo json_encode(["error" => "Input text too long"]);
        exit();
    }

    // Dump the contents to the contents file
    $client->set('contents', json_encode($data));

    http_response_code(200);
    echo json_encode(["success" => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $contents = $client->get('contents');
    if ($contents === null) {
        http_response_code(404);
        echo json_encode(["error" => "No data found"]);
        exit();
    }
    echo $contents;
}
