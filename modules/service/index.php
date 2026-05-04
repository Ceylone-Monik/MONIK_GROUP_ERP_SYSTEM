<?php
session_start();
require_once '../../config/db.php';

// Fetch tickets joined with asset names
$query = "SELECT t.*, a.name as asset_name FROM tickets t JOIN assets a ON t.asset_id = a.asset_id";
$tickets = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Fetch assets for the "Add Ticket" dropdown
$assets = $pdo->query("SELECT asset_id, name FROM assets")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Board | Monik Group</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { margin: 0; display: flex; font-family: 'Segoe UI', sans-serif; background: #f4f7f6; }
        .main-wrapper { margin-left: 250px; padding: 30px; width: calc(100% - 250px); box-sizing: border-box; }
        
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-create { background: #27ae60; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        
        .kanban-board { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .column { background: #ebedf0; padding: 15px; border-radius: 8px; min-height: 600px; }
        .column h3 { text-align: center; color: #2c3e50; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-top: 0; }
        
        .ticket-card { background: white; padding: 15px; border-radius: 6px; margin-bottom: 12px; border-left: 5px solid #3498db; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .status-btn { background: #34495e; color: white; width: 100%; padding: 8px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        
        /* Modal Styling */
        #addTicketModal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index: 2000; }
        .modal-content { background:white; width:400px; margin: 10% auto; padding: 30px; border-radius: 8px; position:relative; }
        input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    </style>
</head>
<body>

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <div class="header-section">
            <h2>🔧 Service & Maintenance Board</h2>
            <!-- THE MISSING BUTTON[cite: 1] -->
            <button class="btn-create" onclick="$('#addTicketModal').show()">+ Create Service Ticket</button>
        </div>

        <div class="kanban-board">
            <!-- NEW COLUMN[cite: 1] -->
            <div class="column">
                <h3>NEW</h3>
                <?php foreach($tickets as $t): if($t['status'] == 'New'): ?>
                    <div class="ticket-card">
                        <h4><?php echo htmlspecialchars($t['asset_name']); ?></h4>
                        <p><?php echo htmlspecialchars($t['issue_description']); ?></p>
                        <button class="status-btn" onclick="updateTicket(<?php echo $t['ticket_id']; ?>, 'In Progress')">Start Progress →</button>
                    </div>
                <?php endif; endforeach; ?>
            </div>

            <!-- IN PROGRESS COLUMN[cite: 1] -->
            <div class="column">
                <h3>IN PROGRESS</h3>
                <?php foreach($tickets as $t): if($t['status'] == 'In Progress'): ?>
                    <div class="ticket-card" style="border-left-color: #f39c12;">
                        <h4><?php echo htmlspecialchars($t['asset_name']); ?></h4>
                        <p><?php echo htmlspecialchars($t['issue_description']); ?></p>
                        <button class="status-btn" style="background:#27ae60" onclick="updateTicket(<?php echo $t['ticket_id']; ?>, 'Completed')">Complete ✔</button>
                    </div>
                <?php endif; endforeach; ?>
            </div>

            <!-- COMPLETED COLUMN[cite: 1] -->
            <div class="column">
                <h3>COMPLETED</h3>
                <?php foreach($tickets as $t): if($t['status'] == 'Completed'): ?>
                    <div class="ticket-card" style="border-left-color: #27ae60; opacity: 0.7;">
                        <h4><?php echo htmlspecialchars($t['asset_name']); ?></h4>
                        <p>Issue Resolved</p>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Add Ticket Modal[cite: 1] -->
    <div id="addTicketModal">
        <div class="modal-content">
            <h3>New Service Ticket</h3>
            <form id="addTicketForm">
                <label>Select Asset:</label>
                <select name="asset_id" required>
                    <?php foreach($assets as $a): ?>
                        <option value="<?php echo $a['asset_id']; ?>"><?php echo $a['name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Issue Description:</label>
                <textarea name="issue" rows="3" required placeholder="Describe the problem..."></textarea>

                <label>Priority:</label>
                <select name="priority">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>

                <button type="submit" class="btn-create" style="width:100%;">Create Ticket</button>
                <button type="button" onclick="$('#addTicketModal').hide()" style="width:100%; margin-top:10px; background:none; border:none; color:gray; cursor:pointer;">Cancel</button>
            </form>
        </div>
    </div>

    <script>
    // AJAX for updating status[cite: 1]
    function updateTicket(id, status) {
        $.post('manage_tickets.php', { action: 'update', ticket_id: id, status: status }, function() {
            location.reload();
        });
    }

    // AJAX for creating ticket[cite: 1]
    $('#addTicketForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'manage_tickets.php',
            method: 'POST',
            data: $(this).serialize() + '&action=create',
            success: function() {
                location.reload();
            }
        });
    });
    </script>
</body>
</html>