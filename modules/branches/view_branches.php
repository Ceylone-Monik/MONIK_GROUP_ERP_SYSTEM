<?php
session_start();
require_once '../../config/db.php';

$org_id = isset($_GET['org_id']) ? (int)$_GET['org_id'] : 0;

if ($org_id === 0) {
    header("Location: index.php");
    exit();
}

// Fetch Company Details
$stmtOrg = $pdo->prepare("SELECT * FROM organizations WHERE org_id = ?");
$stmtOrg->execute([$org_id]);
$company = $stmtOrg->fetch(PDO::FETCH_ASSOC);

// Fetch branches for THIS organization only
$stmt = $pdo->prepare("SELECT * FROM branches WHERE org_id = ?");
$stmt->execute([$org_id]);
$branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $company['name'] . " - Branches";
include '../../includes/header.php'; 
?>

<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="index.php" style="text-decoration: none; color: #3498db; font-weight: 600;">← Back to Organizations</a>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>📍 Branches: <?php echo htmlspecialchars($company['name']); ?></h2>
            <!-- Logic Fix: Added ID so JS can target the click -->
            <button type="button" class="btn" id="btnAddBranch">+ Add New Branch</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch Name</th>
                    <th>Region</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($branches as $row): ?>
                <tr>
                    <td><strong>#<?php echo $row['branch_id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['region']); ?></td>
                    <td>
                        <span class="badge <?php echo ($row['status'] == 'Active') ? 'badge-active' : 'badge-inactive'; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <!-- Logic Fix: Data attributes for the Edit script -->
                        <button type="button" class="btn btn-edit-branch"
                            data-branch-id="<?php echo (int) $row['branch_id']; ?>"
                            data-name="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-region="<?php echo htmlspecialchars($row['region'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-org-id="<?php echo $org_id; ?>"
                            data-status="<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($branches)): ?>
                    <tr><td colspan="5" style="text-align:center; padding: 40px; color: #7f8c8d;">No branches registered for this company yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="branchModal">
    <div class="modal-content">
        <h3 id="branchModalTitle" style="margin-top:0;">Register New Branch</h3>
        <form id="branchForm">
            <input type="hidden" name="branch_id" id="branch_id" value="">
            <input type="hidden" name="org_id" id="branch_org_id" value="<?php echo $org_id; ?>">
            
            <input type="text" name="name" id="branch_name" placeholder="Branch Name" required>
            <input type="text" name="region" id="branch_region" placeholder="Region" required>
            <select name="status" id="branch_status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" class="btn" id="branchSubmitBtn">Save Branch</button>
            <button type="button" onclick="$('#branchModal').hide()" style="background:none; border:none; color:#999; width:100%; margin-top:10px; cursor:pointer;">Cancel</button>
        </form>
    </div>
</div>

<!-- Only Logic/JS changes here -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Logic: Listen for the button ID to open modal
    $('#btnAddBranch').on('click', function() {
        $('#branchModalTitle').text('Register New Branch for <?php echo addslashes($company['name']); ?>');
        $('#branch_id').val('');
        $('#branch_name').val('');
        $('#branch_region').val('');
        $('#branch_status').val('Active');
        $('#branchModal').show();
    });

    // Logic: Handle Edit button data mapping
    $(document).on('click', '.btn-edit-branch', function() {
        var $b = $(this);
        $('#branchModalTitle').text('Edit Branch');
        $('#branch_id').val($b.attr('data-branch-id'));
        $('#branch_name').val($b.attr('data-name'));
        $('#branch_region').val($b.attr('data-region'));
        $('#branch_status').val($b.attr('data-status'));
        $('#branchModal').show();
    });

    // Logic: Form submission routing
    $('#branchForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#branch_id').val();
        var url = id ? 'update_branch.php' : 'add_branch.php';
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') { location.reload(); }
                else { alert('Error: ' + response.message); }
            }
        });
    });
});
</script>
</body>
</html>