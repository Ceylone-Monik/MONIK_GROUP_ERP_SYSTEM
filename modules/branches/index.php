<?php
session_start();
require_once '../../config/db.php';

// Fetch all branches with organization names
$stmt = $pdo->query("SELECT b.*, o.name as org_name FROM branches b JOIN organizations o ON b.org_id = o.org_id");
$branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $pageTitle = "Branches | MONIK Group"; // Change this per module
  include '../../includes/header.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Management | Monik Group ERP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include '../../includes/sidebar.php'; ?>

<div class="main-wrapper">
<?php include '../../includes/topbar.php'; ?>
<div class="container">
    <h2>🏢 Branch Management</h2>
    <button type="button" class="btn" id="btnAddBranch">+ Add New Branch</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Branch Name</th>
                <th>Region</th>
                <th>Organization</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="branchTableBody">
            <?php foreach ($branches as $row): ?>
            <tr>
                <td><strong>#<?php echo $row['branch_id']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['region']); ?></td>
                <td><?php echo htmlspecialchars($row['org_name']); ?></td>
                <td>
                    <span class="badge <?php echo ($row['status'] == 'Active') ? 'badge-active' : 'badge-inactive'; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn btn-edit-branch"
                        data-branch-id="<?php echo (int) $row['branch_id']; ?>"
                        data-name="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-region="<?php echo htmlspecialchars($row['region'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-org-id="<?php echo (int) $row['org_id']; ?>"
                        data-status="<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>">Edit</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add / Edit Branch Modal -->
<div id="branchModal">
    <div class="modal-content">
        <h3 id="branchModalTitle" style="margin-top:0;">Register New Branch</h3>
        <form id="branchForm">
            <input type="hidden" name="branch_id" id="branch_id" value="">
            <input type="hidden" name="org_id" id="branch_org_id" value="1">
            <input type="text" name="name" id="branch_name" placeholder="Branch Name (e.g. Badulla Hub)" required>
            <input type="text" name="region" id="branch_region" placeholder="Region (e.g. Uva)" required>
            <select name="status" id="branch_status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" class="btn" id="branchSubmitBtn">Save Branch</button>
            <button type="button" onclick="$('#branchModal').hide()" style="background:none; border:none; color:#999; width:100%; margin-top:10px; cursor:pointer;">Cancel</button>
        </form>
    </div>
</div>
 </div>

<script>
$(document).ready(function() {
    function openBranchModalAdd() {
        $('#branchModalTitle').text('Register New Branch');
        $('#branch_id').val('');
        $('#branch_org_id').val('1');
        $('#branch_name').val('');
        $('#branch_region').val('');
        $('#branch_status').val('Active');
        $('#branchSubmitBtn').text('Save Branch');
        $('#branchModal').show();
    }

    $('#btnAddBranch').on('click', openBranchModalAdd);

    $(document).on('click', '.btn-edit-branch', function() {
        var $b = $(this);
        $('#branchModalTitle').text('Edit Branch');
        $('#branch_id').val($b.attr('data-branch-id'));
        $('#branch_org_id').val($b.attr('data-org-id'));
        $('#branch_name').val($b.attr('data-name'));
        $('#branch_region').val($b.attr('data-region'));
        $('#branch_status').val($b.attr('data-status'));
        $('#branchSubmitBtn').text('Update Branch');
        $('#branchModal').show();
    });

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
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown'));
                }
            },
            error: function() {
                alert('Request failed.');
            }
        });
    });
});
</script>

</body>
</html>