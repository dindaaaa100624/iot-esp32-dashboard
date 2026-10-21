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

if (
    !is_array($data) ||
    !isset($data["suhu"], $data["kelembapan"]) ||
    !is_numeric($data["suhu"]) ||
    !is_numeric($data["kelembapan"])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak valid"
    ]);
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO sensor_data (suhu, kelembapan) VALUES (:suhu, :kelembapan)"
);
$stmt->execute([
    ":suhu" => $data["suhu"],
    ":kelembapan" => $data["kelembapan"]
]);

echo json_encode([
    "status" => "success",
    "message" => "Data berhasil disimpan",
    "id" => $pdo->lastInsertId()
]);