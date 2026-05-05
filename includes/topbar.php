<?php
$base_url = "/MONIK_GROUP_ERP/MONIK_GROUP_ERP_SYSTEM/";
$fullName = $_SESSION['full_name'] ?? 'User';
?>
<div class="topbar">
  <div class="topbar-left">
    <img src="<?php echo $base_url; ?>assets/pic/MonikLogoOnly.png" alt="Monik Group Logo" class="topbar-logo">
  </div>
  <div class="topbar-right">
    <a class="quick-action" href="<?php echo $base_url; ?>modules/assets/index.php">+ Add Asset</a>
    <a class="quick-action" href="<?php echo $base_url; ?>modules/service/index.php">+ Create Ticket</a>
    <span class="topbar-chip notif-badge">Notifications</span>
    <span class="topbar-chip"><?php echo htmlspecialchars((string) $fullName); ?></span>
  </div>
</div>
