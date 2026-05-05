<?php
session_start();
require_once '../../config/db.php';

// Fetch Assets with their Branch Names
$query = "SELECT a.*, b.name as branch_name FROM assets a 
          JOIN branches b ON a.branch_id = b.branch_id";
$stmt = $pdo->query($query);
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Branches for the dropdown menu
$branches = $pdo->query("SELECT branch_id, name FROM branches")->fetchAll(PDO::FETCH_ASSOC);

// Set page title and include the shared header
$pageTitle = "Assets | MONIK Group";
include '../../includes/header.php'; // This file already starts the <html>, <head>, and <body>
?>

<!-- No need for <head> or <body> tags here, they are in header.php -->

<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    <div class="container" style="padding: 20px;">
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
                        $qrData = "MONIK-ASSET-" . $asset['asset_id'];
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                    ?>
                    <tr>
                        <td><strong>#<?php echo $asset['asset_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($asset['name']); ?></td>
                        <td><?php echo htmlspecialchars($asset['category']); ?></td>
                        <td><?php echo htmlspecialchars($asset['branch_name']); ?></td>
                        <td><span style="color: green;">●</span> <?php echo $asset['status']; ?></td>
                        <td>
                            <img src="<?php echo $qrUrl; ?>" style="width:50px; cursor:pointer;" title="View Full QR" onclick="window.open('<?php echo $qrUrl; ?>')">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Asset Modal -->
    <div id="assetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000;">
        <div class="modal-content" style="background:white; width:400px; margin: 10% auto; padding:30px; border-radius:8px;">
            <span class="close-btn" onclick="$('#assetModal').hide()" style="float:right; cursor:pointer;">&times;</span>
            <h3>Register New Asset</h3>
            <form id="addAssetForm">
                <input type="text" name="name" placeholder="Asset Name" required style="width:100%; margin-bottom:10px; padding:8px;">
                <input type="text" name="category" placeholder="Category" required style="width:100%; margin-bottom:10px; padding:8px;">
                
                <label style="font-size: 14px; color: #666;">Assign to Branch:</label>
                <select name="branch_id" required style="width:100%; margin-bottom:20px; padding:8px;">
                    <option value="">-- Select Branch --</option>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?php echo $b['branch_id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn" style="width:100%; padding:10px; background:#3498db; color:white; border:none; border-radius:4px;">Save Asset to System</button>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#addAssetForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_asset.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    alert('Asset Registered Successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            }
        });
    });
});
</script>

</body>
</html>