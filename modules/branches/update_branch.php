<?php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    exit;
}

$branchId = (int) ($_POST['branch_id'] ?? 0);
$name = trim((string) ($_POST['name'] ?? ''));
$region = trim((string) ($_POST['region'] ?? ''));
$orgId = (int) ($_POST['org_id'] ?? 0);
$status = $_POST['status'] ?? 'Active';

if ($branchId < 1 || $name === '' || $region === '' || $orgId < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid fields']);
    exit;
}

if (!in_array($status, ['Active', 'Inactive'], true)) {
    $status = 'Active';
}

try {
    $stmt = $pdo->prepare(
        'UPDATE branches SET org_id = ?, name = ?, region = ?, status = ? WHERE branch_id = ?'
    );
    $stmt->execute([$orgId, $name, $region, $status, $branchId]);
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
