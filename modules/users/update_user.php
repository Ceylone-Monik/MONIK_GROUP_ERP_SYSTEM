<?php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    exit;
}

$userId = (int) ($_POST['user_id'] ?? 0);
$fullName = trim((string) ($_POST['full_name'] ?? ''));
$username = trim((string) ($_POST['username'] ?? ''));
$roleId = (int) ($_POST['role_id'] ?? 0);
$branchRaw = $_POST['branch_id'] ?? '';
$branchId = $branchRaw === '' || $branchRaw === null ? null : (int) $branchRaw;
$password = (string) ($_POST['password'] ?? '');

if ($userId < 1 || $fullName === '' || $username === '' || $roleId < 1) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid fields']);
    exit;
}

try {
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'UPDATE users SET username = ?, full_name = ?, role_id = ?, branch_id = ?, password_hash = ? WHERE user_id = ?'
        );
        $stmt->execute([$username, $fullName, $roleId, $branchId, $hash, $userId]);
    } else {
        $stmt = $pdo->prepare(
            'UPDATE users SET username = ?, full_name = ?, role_id = ?, branch_id = ? WHERE user_id = ?'
        );
        $stmt->execute([$username, $fullName, $roleId, $branchId, $userId]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
