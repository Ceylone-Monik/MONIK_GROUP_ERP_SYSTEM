<?php
session_start();
require_once '../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $branch_id = $_POST['branch_id'];
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    $user_id = $_SESSION['user_id'];

    // File handling
    $target_dir = "../../uploads/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    $file_name = time() . "_" . basename($_FILES["doc_file"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_file)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO documents (branch_id, title, file_path, category, expiry_date, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$branch_id, $title, $file_name, $category, $expiry_date, $user_id]);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload failed.']);
    }
}
?>