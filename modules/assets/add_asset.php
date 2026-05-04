<?php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $branch_id = $_POST['branch_id'] ?? '';
    $status = 'Good'; // Default status

    try {
        $stmt = $pdo->prepare("INSERT INTO assets (name, category, branch_id, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $branch_id, $status]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>