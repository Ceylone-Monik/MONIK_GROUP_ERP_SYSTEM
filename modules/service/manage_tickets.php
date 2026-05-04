<?php
require_once '../../config/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'create') {
    $asset_id = $_POST['asset_id'];
    $issue = $_POST['issue'];
    $priority = $_POST['priority'];
    $assigned_to = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : NULL;

    $stmt = $pdo->prepare("INSERT INTO tickets (asset_id, issue_description, priority, assigned_to) VALUES (?, ?, ?, ?)");
    $stmt->execute([$asset_id, $issue, $priority, $assigned_to]);
    echo json_encode(['status' => 'success']);

} elseif ($action === 'update') {
    $id = $_POST['ticket_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE ticket_id = ?");
    $stmt->execute([$status, $id]);
    echo json_encode(['status' => 'success']);
}
?>