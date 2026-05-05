<?php
session_start();
require_once '../../config/db.php';

// Fetch all active companies
$companies = $pdo->query("SELECT * FROM organizations WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Asset Organizations | MONIK Group";
include '../../includes/header.php'; 
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    
    <div class="container" style="padding: 20px;">
        <h2>📦 Asset Management</h2>
        <p style="color: #7f8c8d; margin-bottom: 30px;">Select an organization to manage its physical assets and inventory.</p>

        <!-- Company Cards Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">
            <?php foreach ($companies as $company): ?>
                <a href="view_assets.php?org_id=<?php echo $company['org_id']; ?>" style="text-decoration: none; color: inherit;">
                    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee; border-left: 6px solid #6c5ce7;">
                        <h3 style="margin: 0; color: #2c3e50;"><?php echo htmlspecialchars($company['name']); ?></h3>
                        <p style="color: #7f8c8d; font-size: 13px; margin: 10px 0;"><?php echo htmlspecialchars($company['industry']); ?></p>
                        
                        <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <?php
                                // Count assets across all branches of this company
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM assets a JOIN branches b ON a.branch_id = b.branch_id WHERE b.org_id = ?");
                                $stmt->execute([$company['org_id']]);
                                $assetCount = $stmt->fetchColumn();
                            ?>
                            <span style="font-size: 13px; font-weight: 600;">📦 <?php echo $assetCount; ?> Assets</span>
                            <span style="color: #6c5ce7; font-weight: bold; font-size: 13px;">View Inventory →</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>