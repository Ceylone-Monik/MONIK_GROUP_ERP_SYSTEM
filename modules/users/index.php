<?php
session_start();
require_once '../../config/db.php';

// Fetch users with their roles and branch names
$query = "SELECT u.*, r.role_name, b.name as branch_name FROM users u 
          JOIN roles r ON u.role_id = r.role_id 
          LEFT JOIN branches b ON u.branch_id = b.branch_id";
$users = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
$branches = $pdo->query("SELECT * FROM branches")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Management | Monik Group</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #34495e; color: white; }
        .btn { padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-add { background: #27ae60; color: white; }
        #userModal { display:none; position:fixed; top:10%; left:30%; background:white; padding:30px; border:1px solid #ccc; box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 400px; }
    </style>
</head>
<body>

    <h2>👥 User Management</h2>
    <button class="btn btn-add" onclick="$('#userModal').show()">+ Create New User</button>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Branch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo $u['full_name']; ?></td>
                <td><?php echo $u['username']; ?></td>
                <td><strong><?php echo $u['role_name']; ?></strong></td>
                <td><?php echo $u['branch_name'] ?? 'Global (HQ)'; ?></td>
                <td><button>Edit</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="userModal">
        <h3>Create Staff Account</h3>
        <form id="addUserForm">
            <input type="text" name="full_name" placeholder="Full Name" required style="width:100%; margin-bottom:10px;">
            <input type="text" name="username" placeholder="Username (Email)" required style="width:100%; margin-bottom:10px;">
            <input type="password" name="password" placeholder="Temporary Password" required style="width:100%; margin-bottom:10px;">
            
            <label>Role:</label>
            <select name="role_id" style="width:100%; margin-bottom:10px;">
                <?php foreach($roles as $r): ?>
                    <option value="<?php echo $r['role_id']; ?>"><?php echo $r['role_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Assign to Branch (Optional):</label>
            <select name="branch_id" style="width:100%; margin-bottom:10px;">
                <option value="">None (Head Office)</option>
                <?php foreach($branches as $b): ?>
                    <option value="<?php echo $b['branch_id']; ?>"><?php echo $b['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-add" style="width:100%;">Create User</button>
            <button type="button" onclick="$('#userModal').hide()" style="width:100%; margin-top:5px;">Cancel</button>
        </form>
    </div>

    <script>
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_user.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') { location.reload(); }
                else { alert(res.message); }
            }
        });
    });
    </script>
</body>
</html>