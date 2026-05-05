<?php
session_start();
require_once '../../config/db.php';

// Fetch Assets with their Branch Names
$query = "SELECT a.*, b.name as branch_name FROM assets a 
          JOIN branches b ON a.branch_id = b.branch_id";
$stmt = $pdo->query($query);
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Branches for the dropdown menu in the modal
$branches = $pdo->query("SELECT branch_id, name FROM branches")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $pageTitle = "Assets | MONIK Group"; // Change this per module
  include '../../includes/header.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management | Monik Group ERP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
<?php include '../../includes/topbar.php'; ?>
<div class="container">
    <h2>📦 Asset Management</h2>
    <p>Manage and track physical assets across all Monik Group branches.</p>
    
    <button class="btn" onclick="$('#assetModal').show()">+ Add New Asset</button>

    <table>
        <thead>
            <tr>
                <th>Asset ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Branch</th>
                <th>Status</th>
                <th>QR Code (Click to Expand)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assets)): ?>
                <tr><td colspan="6" style="text-align:center;">No assets found. Add your first asset above.</td></tr>
            <?php else: ?>
                <?php foreach ($assets as $asset): 
                    // Data to be encoded in the QR
                    $qrData = "MONIK-ASSET-" . $asset['asset_id'];
                    // NEW STABLE API URL
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                ?>
                <tr>
                    <td><strong>#<?php echo $asset['asset_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($asset['name']); ?></td>
                    <td><?php echo htmlspecialchars($asset['category']); ?></td>
                    <td><?php echo htmlspecialchars($asset['branch_name']); ?></td>
                    <td><span style="color: green;">●</span> <?php echo $asset['status']; ?></td>
                    <td>
                        <img src="<?php echo $qrUrl; ?>" class="qr-code" title="View Full QR" onclick="window.open('<?php echo $qrUrl; ?>')">
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Asset Modal -->
<div id="assetModal">
    <div class="modal-content">
        <span class="close-btn" onclick="$('#assetModal').hide()">&times;</span>
        <h3>Register New Asset</h3>
        <form id="addAssetForm">
            <input type="text" name="name" placeholder="Asset Name (e.g., Office Chair)" required>
            <input type="text" name="category" placeholder="Category (e.g., Furniture)" required>
            
            <label style="font-size: 14px; color: #666;">Assign to Branch:</label>
            <select name="branch_id" required>
                <option value="">-- Select Branch --</option>
                <?php foreach ($branches as $b): ?>
                    <option value="<?php echo $b['branch_id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Save Asset to System</button>
        </form>
    </div>
</div>
 </div>

<script>
$(document).ready(function() {
    $('#addAssetForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_asset.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    alert('Asset Registered Successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                alert('Server error. Please check your database connection.');
            }
        });
    });
});
</script>

</body>
</html>