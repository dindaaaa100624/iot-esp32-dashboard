<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Gunakan POST"
    ]);
    exit;
}

require_once "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || !isset($data["status"]) ||
    !in_array($data["status"], ["ON", "OFF"], true)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Status tidak valid"
    ]);
    exit;
}

$pdo->prepare("UPDATE kontrol_led SET status_led = :s WHERE id = 1")
    ->execute([":s" => $data["status"]]);

echo json_encode([
    "status" => "success",
    "message" => "Status LED disimpan"
]);