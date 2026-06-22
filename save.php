<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Define password
$admin_password = 'ape2026';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate password
    if (!isset($data['password']) || $data['password'] !== $admin_password) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized: Wrong password"]);
        exit;
    }

    // Save data to site-data.json
    if (isset($data['siteData'])) {
        $file_path = 'site-data.json';
        $result = file_put_contents($file_path, json_encode($data['siteData'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
        if ($result !== false) {
            echo json_encode(["success" => true, "message" => "Data saved successfully!"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to write to file. Check file permissions."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "No data provided."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
}
?>
