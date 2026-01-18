<?php
require_once '../config/db.php';

// --- ADD REPORT ---
if(isset($_POST['add_report'])){
    $patient_id = intval($_POST['patient_id']);
    $report_type = $_POST['report_type'];
    $summary = $_POST['summary'];
    $report_date = $_POST['report_date'];

    $fileName = NULL;
    if(!empty($_FILES['report_file']['name'])){
        $uploadDir = '../uploads/reports/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time().'_'.basename($_FILES['report_file']['name']);
        move_uploaded_file($_FILES['report_file']['tmp_name'], $uploadDir.$fileName);
    }

    $stmt = $pdo->prepare('INSERT INTO lab_reports (patient_id, report_date, report_type, summary, file) VALUES (?,?,?,?,?)');
    $stmt->execute([$patient_id,$report_date,$report_type,$summary,$fileName]);
    header('Location: doc_reports.php'); exit;
}

// --- EDIT REPORT ---
if(isset($_POST['edit_report'])){
    $id = intval($_POST['edit_id']);
    $patient_id = intval($_POST['edit_patient_id']);
    $report_type = $_POST['edit_report_type'];
    $summary = $_POST['edit_summary'];
    $report_date = $_POST['edit_report_date'];

    $fileName = NULL;
    if(!empty($_FILES['edit_report_file']['name'])){
        $uploadDir = '../uploads/reports/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time().'_'.basename($_FILES['edit_report_file']['name']);
        move_uploaded_file($_FILES['edit_report_file']['tmp_name'], $uploadDir.$fileName);
        // delete old file
        $old = $pdo->prepare('SELECT file FROM lab_reports WHERE id=?');
        $old->execute([$id]);
        $oldFile = $old->fetchColumn();
        if($oldFile && file_exists($uploadDir.$oldFile)) unlink($uploadDir.$oldFile);
    }

    if($fileName){
        $stmt = $pdo->prepare('UPDATE lab_reports SET patient_id=?, report_date=?, report_type=?, summary=?, file=? WHERE id=?');
        $stmt->execute([$patient_id,$report_date,$report_type,$summary,$fileName,$id]);
    } else {
        $stmt = $pdo->prepare('UPDATE lab_reports SET patient_id=?, report_date=?, report_type=?, summary=? WHERE id=?');
        $stmt->execute([$patient_id,$report_date,$report_type,$summary,$id]);
    }
    header('Location: doc_reports.php'); exit;
}

// --- DELETE REPORT ---
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare('SELECT file FROM lab_reports WHERE id=?');
    $stmt->execute([$id]);
    $file = $stmt->fetchColumn();
    if($file && file_exists('../uploads/reports/'.$file)) unlink('../uploads/reports/'.$file);

    $del = $pdo->prepare('DELETE FROM lab_reports WHERE id=?');
    $del->execute([$id]);
    header('Location: doc_reports.php'); exit;
}

// --- FETCH PATIENTS ---
$patients = $pdo->query('SELECT id, username FROM users')->fetchAll();

// --- FETCH REPORTS ---
$reports = $pdo->query('SELECT lr.*, u.username AS patient_name FROM lab_reports lr LEFT JOIN users u ON u.id=lr.patient_id ORDER BY lr.report_date DESC')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Pregnancy Reports</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- === YOUR ORIGINAL CSS === -->
<style>
        :root {
            --bg: #fef9fb;
            --card: #ffffff; 
            --accent: #f7a8b8;
            --accent-dark: #d77890; 
            --muted: #8b646f; 
            --border: #f3e5e8; 
            --soft-shadow: 0 12px 24px rgba(0,0,0,0.08); 
            --hover-shadow: 0 16px 32px rgba(0,0,0,0.15);
            --radius-lg: 16px; 
            --radius-md: 10px;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
        }

        body {
            margin: 0;
            background: var(--bg);
        }

        /* ---------- HEADER ---------- */
        header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 16px 32px;
            background: var(--bg);
            box-shadow: var(--soft-shadow);
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .back {
            font-size: 24px;
            padding: 8px;
            border-radius: var(--radius-md);
            color: var(--muted);
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .back:hover {
            background-color: #fcebeb;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-dark);
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 35px auto;
            background: var(--card);
            padding: 35px;
            border-radius: var(--radius-lg);
            box-shadow: var(--soft-shadow);
        }

        /* ---------- HEADERS WITH ICONS ---------- */
        h2 {
            color: var(--accent-dark);
            font-size: 24px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 i {
            font-size: 1.4em;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group select,
        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 15px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            font-size: 16px;
            background: var(--bg);
        }

        .input-group label {
            position: absolute;
            left: 18px;
            top: -10px;
            background: var(--card);
            padding: 0 5px;
            color: var(--accent);
            font-size: 13px;
            font-weight: bold;
        }

        select {
            color: var(--accent-dark);
            font-weight: bold;
        }

        .reports-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 20px;
        }

        .report-card {
            background: var(--card);
            padding: 25px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--soft-shadow);
            transition: 0.2s;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
            border-color: var(--accent);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ffc0cb;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .card-patient {
            color: var(--accent-dark);
            font-weight: bold;
        }

        .card-type {
            background: #ffd3e0;
            padding: 5px 12px;
            border-radius: 6px;
            color: var(--accent-dark);
            font-weight: 600;
            margin-bottom: 10px;
            display: inline-block;
        }

        .card-summary {
            font-size: 0.95em;
            border-left: 3px solid var(--accent);
            padding-left: 10px;
            color: var(--muted);
            margin-bottom: 15px;
        }

        .card-actions a,
        .card-actions button {
            background: var(--accent);
            padding: 10px 16px;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            margin-right: 8px;
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: #ff4d6d !important;
        }

        .modal-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background: var(--card);
            width: 450px;
            padding: 25px;
            border-radius: var(--radius-lg);
            box-shadow: var(--soft-shadow);
        }

        .modal h3 {
            margin: 0 0 15px;
            color: var(--accent-dark);
        }
    </style>
