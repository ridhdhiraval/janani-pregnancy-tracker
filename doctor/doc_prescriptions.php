<?php
// doc_prescriptions.php
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . "/../config/db.php";

// --- Helpers ---
function redirect($qs = "") {
    $loc = "doc_prescriptions.php";
    if ($qs) $loc .= "?" . $qs;
    header("Location: {$loc}");
    exit;
}

function isValidPatient($pdo, $patient_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = ?");
    $stmt->execute([$patient_id]);
    return $stmt->fetchColumn() > 0;
}

// --- ADD PRESCRIPTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_prescription'])) {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    if (!isValidPatient($pdo, $patient_id)) {
        die("Error: Selected patient does not exist.");
    }
    $prescribed_date = $_POST['prescribed_date'] ?? null;
    $medication_name = trim($_POST['medication_name'] ?? '');
    $dosage = trim($_POST['dosage'] ?? '');
    $instructions = trim($_POST['instructions'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    $stmt = $pdo->prepare("INSERT INTO prescriptions (patient_id, prescribed_date, medication_name, dosage, instructions, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$patient_id, $prescribed_date, $medication_name, $dosage, $instructions, $status]);
    redirect("added=1");
}

// --- UPDATE PRESCRIPTION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_prescription'])) {
    $id = intval($_POST['edit_id'] ?? 0);
    $patient_id = intval($_POST['edit_patient_id'] ?? 0);
    if (!isValidPatient($pdo, $patient_id)) {
        die("Error: Selected patient does not exist.");
    }
    $prescribed_date = $_POST['edit_prescribed_date'] ?? null;
    $medication_name = trim($_POST['edit_medication_name'] ?? '');
    $dosage = trim($_POST['edit_dosage'] ?? '');
    $instructions = trim($_POST['edit_instructions'] ?? '');
    $status = $_POST['edit_status'] ?? 'Active';

    $stmt = $pdo->prepare("UPDATE prescriptions SET patient_id=?, prescribed_date=?, medication_name=?, dosage=?, instructions=?, status=? WHERE id=?");
    $stmt->execute([$patient_id, $prescribed_date, $medication_name, $dosage, $instructions, $status, $id]);
    redirect("updated=1");
}

// --- DELETE PRESCRIPTION ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM prescriptions WHERE id=?");
    $stmt->execute([$id]);
    redirect("deleted=1");
}

// --- FETCH PATIENTS ---
$patients_stmt = $pdo->prepare("SELECT id, username FROM users WHERE role IS NULL OR role != 'doctor' ORDER BY username ASC");
$patients_stmt->execute();
$patients = $patients_stmt->fetchAll(PDO::FETCH_ASSOC);

