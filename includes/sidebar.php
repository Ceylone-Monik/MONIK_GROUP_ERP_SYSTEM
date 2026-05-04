<?php
$base_url = "/MONIK_GROUP_ERP/MONIK_GROUP_ERP_SYSTEM/";
$uri = $_SERVER['REQUEST_URI'] ?? '';

function nav_active(string $needle, string $uri): string {
    return strpos($uri, $needle) !== false ? 'active' : '';
}
?>
<div class="sidebar">
    <h2>MONIK ERP</h2>
    <div class="nav-links">
        <a class="<?php echo nav_active('/dashboard.php', $uri); ?>" href="<?php echo $base_url; ?>dashboard.php">📊 Dashboard</a>
        <a class="<?php echo nav_active('/modules/branches/', $uri); ?>" href="<?php echo $base_url; ?>modules/branches/index.php">🏢 Branches</a>
        <a class="<?php echo nav_active('/modules/assets/', $uri); ?>" href="<?php echo $base_url; ?>modules/assets/index.php">📦 Assets</a>
        <a class="<?php echo nav_active('/modules/users/', $uri); ?>" href="<?php echo $base_url; ?>modules/users/index.php">👥 Users</a>
        <a class="<?php echo nav_active('/modules/service/', $uri); ?>" href="<?php echo $base_url; ?>modules/service/index.php">🔧 Service Board</a>
        <a class="<?php echo nav_active('/modules/audits/', $uri); ?>" href="<?php echo $base_url; ?>modules/audits/index.php">📝 Audits</a>
        <a class="<?php echo nav_active('/modules/documents/', $uri); ?>" href="<?php echo $base_url; ?>modules/documents/index.php">📁 Documents</a>
    </div>
    <a href="<?php echo $base_url; ?>auth/logout.php" class="logout-btn">Logout</a>
</div>