</head>
<body>

<header>
    <a href="doc_dashboard.php" class="back">&#8592;</a>
    <span class="page-title">Pregnancy Reports</span>
</header>

<div class="container">

<h2><i class="fas fa-plus-circle"></i> Add New Report</h2>
<form method="POST" enctype="multipart/form-data">
    <select name="patient_id" required>
        <option hidden>Select Patient</option>
        <?php foreach($patients as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['username'] ?></option>
        <?php endforeach; ?>
    </select>

    <input type="date" name="report_date" required>

    <select name="report_type" required>
        <option hidden>Select Report Type</option>
        <option>General Pregnancy Checkup</option>
        <option>Ultrasound Summary</option>
        <option>Blood Test Summary</option>
        <option>Doctor Notes</option>
        <option>Upload Only</option>
    </select>

    <textarea name="summary" rows="2" placeholder="Summary (optional)"></textarea>
    <input type="file" name="report_file">
    <button type="submit" name="add_report">Add Report</button>
</form>

<hr>
<h2><i class="fas fa-file-alt"></i> All Reports</h2>
<div class="reports-list">
<?php foreach($reports as $r): ?>
<div class="report-card">
    <div class="card-header">
        <span class="card-patient"><?= $r['patient_name'] ?></span>
        <span class="card-date"><?= $r['report_date'] ?></span>
    </div>
    <span class="card-type"><?= $r['report_type'] ?></span>
    <div class="card-summary"><?= $r['summary'] ?></div>
    <div class="card-actions">
        <?php if($r['file']): ?>
        <a href="../uploads/reports/<?= $r['file'] ?>" target="_blank">View</a>
        <a href="../uploads/reports/<?= $r['file'] ?>" download>Download</a>
        <?php endif; ?>
        <button onclick="openEditModal('<?= $r['id'] ?>','<?= $r['patient_id'] ?>','<?= $r['report_type'] ?>','<?= $r['report_date'] ?>','<?= addslashes($r['summary']) ?>')">Edit</button>
        <a class="delete-btn" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this report?')">Delete</a>
    </div>
</div>
<?php endforeach; ?>
</div>
</div>

<div class="modal-bg" id="editModal">
<div class="modal">
<h3>Edit Report</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="edit_id" id="editId">
    <select name="edit_patient_id" id="editPatientId" required>
        <?php foreach($patients as $p): ?>
            <option value="<?= $p['id'] ?>"><?= $p['username'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="date" name="edit_report_date" id="editReportDate" required>
    <select name="edit_report_type" id="editReportType" required>
        <option>General Pregnancy Checkup</option>
        <option>Ultrasound Summary</option>
        <option>Blood Test Summary</option>
        <option>Doctor Notes</option>
        <option>Upload Only</option>
    </select>
    <textarea name="edit_summary" id="editSummary" rows="3"></textarea>
    <input type="file" name="edit_report_file">
    <button type="submit" name="edit_report">Save Changes</button>
    <button type="button" class="delete-btn" onclick="closeModal()">Cancel</button>
</form>
</div>
</div>

<script>
function openEditModal(id, patientId, type, date, summary){
    document.getElementById('editId').value = id;
    var sel = document.getElementById('editPatientId');
    for(var i=0;i<sel.options.length;i++){ if(sel.options[i].value==patientId){ sel.selectedIndex=i; break; } }
    var st = document.getElementById('editReportType');
    for(var i=0;i<st.options.length;i++){ if(st.options[i].text==type){ st.selectedIndex=i; break; } }
    document.getElementById('editReportDate').value = date;
    document.getElementById('editSummary').value = summary.replace(/\r\n/g,'\n');
    document.getElementById('editModal').style.display='flex';
}
function closeModal(){ document.getElementById('editModal').style.display='none'; }
</script>

</body>
</html>
