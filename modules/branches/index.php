<?php
session_start();
require_once '../../config/db.php';

// Fetch all branches with organization names
$stmt = $pdo->query("SELECT b.*, o.name as org_name FROM branches b JOIN organizations o ON b.org_id = o.org_id");
$branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
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
    <button class="btn" onclick="$('#branchModal').show()">+ Add New Branch</button>

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
                <td><button class="btn">Edit</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Branch Modal -->
<div id="branchModal">
    <div class="modal-content">
        <h3 style="margin-top:0;">Register New Branch</h3>
        <form id="addBranchForm">
            <input type="hidden" name="org_id" value="1">
            <input type="text" name="name" placeholder="Branch Name (e.g. Badulla Hub)" required>
            <input type="text" name="region" placeholder="Region (e.g. Uva)" required>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
            <button type="submit" class="btn">Save Branch</button>
            <button type="button" onclick="$('#branchModal').hide()" style="background:none; border:none; color:#999; width:100%; margin-top:10px; cursor:pointer;">Cancel</button>
        </form>
    </div>
</div>
 </div>

<script>
$(document).ready(function() {
    $('#addBranchForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_branch.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });
});
</script>

</body>
</html>