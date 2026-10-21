<?php
header("Content-Type: application/json");
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $mode = $data["mode"] ?? null;
    $perintah = $data["perintah"] ?? null;

    $modeValid = $mode === null || in_array($mode, ["manual", "otomatis"], true);
    $perintahValid = $perintah === null || in_array($perintah, ["ON", "OFF"], true);

    if (!is_array($data) || !$modeValid || !$perintahValid ||
        ($mode === null && $perintah === null)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Data tidak valid"
        ]);
        exit;
    }

    if ($mode !== null) {
        $pdo->prepare("UPDATE kontrol_led SET mode = :m WHERE id = 1")
            ->execute([":m" => $mode]);
    }
    if ($perintah !== null) {
        $pdo->prepare("UPDATE kontrol_led SET perintah = :p WHERE id = 1")
            ->execute([":p" => $perintah]);
    }
}

$row = $pdo->query(
    "SELECT mode, perintah, status_led, waktu FROM kontrol_led WHERE id = 1"
)->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $row
]);