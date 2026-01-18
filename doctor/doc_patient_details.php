<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../config/db.php'; // Ensure this path is correct

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("HTTP/1.0 400 Invalid Patient ID");
    die("Invalid patient ID");
}

$patient_id = $_GET['id'];
$patient = null;
$records = [
    'appointments' => [],
    'notes' => [],
    'prescriptions' => [],
    'reports' => []
];

try {
    // 1. Fetch basic user info
    $stmt = $pdo->prepare("SELECT id, username, email, role, status, created_at, updated_at 
                           FROM users 
                           WHERE id = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        header("HTTP/1.0 404 Patient Not Found");
        die("Patient not found");
    }

    // 2. Extended Patient Details
    $stmt = $pdo->prepare("SELECT age, gender, phone_number, address, emergency_contact 
                           FROM patient_details 
                           WHERE user_id = ?");
    $stmt->execute([$patient_id]);
    $patient_details = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['age' => 'N/A', 'gender' => 'N/A', 'phone_number' => 'N/A', 'address' => 'N/A', 'emergency_contact' => 'N/A'];
    
    $patient = array_merge($patient, $patient_details);

    // 3. Fetch Tab Data
    $stmt = $pdo->prepare("SELECT id, appt_date as date, appt_time as time, appt_type as type, status 
                           FROM appointments 
                           WHERE patient_id = ? 
                           ORDER BY appt_date DESC");
    $stmt->execute([$patient_id]);
    $records['appointments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, note_date as date, content 
                           FROM clinical_notes 
                           WHERE patient_id = ? 
                           ORDER BY note_date DESC");
    $stmt->execute([$patient_id]);
    $records['notes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, prescribed_date as date, medication_name as medication, dosage, instructions, status 
                           FROM prescriptions 
                           WHERE patient_id = ? 
                           ORDER BY prescribed_date DESC");
    $stmt->execute([$patient_id]);
    $records['prescriptions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, report_date as date, report_type as type, summary as content 
                           FROM lab_reports 
                           WHERE patient_id = ? 
                           ORDER BY report_date DESC");
    $stmt->execute([$patient_id]);
    $records['reports'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Database Query Error: " . $e->getMessage() . " ❌"); 
}

$data_json = json_encode($records);
$avatar_initial = strtoupper(substr($patient['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Patient Details — <?= htmlspecialchars($patient['username']) ?></title>
<style>
/* ----------------------------------- GLOBAL & THEME (PASTEL PINK) ----------------------------------- */
:root {
    --bg: #fefafa; 
    --card: #ffffff; 
    --accent: #e88fb6; 
    --accent-dark: #b94f78; 
    --muted: #7a5d6a; 
    --border: #f5e8ea; 
    --soft-shadow: 0 4px 15px rgba(0,0,0,0.04);
    
    /* Status Colors */
    --critical: #e04068; 
    --upcoming: #f0c7d2; 
    --completed: #16a34a; 
    --active: #16a34a; 

    --radius-lg: 16px;
    --radius-md: 10px;
    font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #333;
}
html, body { min-height: 100%; margin: 0; background: var(--bg); overflow-x: hidden; }

.container { max-width: 1200px; margin: auto; padding: 40px 20px; box-sizing: border-box; }
.header { display: flex; align-items: center; gap: 20px; padding: 20px 0 10px; }
.back-btn { text-decoration:none; color:var(--muted); font-size:18px; padding:10px 14px; border-radius:10px; background:#f7e9ee; transition:0.2s; }
.back-btn:hover { background:#f3dbe4; }
.page-title { font-size: 34px; font-weight: 800; color: var(--accent-dark); margin: 0; }

.content-wrapper { 
    display: grid; 
    grid-template-columns: 350px 1fr; 
    gap: 40px; 
    padding-bottom: 50px; 
    margin-top: 20px;
}
.profile-card {
    background: var(--card);
    padding: 30px;
    border-radius: var(--radius-lg);
    box-shadow: var(--soft-shadow);
    border-top: 5px solid var(--accent);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    height: fit-content; 
    position: sticky; 
    top: 20px; 
}
.avatar { width: 120px; height: 120px; border-radius: 50%; background:#f6d6e0; display: flex; align-items: center; justify-content: center; font-size:48px; font-weight:700; color:#b94f78; margin-bottom: 15px;}
.info h2 { margin: 5px 0 0 0; font-size: 28px; color: var(--accent-dark); }
.status-badge { margin: 15px 0 25px 0; padding: 8px 16px; border-radius: 18px; font-weight: 700; font-size: 15px; display: inline-block; }
.status_active { background: #e8fbea; color: #1a8a42; }
.status_inactive { background: #ffe6ea; color: #d33b56; }
.profile-actions button { background: var(--accent); color: white; padding: 10px 20px; border-radius: var(--radius-md); border: none; font-weight: 600; cursor: pointer; transition: background-color 0.2s; }
.profile-actions button:hover { background: var(--accent-dark); }
.contact-details { width: 100%; text-align: left; border-top: 1px solid var(--border); padding-top: 25px; margin-top: 20px;}
.contact-details div { display: flex; gap: 10px; margin-bottom: 10px; color: #444; font-size: 15px; }
.contact-details div strong { color: #222; font-weight: 600; width: 80px; flex-shrink: 0; }

.record-details-area { min-width: 0; }
.tab-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.tabs { display: flex; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid var(--border); padding-bottom: 5px; }
.tab { padding: 10px 18px; cursor: pointer; font-weight: 600; color: var(--muted); transition: color 0.2s; position: relative; top: 2px; }
.tab:hover { color: var(--accent-dark); }
.tab.active { color: var(--accent-dark); border-bottom: 2px solid var(--accent-dark); }

.action-buttons button { background: var(--accent) !important; color: white !important; border: none !important; padding: 10px 20px !important; border-radius: var(--radius-md);}
.action-buttons button:hover { background: var(--accent-dark) !important; }
.grid { display: flex; flex-direction: column; gap: 16px; }

.data-card { background: var(--card); padding: 20px; border-radius: var(--radius-lg); box-shadow: var(--soft-shadow); display: flex; justify-content: space-between; align-items: center; gap: 20px; border-left: 5px solid #ccc; }
.date-section { flex-shrink: 0; width: 60px; text-align: center; padding-right: 20px; border-right: 1px solid var(--border); line-height: 1.2; }
.date-section .day { font-size: 32px; font-weight: 800; color: var(--accent-dark); }
.date-section .month { font-size: 14px; font-weight: 600; color: var(--muted); text-transform: uppercase; }
.card-content { flex-grow: 1; }
.card-content h3 { margin: 0 0 5px 0; font-size: 18px; color: #222; font-weight: 600; }
.card-content p { margin: 0; color: #444; font-size: 15px; line-height: 1.5; }
.actions { display: flex; gap: 10px; align-items: center; }
.actions button { padding: 8px 16px; border-radius: var(--radius-md); font-weight: 600; font-size: 14px; cursor: pointer; border: 1px solid var(--accent); background: transparent; color: var(--accent-dark); transition: all .2s; }
.btn-delete { background: none; border: none; color: var(--critical); font-size: 18px; cursor: pointer; }

.Critical { border-left-color: var(--critical); }
.Upcoming { border-left-color: var(--upcoming); }
.Completed { border-left-color: var(--completed); }
.Active { border-left-color: var(--active); }

.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: none; justify-content: center; align-items: center; z-index: 1000; }
.modal-overlay.active { display: flex; }
.modal-content { background: var(--card); padding: 30px; border-radius: var(--radius-lg); width: 90%; max-width: 650px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 14px; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-md); box-sizing: border-box; }
.modal-actions { display: flex; justify-content: flex-end; gap: 10px; padding-top: 10px; }
.btn-cancel { background: #e0e0e0; color: #555; border: none; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;}
.btn-save { background: var(--accent); color: white; border: none; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 600; cursor: pointer;}

@media(max-width: 900px){ 
    .content-wrapper { grid-template-columns: 1fr; gap: 20px; }
    .profile-card { position: static; }
    .tab-controls { flex-direction: column; align-items: flex-start; gap: 15px; }
    .tabs { width: 100%; justify-content: space-between; }
    .tab { flex: 1; text-align: center; }
}
</style>
</head>
<body>

<div class="container">
    <header class="header">
        <a href="doc_patients.php" class="back-btn" title="Back to Patients List">← Back</a>
        <div class="page-info">
            <h1 class="page-title">Patient Profile</h1>
        </div>
    </header>

    <div class="content-wrapper">
        
        <section class="profile-card">
            <div class="avatar">
                <?= $avatar_initial ?>
            </div>
            <div class="info">
                <h2><?= htmlspecialchars($patient['username']) ?></h2>
                <p style="color:var(--muted); margin-top:0;">Patient ID: <?= $patient['id'] ?></p>
                
                <span class="status-badge status_<?= $patient['status'] ?>">
                    <?= ucfirst($patient['status']) ?>
                </span>
            </div>

            

            <div class="contact-details">
                <div><strong>Age:</strong> <?= htmlspecialchars($patient['age']) ?></div>
                <div><strong>Gender:</strong> <?= htmlspecialchars($patient['gender']) ?></div>
                <div style="border-top: 1px dashed var(--border); padding-top: 10px; margin-top: 10px;"></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($patient['email']) ?></div>
                <div><strong>Phone:</strong> <?= htmlspecialchars($patient['phone_number']) ?></div>
                <div><strong>Address:</strong> <?= htmlspecialchars($patient['address']) ?></div>
                <div><strong>Emergency:</strong> <?= htmlspecialchars($patient['emergency_contact']) ?></div>
            </div>
        </section>

        <div class="record-details-area">
            
            <div class="tab-controls">
                <div class="tabs">
                    <div class="tab active" data-tab="appointments">Appointments</div>
                    <div class="tab" data-tab="notes">Clinical Notes</div>
                    <div class="tab" data-tab="prescriptions">Prescriptions</div>
                    <div class="tab" data-tab="reports">Reports</div>
                </div>
                <div class="action-buttons">
                    <button class="btn-add" onclick="openModalForRecord(null, currentTab)">
                        + Add New
                    </button>
                </div>
            </div>
            
            <main class="grid" id="grid"></main>
            
            <h2 class="section-title" style="margin-top: 50px;">Account Information</h2>
            <div class="details-box">
                <p><span class="label">Role:</span> <?= ucfirst($patient['role']) ?></p>
                <p><span class="label">Created On:</span> <?= date("d M Y, h:i A", strtotime($patient['created_at'])) ?></p>
                <p><span class="label">Last Updated:</span> <?= date("d M Y, h:i A", strtotime($patient['updated_at'])) ?></p>
            </div>
        </div>

    </div>
</div>


<div id="recordModal" class="modal-overlay" onclick="if(event.target.id === 'recordModal') closeModal()">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Edit Record</h2>
            <button type="button" class="close-button" onclick="closeModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:var(--muted);">&times;</button>
        </div>
        <form id="recordForm" onsubmit="handleSave(event)">
            <input type="hidden" id="recordId">
            <input type="hidden" id="recordType">
            
            <div id="formFields">
                </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<script>
// --- JavaScript Global Variables ---
let DATA = JSON.parse(JSON.stringify(<?= $data_json; ?>));
let currentTab = 'appointments';
let nextId = 100000; // fallback for client-only IDs (shouldn't be used when server returns id)

const grid = document.getElementById('grid');
const tabs = document.querySelectorAll('.tab');
const modal = document.getElementById('recordModal');
const formFieldsDiv = document.getElementById('formFields');
const modalTitle = document.getElementById('modalTitle');

const titleMap = {
    'appointments': 'Appointment',
    'notes': 'Clinical Note',
    'prescriptions': 'Prescription',
    'reports': 'Diagnostic Report'
};

const iconMap = {
    'appointments': '📅',
    'notes': '✍️',
    'prescriptions': '💊',
    'reports': '📄'
};

// Utility
function formatDateForCard(dateString) {
    if (!dateString) return { day:'', month:'', year:'' };
    const date = new Date(dateString + 'T00:00:00');
    const day = date.getDate();
    const month = date.toLocaleString('en-US', { month: 'short' }).toUpperCase();
    const year = date.getFullYear();
    return { day, month, year };
}

function generateFormFields(type, record = {}) {
    let fieldsHtml = `<div class="form-group"><label for="date">Date</label><input type="date" id="date" name="date" value="${record.date || ''}" required></div>`;
    const time = (record.time && record.time.includes(':') ? record.time.substring(0,5) : '');

    if (type === 'appointments') {
        const status = record.status || 'Upcoming';
        fieldsHtml += `
            <div class="form-group"><label for="time">Time</label><input type="time" id="time" name="time" value="${time}"></div>
            <div class="form-group"><label for="type">Appointment Type</label><input type="text" id="type" name="type" value="${record.type || ''}" required></div>
            <div class="form-group"><label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Upcoming" ${status === 'Upcoming' ? 'selected' : ''}>Upcoming</option>
                    <option value="Completed" ${status === 'Completed' ? 'selected' : ''}>Completed</option>
                    <option value="Critical" ${status === 'Critical' ? 'selected' : ''}>Critical</option>
                </select>
            </div>
        `;
    } else if (type === 'notes') {
        fieldsHtml += `<div class="form-group"><label for="content">Clinical Note</label><textarea id="content" name="content" rows="6" required>${record.content || ''}</textarea></div>`;
    } else if (type === 'prescriptions') {
        const status = record.status || 'Active';
        fieldsHtml += `
            <div class="form-group"><label for="medication">Medication</label><input type="text" id="medication" name="medication" value="${record.medication || ''}" required></div>
            <div class="form-group"><label for="dosage">Dosage</label><input type="text" id="dosage" name="dosage" value="${record.dosage || ''}" required></div>
            <div class="form-group"><label for="status_rx">Status</label>
                <select id="status_rx" name="status" required>
                    <option value="Active" ${status === 'Active' ? 'selected' : ''}>Active</option>
                    <option value="Completed" ${status === 'Completed' ? 'selected' : ''}>Completed</option>
                </select>
            </div>
            <div class="form-group"><label for="instructions">Instructions</label><textarea id="instructions" name="instructions" rows="4" required>${record.instructions || ''}</textarea></div>
        `;
    } else if (type === 'reports') {
        fieldsHtml += `
            <div class="form-group"><label for="type">Report Type (e.g., X-Ray, CBC)</label><input type="text" id="type" name="type" value="${record.type || ''}" required></div>
            <div class="form-group"><label for="content">Report Findings / Summary</label><textarea id="content" name="content" rows="6" required>${record.content || ''}</textarea></div>
        `;
    }

    return fieldsHtml;
}

// Modal handlers
function openModalForRecord(id, type) {
    const isEdit = id !== null;
    let record = {};
    if (isEdit) record = DATA[type].find(item => item.id === id) || {};
    modalTitle.textContent = isEdit ? `Edit ${titleMap[type]}` : `Add New ${titleMap[type]}`;
    document.getElementById('recordId').value = id || 'new';
    document.getElementById('recordType').value = type;
    formFieldsDiv.innerHTML = generateFormFields(type, record);
    modal.style.display = 'flex';
    setTimeout(()=> modal.classList.add('active'), 10);
}

function closeModal() {
    modal.classList.remove('active');
    setTimeout(()=>{ modal.style.display = 'none'; }, 250);
    const frm = document.getElementById('recordForm');
    if (frm) frm.reset();
}

// AJAX save
async function handleSave(event) {
    event.preventDefault();
    const id = document.getElementById('recordId').value;
    const type = document.getElementById('recordType').value;
    const patientId = <?= $patient_id ?>;

    const payload = { action: id === 'new' ? 'add' : 'update', type, patient_id: patientId };

    const form = event.target;
    for (let i = 0; i < form.elements.length; i++) {
        const el = form.elements[i];
        if (!el.name) continue;
        // Map the `type` form field to type_name for server for appointments/reports
        if ((type === 'appointments' || type === 'reports') && el.name === 'type') {
            payload['type_name'] = el.value;
        } else {
            payload[el.name] = el.value;
        }
    }
    if (id !== 'new') payload.id = id;

    try {
        const res = await fetch('patient_record_actions.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify(payload)
        });
        const json = await res.json();

        if (!res.ok) {
            alert('Server error: ' + (json.error || res.statusText));
            return;
        }

        if (json.success) {
            // Build/patch local DATA entry to reflect server state
            if (id === 'new') {
                // created new
                const newId = parseInt(json.id, 10) || nextId++;
                const newRec = { id: newId };

                if (type === 'appointments') {
                    newRec.date = payload.date || '';
                    newRec.time = payload.time || '';
                    newRec.type = payload.type_name || '';
                    newRec.status = payload.status || '';
                } else if (type === 'notes') {
                    newRec.date = payload.date || '';
                    newRec.content = payload.content || '';
                } else if (type === 'prescriptions') {
                    newRec.date = payload.date || '';
                    newRec.medication = payload.medication || '';
                    newRec.dosage = payload.dosage || '';
                    newRec.instructions = payload.instructions || '';
                    newRec.status = payload.status || '';
                } else if (type === 'reports') {
                    newRec.date = payload.date || '';
                    newRec.type = payload.type_name || '';
                    newRec.content = payload.content || '';
                }

                DATA[type].unshift(newRec); // add to top
            } else {
                // update existing in DATA
                const idx = DATA[type].findIndex(x => x.id.toString() === id.toString());
                if (idx !== -1) {
                    if (type === 'appointments') {
                        DATA[type][idx].date = payload.date || '';
                        DATA[type][idx].time = payload.time || '';
                        DATA[type][idx].type = payload.type_name || '';
                        DATA[type][idx].status = payload.status || '';
                    } else if (type === 'notes') {
                        DATA[type][idx].date = payload.date || '';
                        DATA[type][idx].content = payload.content || '';
                    } else if (type === 'prescriptions') {
                        DATA[type][idx].date = payload.date || '';
                        DATA[type][idx].medication = payload.medication || '';
                        DATA[type][idx].dosage = payload.dosage || '';
                        DATA[type][idx].instructions = payload.instructions || '';
                        DATA[type][idx].status = payload.status || '';
                    } else if (type === 'reports') {
                        DATA[type][idx].date = payload.date || '';
                        DATA[type][idx].type = payload.type_name || '';
                        DATA[type][idx].content = payload.content || '';
                    }
                }
            }

            closeModal();
            renderGrid();
        } else {
            alert('Error: ' + (json.error || 'Unknown error'));
        }

    } catch (err) {
        console.error(err);
        alert('Network error while saving');
    }
}

// AJAX delete
async function handleDeleteRecord(id, type) {
    if (!confirm(`Are you sure you want to permanently delete this ${titleMap[type]} record (ID: ${id})?`)) return;
    try {
        const res = await fetch('patient_record_actions.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ action: 'delete', type, id, patient_id: <?= $patient_id ?> })
        });
        const json = await res.json();
        if (!res.ok) {
            alert('Server error: ' + (json.error || res.statusText));
            return;
        }
        if (json.success) {
            DATA[type] = DATA[type].filter(item => item.id !== id);
            renderGrid();
        } else {
            alert('Error: ' + (json.error || 'Unknown error'));
        }
    } catch (err) {
        console.error(err);
        alert('Network error while deleting');
    }
}

// Render grid
function renderGrid(){
    grid.innerHTML = '';
    const items = (DATA[currentTab] || []).slice().sort((a,b) => new Date(b.date) - new Date(a.date));

    if (items.length === 0) {
        grid.innerHTML = `<p style="text-align: center; color: var(--muted); padding: 50px;">No ${currentTab} found for this patient. Click "Add New" to create one.</p>`;
        return;
    }

    const currentIcon = iconMap[currentTab] || '';

    items.forEach(item => {
        const statusClass = (item.status || '').toString().replace(/\s/g,'').replace(/-/g,'');
        const { day, month, year } = formatDateForCard(item.date || '');
        let mainDetail = '';
        let secondaryDetail = '';

        if (currentTab === 'appointments') {
            mainDetail = item.type || '';
            secondaryDetail = `Time: ${item.time || 'N/A'} | Status: <span class="status-badge ${statusClass}" style="padding:4px 8px;font-size:13px;">${item.status || ''}</span>`;
        } else if (currentTab === 'notes') {
            mainDetail = 'Clinical Note';
            const c = item.content || '';
            secondaryDetail = c ? (c.length > 120 ? c.substring(0,120) + '...' : c) : '';
        } else if (currentTab === 'prescriptions') {
            mainDetail = item.medication || '';
            secondaryDetail = `Dosage: ${item.dosage || ''} | Status: <span class="status-badge ${statusClass}" style="padding:4px 8px;font-size:13px;">${item.status || ''}</span>`;
        } else if (currentTab === 'reports') {
            mainDetail = item.type || '';
            const c = item.content || '';
            secondaryDetail = c ? (c.length > 120 ? c.substring(0,120) + '...' : c) : '';
        }

        const card = document.createElement('div');
        card.className = `data-card ${statusClass}`;
        card.innerHTML = `
            <div class="date-section">
                <div class="day">${day}</div>
                <div class="month">${month} ${year}</div>
            </div>
            <div class="card-content">
                <h3>${currentIcon} ${mainDetail}</h3>
                <p style="margin-top:5px;">${secondaryDetail}</p>
            </div>
            <div class="actions">
                <button onclick="openModalForRecord(${item.id},'${currentTab}')">Edit</button>
                <button class="btn-delete" title="Delete Record" onclick="handleDeleteRecord(${item.id},'${currentTab}')">🗑️</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Tab logic
tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(tab => tab.classList.remove('active'));
    t.classList.add('active');
    currentTab = t.dataset.tab;
    renderGrid();
}));

// Close modal on ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
});

// Initial render
renderGrid();
</script>

</body>
</html>
