<?php
session_start();
require_once '../../config/db.php';

// Fetch all companies/organizations
$stmt = $pdo->query("SELECT * FROM organizations WHERE status = 'Active'");
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Organizations | MONIK Group";
include '../../includes/header.php'; 
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>🏢 MONIK Group Organizations</h2>
        </div>
        <p style="color: #7f8c8d; margin-bottom: 30px;">Select a company below to view and manage its specific branches.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px;">
            <?php foreach ($companies as $company): ?>
                <a href="view_branches.php?org_id=<?php echo $company['org_id']; ?>" style="text-decoration: none; color: inherit;">
                    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s, border-color 0.2s; border: 1px solid #eee; border-left: 6px solid #3498db;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h3 style="margin: 0; color: #2c3e50; font-size: 1.2rem;"><?php echo htmlspecialchars($company['name']); ?></h3>
                            <span style="background: #e1f5fe; color: #0288d1; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo $company['short_name']; ?></span>
                        </div>
                        
                        <p style="color: #7f8c8d; font-size: 13px; margin: 15px 0; min-height: 36px;">
                            <?php echo htmlspecialchars($company['industry'] ?? 'Service Provider'); ?>
                        </p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f5f5f5; pt-15px; margin-top: 15px;">
                            <?php
                                // Count branches for this specific organization
                                $countStmt = $pdo->prepare("SELECT COUNT(*) FROM branches WHERE org_id = ?");
                                $countStmt->execute([$company['org_id']]);
                                $branchCount = $countStmt->fetchColumn();
                            ?>
                            <span style="font-size: 13px; font-weight: 600; color: #34495e;">📍 <?php echo $branchCount; ?> Branches</span>
                            <span style="color: #3498db; font-weight: bold; font-size: 13px;">Manage →</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    a:hover div {
        transform: translateY(-5px);
        border-color: #3498db !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
</style>

</body>
</html>