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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard | Monik Group</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include 'includes/topbar.php'; ?>
        <h1 class="page-title">Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Executive'); ?> 🚀</h1>
        <p class="page-subtitle">Real-time overview of Monik Group operations.</p>

        <div class="kpi-container">
            <div class="kpi-card blue">
                <p>TOTAL BRANCHES</p>
                <h3><?php echo (int) $totalBranches; ?></h3>
            </div>
            <div class="kpi-card yellow">
                <p>TOTAL ASSETS</p>
                <h3><?php echo (int) $totalAssets; ?></h3>
            </div>
            <div class="kpi-card red">
                <p>OPEN TICKETS</p>
                <h3><?php echo (int) $openTickets; ?></h3>
            </div>
            <div class="kpi-card green">
                <p>AVG AUDIT SCORE</p>
                <h3><?php echo round((float) $avgAudit, 1); ?>%</h3>
            </div>
        </div>

        <div class="chart-section">
            <h3>📈 Audit Performance Trend</h3>
            <canvas id="auditChart" height="100"></canvas>
        </div>

        <div class="alerts-panel">
            <h4 class="alert-title">🚨 System Alerts</h4>
            <ul class="alerts-list">
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
                borderColor: '#4f8cff',
                backgroundColor: 'rgba(79, 140, 255, 0.15)',
                fill: true,
                tension: 0.32
            }]
        },
        options: {
            plugins: {
                legend: { labels: { color: '#33466e' } }
            },
            scales: {
                x: { ticks: { color: '#5f6f8f' }, grid: { color: 'rgba(76,99,144,0.16)' } },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { color: '#5f6f8f' },
                    grid: { color: 'rgba(76,99,144,0.16)' }
                }
            }
        }
    });
    </script>
</body>
</html>