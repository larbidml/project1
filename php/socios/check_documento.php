<?php
header('Content-Type: application/json');
require_once '../resource/Database.php';

$documento = $_GET['documento'] ?? $_POST['documento'] ?? null;

if (empty($documento)) {
    echo json_encode(['exists' => false]);
    exit;
}

try {
    $sql = "SELECT COUNT(*) FROM socios WHERE documento = :documento";
    $stmt = $db->prepare($sql);
    $stmt->execute([':documento' => $documento]);
    $count = $stmt->fetchColumn();

    echo json_encode(['exists' => ($count > 0)]);
} catch (PDOException $e) {
    error_log("Error check_documento: " . $e->getMessage());
    echo json_encode(['exists' => false, 'error' => 'db']);
}
