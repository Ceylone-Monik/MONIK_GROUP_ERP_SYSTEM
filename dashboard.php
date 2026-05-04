<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
require_once 'config/db.php';

// 1. KPI Data Extraction
$totalBranches = $pdo->query("SELECT COUNT(*) FROM branches")->fetchColumn();
$totalAssets = $pdo->query("SELECT COUNT(*) FROM assets")->fetchColumn();
$openTickets = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status != 'Completed'")->fetchColumn();
$avgAudit = $pdo->query("SELECT AVG(score) FROM audits")->fetchColumn() ?: 0;

// 2. Chart Data: Audit scores over time
$chartQuery = $pdo->query("SELECT audit_date, score FROM audits ORDER BY audit_date ASC LIMIT 10");
$chartDates = [];
$chartScores = [];
while($row = $chartQuery->fetch(PDO::FETCH_ASSOC)) {
    $chartDates[] = $row['audit_date'];
    $chartScores[] = $row['score'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Executive Dashboard | Monik Group</title>
    <!-- Chart.js for Visual Reports[cite: 1] -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin: 0; display: flex; font-family: 'Segoe UI', sans-serif; background: #f4f7f6; }
        .main-wrapper { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        
        /* KPI Cards Styling */
        .kpi-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .kpi-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #3498db; }
        .kpi-card h3 { font-size: 24px; margin: 10px 0; color: #2c3e50; }
        .kpi-card p { color: #7f8c8d; font-size: 14px; margin: 0; font-weight: bold; }
        
        .chart-section { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .alerts-panel { margin-top: 30px; background: #fff; padding: 20px; border-radius: 10px; border-left: 5px solid #e74c3c; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <h1>Welcome, <?php echo $_SESSION['full_name']; ?> 🚀</h1>
        <p>Real-time overview of Monik Group operations.</p>

        <!-- KPI Row[cite: 1] -->
        <div class="kpi-container">
            <div class="kpi-card" style="border-color: #3498db;">
                <p>TOTAL BRANCHES</p>
                <h3><?php echo $totalBranches; ?></h3>
            </div>
            <div class="kpi-card" style="border-color: #f1c40f;">
                <p>TOTAL ASSETS</p>
                <h3><?php echo $totalAssets; ?></h3>
            </div>
            <div class="kpi-card" style="border-color: #e74c3c;">
                <p>OPEN TICKETS</p>
                <h3><?php echo $openTickets; ?></h3>
            </div>
            <div class="kpi-card" style="border-color: #27ae60;">
                <p>AVG AUDIT SCORE</p>
                <h3><?php echo round($avgAudit, 1); ?>%</h3>
            </div>
        </div>

        <!-- Charts Section[cite: 1] -->
        <div class="chart-section">
            <h3>📈 Audit Performance Trend</h3>
            <canvas id="auditChart" height="100"></canvas>
        </div>

        <!-- Alerts Panel[cite: 1] -->
        <div class="alerts-panel">
            <h4 style="margin:0; color: #e74c3c;">🚨 System Alerts</h4>
            <ul style="margin: 10px 0 0 0; font-size: 14px;">
                <?php if($openTickets > 5): ?>
                    <li>High volume of open service tickets. Assign more technicians.</li>
                <?php endif; ?>
                <?php if($avgAudit < 70): ?>
                    <li>Company-wide audit average is below target. Review branch compliance.</li>
                <?php endif; ?>
                <li>Check Document Management for expiring contracts.</li>
            </ul>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('auditChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartDates); ?>,
            datasets: [{
                label: 'Audit Score %',
                data: <?php echo json_encode($chartScores); ?>,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });
    </script>
</body>
</html>