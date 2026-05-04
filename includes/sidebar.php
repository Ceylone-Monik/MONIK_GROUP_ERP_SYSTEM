<?php
// Define the absolute base path for your project
$base_url = "/MONIK_GROUP_ERP/MONIK_GROUP_ERP_SYSTEM/";
?>
<style>
    .sidebar { 
        width: 250px; 
        background: #2c3e50; 
        color: white; 
        height: 100vh; 
        position: fixed; 
        padding: 20px; 
        box-sizing: border-box; 
        display: flex; 
        flex-direction: column; 
        z-index: 1000;
    }
    .sidebar h2 { 
        font-size: 1.5rem; 
        border-bottom: 1px solid #34495e; 
        padding-bottom: 10px; 
        margin-bottom: 20px; 
    }
    .nav-links { 
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
        flex-grow: 1; 
    }
    .nav-links a { 
        color: #bdc3c7; 
        text-decoration: none; 
        padding: 12px; 
        border-radius: 6px; 
        transition: 0.3s; 
        font-size: 14px; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    .nav-links a:hover { 
        background: #34495e; 
        color: white; 
    }
    .logout-btn { 
        background: #e74c3c; 
        color: white; 
        text-align: center; 
        padding: 12px; 
        border-radius: 6px; 
        text-decoration: none; 
        font-weight: bold; 
        margin-top: auto; 
    }
</style>

<div class="sidebar">
    <h2>MONIK ERP</h2>
    <div class="nav-links">
        <a href="<?php echo $base_url; ?>dashboard.php">📊 Dashboard</a>
        <a href="<?php echo $base_url; ?>modules/branches/index.php">🏢 Branches</a>
        <a href="<?php echo $base_url; ?>modules/assets/index.php">📦 Assets</a>
        <a href="<?php echo $base_url; ?>modules/users/index.php">👥 Users</a>
        <a href="<?php echo $base_url; ?>modules/service/index.php">🔧 Service Board</a>
        <a href="<?php echo $base_url; ?>modules/audits/index.php">📝 Audits</a>
        <a href="<?php echo $base_url; ?>modules/documents/index.php">📁 Documents</a>
    </div>
    <a href="<?php echo $base_url; ?>auth/logout.php" class="logout-btn">Logout</a>
</div>