<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'MONIK Group ERP';

// Absolute base path to ensure the logo loads from any subfolder
$base_url = "/MONIK_GROUP_ERP/MONIK_GROUP_ERP_SYSTEM/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    
    <!-- 1. ADD THIS LINE TO CHANGE THE TAB LOGO[cite: 1] -->
    <link rel="icon" type="image/png" href="<?= $base_url ?>assets/pic/MonikLogoOnly.png">
    
    <!-- 2. Path to your existing stylesheet -->
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
</head>
<body>