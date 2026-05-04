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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Board | Monik Group</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include '../../includes/topbar.php'; ?>
        <div class="header-section">
            <h2>🔧 Service & Maintenance Board</h2>
            <!-- THE MISSING BUTTON[cite: 1] -->
            <button class="btn" onclick="$('#addTicketModal').show()">+ Create Service Ticket</button>
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

                <button type="submit" class="btn" style="width:100%;">Create Ticket</button>
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