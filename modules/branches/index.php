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
    <title>Branch Management | Monik Group ERP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Professional ERP Styling */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f4f7f6; color: #333; }
        .container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        
        h2 { margin-top: 0; color: #2c3e50; display: flex; align-items: center; gap: 10px; }
        
        /* Table Styling - FIXED VISIBILITY */
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; }
        th { background-color: #34495e; color: #ffffff; padding: 15px; text-align: left; font-weight: 600; text-transform: uppercase; font-size: 13px; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; font-size: 14px; }
        tr:hover { background-color: #f9f9f9; }

        /* Status Badge */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }

        /* Buttons */
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-add:hover { background: #219150; }
        .btn-edit { background: #3498db; color: white; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; }

        /* Modal Styling */
        #branchModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index: 1000; }
        .modal-content { background:white; width:400px; margin: 10% auto; padding: 30px; border-radius: 8px; position:relative; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-save { background: #2c3e50; color: white; width: 100%; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>🏢 Branch Management</h2>
    <button class="btn-add" onclick="$('#branchModal').show()">+ Add New Branch</button>

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
                <td><button class="btn-edit">Edit</button></td>
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
            <button type="submit" class="btn-save">Save Branch</button>
            <button type="button" onclick="$('#branchModal').hide()" style="background:none; border:none; color:#999; width:100%; margin-top:10px; cursor:pointer;">Cancel</button>
        </form>
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