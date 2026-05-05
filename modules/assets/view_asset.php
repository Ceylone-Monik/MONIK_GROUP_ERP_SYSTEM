<?php
require_once '../../config/db.php';

// Get the Asset ID from the URL
$asset_id = $_GET['id'] ?? null;

if (!$asset_id) {
    die("Error: Asset ID is missing.");
}

// Fetch details for this specific asset
$stmt = $pdo->prepare("SELECT a.*, b.name as branch_name FROM assets a 
                       JOIN branches b ON a.branch_id = b.branch_id 
                       WHERE a.asset_id = ?");
$stmt->execute([$asset_id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    die("Error: Asset not found in the Monik Group database.");
}

$pageTitle = "Asset Info: " . htmlspecialchars($asset['name']);
include '../../includes/header.php';
?>

<style>
    .detail-card {
        background: white; 
        padding: 30px; 
        border-radius: 15px; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.1); 
        max-width: 450px; 
        margin: 50px auto; 
        text-align: center;
    }
    .info-row { text-align: left; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
    .status-active { color: #27ae60; font-weight: bold; }
</style>

<div class="main-wrapper" style="margin-left: 0; width: 100%;">
    <div class="detail-card">
        <h2 style="color: #2c3e50;">📦 Asset Details</h2>
        <p style="color: #7f8c8d;">Monik Group Inventory System</p>
        <hr>
        
        <div class="info-row">
            <strong>Asset ID:</strong> #<?php echo $asset['asset_id']; ?>
        </div>
        <div class="info-row">
            <strong>Item Name:</strong> <?php echo htmlspecialchars($asset['name']); ?>
        </div>
        <div class="info-row">
            <strong>Category:</strong> <?php echo htmlspecialchars($asset['category']); ?>
        </div>
        <div class="info-row">
            <strong>Assigned Branch:</strong> <?php echo htmlspecialchars($asset['branch_name']); ?>
        </div>
        <div class="info-row">
            <strong>Status:</strong> <span class="status-active">● <?php echo $asset['status']; ?></span>
        </div>

        <br>
        <button onclick="window.close()" style="width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer;">Done</button>
    </div>
</div>
</body>
</html>