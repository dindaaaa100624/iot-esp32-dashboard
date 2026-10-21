<?php
header("Content-Type: application/json");

require_once "../config.php";

try {
    $stmt = $pdo->query(
        "SELECT id, suhu, kelembapan, waktu FROM sensor_data ORDER BY id DESC LIMIT 1"
    );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode([
            "status" => "empty",
            "message" => "Belum ada data"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal membaca database"
    ]);
}