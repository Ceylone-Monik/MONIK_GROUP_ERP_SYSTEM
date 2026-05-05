<?php
session_start();
require_once '../../config/db.php';

$org_id = isset($_GET['org_id']) ? (int)$_GET['org_id'] : 0;
if ($org_id === 0) { header("Location: index.php"); exit(); }

// Fetch Company Info
$stmtOrg = $pdo->prepare("SELECT name FROM organizations WHERE org_id = ?");
$stmtOrg->execute([$org_id]);
$company = $stmtOrg->fetch(PDO::FETCH_ASSOC);

// Fetch Assets for THIS company's branches
$query = "SELECT a.*, b.name as branch_name FROM assets a 
          JOIN branches b ON a.branch_id = b.branch_id 
          WHERE b.org_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$org_id]);
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch branches for the dropdown (only those belonging to THIS company)
$stmtB = $pdo->prepare("SELECT branch_id, name FROM branches WHERE org_id = ?");
$stmtB->execute([$org_id]);
$branches = $stmtB->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Assets - " . $company['name'];
include '../../includes/header.php'; 
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    <div class="container" style="padding: 20px;">
        <div style="margin-bottom: 20px;">
            <a href="index.php" style="text-decoration: none; color: #3498db; font-weight: 600;">← Back to Organizations</a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>📦 Assets: <?php echo htmlspecialchars($company['name']); ?></h2>
            <button class="btn" onclick="$('#assetModal').show()" style="background: #6c5ce7; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">+ Add New Asset</button>
        </div>

        <table style="width: 100%; border-collapse: collapse; background: white;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #eee;">
                    <th style="padding: 15px;">Asset ID</th>
                    <th style="padding: 15px;">Name</th>
                    <th style="padding: 15px;">Category</th>
                    <th style="padding: 15px;">Branch</th>
                    <th style="padding: 15px;">Status</th>
                    <th style="padding: 15px;">QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $asset): 
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MONIK-ASSET-" . $asset['asset_id'];
                ?>
                <tr style="border-bottom: 1px solid #f9f9f9;">
                    <td style="padding: 15px;"><strong>#<?php echo $asset['asset_id']; ?></strong></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($asset['name']); ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($asset['category']); ?></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($asset['branch_name']); ?></td>
                    <td style="padding: 15px;"><span style="color: green;">●</span> <?php echo $asset['status']; ?></td>
                    <td style="padding: 15px;">
                        <img src="<?php echo $qrUrl; ?>" style="width:40px; cursor:pointer;" onclick="window.open('<?php echo $qrUrl; ?>')">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Logic remains same but uses filtered branches -->
<div id="assetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000;">
    <div style="background:white; width:400px; margin: 10% auto; padding:30px; border-radius:15px;">
        <h3>Register New Asset</h3>
        <form id="addAssetForm">
            <input type="text" name="name" placeholder="Asset Name" required style="width:100%; padding:12px; margin-bottom:10px; border:1px solid #eee; border-radius:8px;">
            <input type="text" name="category" placeholder="Category" required style="width:100%; padding:12px; margin-bottom:10px; border:1px solid #eee; border-radius:8px;">
            
            <label>Assign to Branch (<?php echo htmlspecialchars($company['name']); ?>):</label>
            <select name="branch_id" required style="width:100%; padding:12px; margin-bottom:20px; border:1px solid #eee; border-radius:8px;">
                <option value="">-- Select Branch --</option>
                <?php foreach ($branches as $b): ?>
                    <option value="<?php echo $b['branch_id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" style="width:100%; padding:12px; background:#6c5ce7; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">Save Asset</button>
            <button type="button" onclick="$('#assetModal').hide()" style="width:100%; background:none; border:none; color:#999; margin-top:10px; cursor:pointer;">Cancel</button>
        </form>
    </div>
</div>

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
                if(res.status === 'success') { location.reload(); }
                else { alert('Error: ' + res.message); }
            }
        });
    });
});
</script>
</body>
</html>