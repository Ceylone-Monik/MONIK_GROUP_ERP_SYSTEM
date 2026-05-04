<?php
require_once '../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];
    $branch_id = !empty($_POST['branch_id']) ? $_POST['branch_id'] : NULL;

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, full_name, role_id, branch_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $full_name, $role_id, $branch_id]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>