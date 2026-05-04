<?php
session_start();
require_once '../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = $_POST['branch_id'];
    $score = $_POST['score'];
    $audit_date = $_POST['audit_date'];
    $comments = $_POST['comments'] ?? '';
    $auditor_id = $_SESSION['user_id']; // Current logged-in user

    try {
        $stmt = $pdo->prepare("INSERT INTO audits (branch_id, auditor_id, score, comments, audit_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$branch_id, $auditor_id, $score, $comments, $audit_date]);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>