// --- FETCH PRESCRIPTIONS ---
$prescriptions_stmt = $pdo->prepare("
    SELECT p.*, u.username AS patient_name
    FROM prescriptions p
    LEFT JOIN users u ON u.id = p.patient_id
    ORDER BY p.prescribed_date DESC, p.id DESC
");
$prescriptions_stmt->execute();
$prescriptions = $prescriptions_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Prescriptions - Doctor</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root {
    --bg: #fef9fb;
    --card: #ffffff; 
    --accent: #7ac7f9;
    --accent-dark: #3399cc; 
    --muted: #555; 
    --border: #e0e0e0; 
    --soft-shadow: 0 8px 20px rgba(0,0,0,0.08); 
    --hover-shadow: 0 0 25px rgba(51,153,204,0.4);
    --radius-lg: 16px; 
    --radius-md: 10px;
    font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #333;
}

body { margin:0; background: var(--bg); }

header {
    display:flex; align-items:center; justify-content:flex-start;
    padding:16px 32px; background: var(--bg);
    box-shadow: var(--soft-shadow); gap:20px;
    position:sticky; top:0; z-index:10;
}

.back { font-size:24px; padding:8px; border-radius:var(--radius-md); color:var(--muted); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.2s; }
.back:hover { background-color:#e0f7ff; }

.page-title { font-size:28px; font-weight:700; color:var(--accent-dark); }

.grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:25px; padding:25px; }

.card { background:var(--card); padding:25px; border-radius:var(--radius-lg); box-shadow:var(--soft-shadow); transition:0.3s; }
.card:hover { box-shadow: var(--hover-shadow); transform: translateY(-3px); }

.card h3 { margin-top:0; color:var(--accent-dark); font-size:20px; }
.form-row select, .form-row input, .form-row textarea { width:100%; padding:12px; margin-bottom:12px; border:1px solid var(--border); border-radius:var(--radius-md); font-size:14px; }
.form-row button { padding:10px 16px; background:var(--accent); color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:14px; transition:0.2s; }
.form-row button:hover { background: var(--accent-dark); }

.actions { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
.btn-outline { background:transparent; color:var(--accent-dark); border:1px solid var(--accent); padding:8px 12px; border-radius:8px; text-decoration:none; font-size:13px; transition:0.2s; }
.btn-outline:hover { background:var(--accent); color:#fff; }

.info p { margin:5px 0; color:var(--muted); font-size:14px; }
.status-active { color:green; font-weight:bold; }
.status-completed { color:red; font-weight:bold; }

.modal-back { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); display:none; justify-content:center; align-items:center; z-index:20; }
.modal { background:var(--card); width:450px; padding:25px; border-radius:var(--radius-lg); box-shadow:var(--soft-shadow); }
.modal h3 { margin:0 0 15px; color:var(--accent-dark); }
.modal .actions button { margin-top:10px; }
</style>
</head>
<body>

<header class="header">
    <a href="doc_dashboard.php" class="back">&#8592;</a>
    <span class="page-title">Prescriptions</span>
</header>

<div class="grid">

<!-- ADD PRESCRIPTION FORM -->
<div class="card">
<h3>Add New Prescription</h3>
<form method="POST" class="form-row">
<select name="patient_id" required>
<option hidden>Select Patient</option>
<?php foreach($patients as $p): ?>
<option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['username']) ?></option>
<?php endforeach; ?>
</select>
<input type="date" name="prescribed_date" required>
<input type="text" name="medication_name" placeholder="Medication Name" required>
<input type="text" name="dosage" placeholder="Dosage (optional)">
<textarea name="instructions" rows="2" placeholder="Instructions (optional)"></textarea>
<select name="status">
<option value="Active">Active</option>
<option value="Completed">Completed</option>
</select>
<button type="submit" name="add_prescription">Add Prescription</button>
</form>
</div>

<!-- LIST PRESCRIPTIONS -->
<?php foreach($prescriptions as $p): ?>
<div class="card">
<div class="info">
<h3><?= htmlspecialchars($p['patient_name'] ?? "Unknown") ?></h3>
<p><?= htmlspecialchars($p['prescribed_date']) ?> | <?= htmlspecialchars($p['medication_name']) ?></p>
<p>Dosage: <?= htmlspecialchars($p['dosage'] ?? '-') ?></p>
<p><?= nl2br(htmlspecialchars($p['instructions'] ?? '')) ?></p>
<p>Status: <span class="status-<?= strtolower($p['status']) ?>"><?= htmlspecialchars($p['status']) ?></span></p>
</div>
<div class="actions">
<button onclick="openEditModal(
'<?= $p['id'] ?>',
'<?= htmlspecialchars($p['patient_id']) ?>',
'<?= $p['prescribed_date'] ?>',
'<?= htmlspecialchars(addslashes($p['medication_name'])) ?>',
'<?= htmlspecialchars(addslashes($p['dosage'])) ?>',
`<?= htmlspecialchars(addslashes($p['instructions'])) ?>`,
'<?= $p['status'] ?>'
)">Edit</button>
<a class="btn-outline" href="?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this prescription?')">Delete</a>
</div>
</div>
<?php endforeach; ?>

</div>

<!-- EDIT MODAL -->
<div class="modal-back" id="editModal">
<div class="modal">
<h3>Edit Prescription</h3>
<form method="POST">
<input type="hidden" id="editId" name="edit_id">
<select id="editPatientId" name="edit_patient_id">
<?php foreach($patients as $p): ?>
<option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['username']) ?></option>
<?php endforeach; ?>
</select>
<input type="date" id="editPrescribedDate" name="edit_prescribed_date">
<input type="text" id="editMedicationName" name="edit_medication_name" placeholder="Medication Name" required>
<input type="text" id="editDosage" name="edit_dosage" placeholder="Dosage (optional)">
<textarea id="editInstructions" name="edit_instructions" rows="2" placeholder="Instructions (optional)"></textarea>
<select id="editStatus" name="edit_status">
<option value="Active">Active</option>
<option value="Completed">Completed</option>
</select>
<div class="actions">
<button type="button" onclick="closeModal()">Cancel</button>
<button type="submit" name="edit_prescription">Save Changes</button>
</div>
</form>
</div>
</div>

<script>
function openEditModal(id, patientId, date, medication, dosage, instructions, status){
    document.getElementById('editId').value = id;
    var sel = document.getElementById('editPatientId');
    for(var i=0;i<sel.options.length;i++){ if(sel.options[i].value==patientId){ sel.selectedIndex=i; break; } }
    document.getElementById('editPrescribedDate').value = date;
    document.getElementById('editMedicationName').value = medication;
    document.getElementById('editDosage').value = dosage;
    document.getElementById('editInstructions').value = instructions.replace(/\\r\\n/g,"\n");
    document.getElementById('editStatus').value = status;
    document.getElementById('editModal').style.display='flex';
}
function closeModal(){ document.getElementById('editModal').style.display='none'; }
</script>

</body>
</html>
