<?php
session_start();
require_once '../../config/db.php';

// Fetch recent audits with branch and auditor names
$query = "SELECT a.*, b.name as branch_name, u.full_name as auditor_name 
          FROM audits a 
          JOIN branches b ON a.branch_id = b.branch_id 
          JOIN users u ON a.auditor_id = u.user_id 
          ORDER BY a.audit_date DESC";
$audits = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$branches = $pdo->query("SELECT * FROM branches WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit & Compliance | Monik Group</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include '../../includes/topbar.php'; ?>
        <h2>📊 Audit & Compliance Dashboard</h2>
        <button class="btn" onclick="$('#auditModal').show()">+ New Audit Entry</button>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Auditor</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($audits as $a): 
                    $score = $a['score'];
                    $class = ($score > 80) ? 'bg-green' : (($score >= 60) ? 'bg-yellow' : 'bg-red');
                    $status = ($score > 80) ? 'Excellent' : (($score >= 60) ? 'Good' : 'Critical');
                ?>
                <tr>
                    <td><?php echo $a['audit_date']; ?></td>
                    <td><?php echo $a['branch_name']; ?></td>
                    <td><?php echo $a['auditor_name']; ?></td>
                    <td><span class="score-pill <?php echo $class; ?>"><?php echo $score; ?>%</span></td>
                    <td><strong><?php echo $status; ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Audit Entry Modal -->
    <div id="auditModal" class="modal">
        <div class="modal-content">
        <h3>Submit Audit Score</h3>
        <form id="auditForm">
            <label>Branch:</label>
            <select name="branch_id" required>
                <?php foreach($branches as $b): ?>
                    <option value="<?php echo $b['branch_id']; ?>"><?php echo $b['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>Score (0 - 100):</label>
            <input type="number" name="score" min="0" max="100" required>

            <label>Audit Date:</label>
            <input type="date" name="audit_date" value="<?php echo date('Y-m-d'); ?>" required>

            <label>Auditor Comments:</label>
            <textarea name="comments" rows="3"></textarea>

            <button type="submit" style="width:100%; background:#27ae60; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Submit Audit</button>
            <button type="button" onclick="$('#auditModal').hide()" style="width:100%; margin-top:10px; background:none; border:none; color:gray; cursor:pointer;">Cancel</button>
        </form>
        </div>
    </div>

    <script>
    $('#auditForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'submit_audit.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res) { location.reload(); }
        });
    });
    </script>
</body>
</html>