<?php
session_start();
require_once '../../config/db.php';

// Fetch documents with branch names
$query = "SELECT d.*, b.name as branch_name FROM documents d 
          JOIN branches b ON d.branch_id = b.branch_id 
          ORDER BY d.uploaded_at DESC";
$docs = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$branches = $pdo->query("SELECT * FROM branches WHERE status = 'Active'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<?php 
  $pageTitle = "Documents | MONIK Group"; // Change this per module
  include '../../includes/header.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management | Monik Group</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include '../../includes/topbar.php'; ?>
        <h2>📁 Document Management System</h2>

        <!-- Upload Form[cite: 1] -->
        <div class="upload-zone">
            <form id="uploadForm" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <input type="text" name="title" placeholder="Document Title" required style="width:100%; padding:8px;">
                    <select name="category" required style="padding:8px;">
                        <option value="Branch">Branch Folder</option>
                        <option value="Legal">Legal Documents</option>
                        <option value="Contract">Contracts</option>
                    </select>
                    <select name="branch_id" required style="padding:8px;">
                        <?php foreach($branches as $b): ?>
                            <option value="<?php echo $b['branch_id']; ?>"><?php echo $b['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-top: 15px; display: flex; gap: 15px; align-items: center;">
                    <label>Expiry Date (Optional):</label>
                    <input type="date" name="expiry_date" style="padding:8px;">
                    <input type="file" name="doc_file" required>
                    <button type="submit" style="background:#3498db; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Upload File</button>
                </div>
            </form>
        </div>

        <div class="file-grid">
            <?php foreach($docs as $d): ?>
            <div class="file-card">
                <span class="file-icon">📄</span>
                <strong><?php echo $d['title']; ?></strong><br>
                <span class="category-tag"><?php echo $d['category']; ?></span><br>
                <small><?php echo $d['branch_name']; ?></small>
                
                <?php if($d['expiry_date']): ?>
                    <div class="expiry-alert">Expires: <?php echo $d['expiry_date']; ?></div>
                <?php endif; ?>

                <div style="margin-top:15px;">
                    <a href="../../uploads/<?php echo $d['file_path']; ?>" target="_blank" style="text-decoration:none; color:#3498db; font-size:13px;">View File</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'upload_handler.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if(res.status === 'success') { location.reload(); }
                else { alert(res.message); }
            }
        });
    });
    </script>
</body>
</html>