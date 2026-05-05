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
<?php 
  $pageTitle = "Users | MONIK Group"; // Change this per module
  include '../../includes/header.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Monik Group</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-wrapper">
    <?php include '../../includes/topbar.php'; ?>
    <h2>👥 User Management</h2>
    <button type="button" class="btn" id="btnAddUser">+ Create New User</button>

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
                <td>
                    <button type="button" class="btn btn-edit-user"
                        data-user-id="<?php echo (int) $u['user_id']; ?>"
                        data-full-name="<?php echo htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-username="<?php echo htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-role-id="<?php echo (int) $u['role_id']; ?>"
                        data-branch-id="<?php echo isset($u['branch_id']) && $u['branch_id'] !== '' ? (int) $u['branch_id'] : ''; ?>">Edit</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="userModal">
        <div class="modal-content">
        <h3 id="userModalTitle">Create Staff Account</h3>
        <form id="userForm">
            <input type="hidden" name="user_id" id="user_id" value="">
            <input type="text" name="full_name" id="user_full_name" placeholder="Full Name" required style="width:100%; margin-bottom:10px;">
            <input type="text" name="username" id="user_username" placeholder="Username (Email)" required style="width:100%; margin-bottom:10px;">
            <input type="password" name="password" id="user_password" placeholder="Password" style="width:100%; margin-bottom:10px;">
            <p id="userPasswordHint" style="margin:0 0 10px; font-size:0.85rem; color:var(--muted);">Required for new users.</p>
            
            <label>Role:</label>
            <select name="role_id" id="user_role_id" style="width:100%; margin-bottom:10px;">
                <?php foreach($roles as $r): ?>
                    <option value="<?php echo $r['role_id']; ?>"><?php echo $r['role_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Assign to Branch (Optional):</label>
            <select name="branch_id" id="user_branch_id" style="width:100%; margin-bottom:10px;">
                <option value="">None (Head Office)</option>
                <?php foreach($branches as $b): ?>
                    <option value="<?php echo $b['branch_id']; ?>"><?php echo $b['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn" id="userSubmitBtn" style="width:100%;">Create User</button>
            <button type="button" onclick="$('#userModal').hide()" style="width:100%; margin-top:5px;">Cancel</button>
        </form>
        </div>
    </div>
    </div>

    <script>
    function openUserModalAdd() {
        $('#userModalTitle').text('Create Staff Account');
        $('#user_id').val('');
        $('#user_full_name').val('');
        $('#user_username').val('');
        $('#user_password').val('').prop('required', true);
        $('#user_password').attr('placeholder', 'Temporary Password');
        $('#userPasswordHint').text('Required for new users.');
        $('#user_role_id').prop('selectedIndex', 0);
        $('#user_branch_id').val('');
        $('#userSubmitBtn').text('Create User');
        $('#userModal').show();
    }

    $('#btnAddUser').on('click', openUserModalAdd);

    $(document).on('click', '.btn-edit-user', function() {
        var $b = $(this);
        $('#userModalTitle').text('Edit User');
        $('#user_id').val($b.attr('data-user-id'));
        $('#user_full_name').val($b.attr('data-full-name'));
        $('#user_username').val($b.attr('data-username'));
        $('#user_role_id').val($b.attr('data-role-id'));
        var bid = $b.attr('data-branch-id');
        $('#user_branch_id').val(bid || '');
        $('#user_password').val('').prop('required', false);
        $('#user_password').attr('placeholder', 'New password (optional)');
        $('#userPasswordHint').text('Leave blank to keep the current password.');
        $('#userSubmitBtn').text('Update User');
        $('#userModal').show();
    });

    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#user_id').val();
        if (!id && !$('#user_password').val()) {
            alert('Please set a password for new users.');
            return;
        }
        var url = id ? 'update_user.php' : 'add_user.php';
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') { location.reload(); }
                else { alert(res.message || 'Error'); }
            },
            error: function() { alert('Request failed.'); }
        });
    });
    </script>
</body>
</html>