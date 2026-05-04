<?php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $region = $_POST['region'] ?? '';
    $org_id = $_POST['org_id'] ?? 1;
    $status = $_POST['status'] ?? 'Active';

    if (!empty($name) && !empty($region)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO branches (org_id, name, region, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$org_id, $name, $region, $status]);
            
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    }
}
?>