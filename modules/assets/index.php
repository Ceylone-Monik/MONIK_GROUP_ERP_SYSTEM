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
<head>
    <meta charset="UTF-8">
    <title>Asset Management | Monik Group ERP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f9f9f9; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .qr-code { width: 60px; height: 60px; border: 1px solid #eee; cursor: pointer; transition: transform 0.2s; }
        .qr-code:hover { transform: scale(1.1); }
        .btn-add { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        /* Modal Style */
        #assetModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); }
        .modal-content { background:white; width:400px; margin: 10% auto; padding: 30px; border-radius: 8px; position:relative; }
        .close-btn { position:absolute; top:10px; right:15px; cursor:pointer; font-size: 20px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-save { background: #007bff; color: white; width: 100%; padding: 10px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 Asset Management</h2>
    <p>Manage and track physical assets across all Monik Group branches.</p>
    
    <button class="btn-add" onclick="$('#assetModal').show()">+ Add New Asset</button>

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

            <button type="submit" class="btn-save">Save Asset to System</button>
        </form>
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