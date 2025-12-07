<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$current_lang = $_GET['lang'] ?? 'en';
$target_id = isset($_GET['id']) ? (int)$_GET['id'] : ($user['id'] ?? null);

$doctors = [];
$patient = null;
$appointments = [];
$notes = [];
$prescriptions = [];
$labs = [];

if ($target_id) {
    try { $stmt = $pdo->prepare('SELECT id, user_id, specialization, qualification, experience_years, phone, gender, dob, address, status, created_at, updated_at FROM doctors WHERE user_id = ? ORDER BY created_at DESC'); $stmt->execute([$target_id]); $doctors = $stmt->fetchAll(); } catch (Exception $e) { $doctors = []; }
    try { $stmt = $pdo->prepare('SELECT id, user_id, age, gender, phone_number, address, emergency_contact FROM patient_details WHERE user_id = ? LIMIT 1'); $stmt->execute([$target_id]); $patient = $stmt->fetch(); } catch (Exception $e) { $patient = null; }
    try { $stmt = $pdo->prepare('SELECT id, appt_date, appt_time, appt_type, status FROM appointments WHERE patient_id = ? ORDER BY appt_date DESC, appt_time DESC'); $stmt->execute([$target_id]); $appointments = $stmt->fetchAll(); } catch (Exception $e) { $appointments = []; }
    try { $stmt = $pdo->prepare('SELECT id, note_date, content FROM clinical_notes WHERE patient_id = ? ORDER BY note_date DESC'); $stmt->execute([$target_id]); $notes = $stmt->fetchAll(); } catch (Exception $e) { $notes = []; }
    try { $stmt = $pdo->prepare('SELECT id, prescribed_date, medication_name, dosage, instructions, status FROM prescriptions WHERE patient_id = ? ORDER BY prescribed_date DESC'); $stmt->execute([$target_id]); $prescriptions = $stmt->fetchAll(); } catch (Exception $e) { $prescriptions = []; }
    try { $stmt = $pdo->prepare('SELECT id, report_date, report_type, summary FROM lab_reports WHERE patient_id = ? ORDER BY report_date DESC'); $stmt->execute([$target_id]); $labs = $stmt->fetchAll(); } catch (Exception $e) { $labs = []; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Doctor Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{ --bg:#f7f3ed; --text:#4b4b4b; --white:#fff; --brand:#e07f91; --muted:#6b7280; --border:#e5e7eb; --accent:#f8e5e8; --nav:#fff; }
        body{ margin:0; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); }
        .navbar{ position:sticky; top:0; background:var(--nav); border-bottom:1px solid var(--border); z-index:20; }
        .nav-inner{ max-width:1200px; margin:0 auto; padding:12px 24px; display:flex; align-items:center; justify-content:space-between; }
        .brand{ font-weight:800; color:var(--brand); font-size:18px; }
        .back{ text-decoration:none; color:#111; background:var(--accent); padding:8px 14px; border-radius:10px; }
        .nav-links a{ margin-left:14px; text-decoration:none; color:#333; padding:6px 10px; border-radius:8px; }
        .nav-links a:hover{ background:#f3f4f6; }
        .content{ padding:24px; max-width:1200px; margin:0 auto; }
        .card{ background:var(--white); border:1px solid var(--border); border-radius:16px; padding:20px; box-shadow:0 6px 16px rgba(0,0,0,.06); margin-bottom:16px; }
        .section-title{ font-weight:800; color:#111; margin-bottom:10px; font-size:16px; }
        table{ width:100%; border-collapse:separate; border-spacing:0; }
        thead th{ background:#fafafa; color:#111; font-weight:600; border-bottom:1px solid var(--border); padding:10px; }
        tbody td{ padding:10px; border-bottom:1px solid var(--border); vertical-align:top; }
        tbody tr:nth-child(even){ background:#fcfcfc; }
        .empty{ color:var(--muted); font-style:italic; }
        @media (max-width: 900px){ .content{ padding:16px; } .brand{ font-size:16px; } .nav-links{ display:none; } }
    </style>
    </head>
<body>
    <div class="navbar">
        <div class="nav-inner">
            <a class="back" href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back</a>
            <div class="brand">Doctor & Health</div>
            <div class="nav-links">
                <a href="#doctors">Doctors</a>
                <a href="#patient">Patient</a>
                <a href="#appointments">Appointments</a>
                <a href="#notes">Notes</a>
                <a href="#prescriptions">Prescriptions</a>
                <a href="#labs">Lab Reports</a>
            </div>
        </div>
    </div>
    <div class="content">
            <?php if (!$target_id): ?>
                <div class="card"><div class="empty">Sign in to view your doctor details.</div></div>
            <?php else: ?>
                <div id="doctors" class="card">
                    <div class="section-title">Doctors</div>
                    <table>
                        <thead><tr><th>Specialization</th><th>Qualification</th><th>Experience</th><th>Phone</th><th>Gender</th><th>DOB</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach($doctors as $d): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($d['specialization']); ?></td>
                                    <td><?php echo htmlspecialchars($d['qualification']); ?></td>
                                    <td><?php echo (int)$d['experience_years']; ?></td>
                                    <td><?php echo htmlspecialchars($d['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($d['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($d['dob']); ?></td>
                                    <td><?php echo htmlspecialchars($d['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($doctors)): ?><tr><td colspan="7" class="empty">No doctors found</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="patient" class="card">
                    <div class="section-title">Patient Details</div>
                    <?php if ($patient): ?>
                    <table>
                        <tbody>
                            <tr><th>Age</th><td><?php echo (int)$patient['age']; ?></td></tr>
                            <tr><th>Gender</th><td><?php echo htmlspecialchars($patient['gender']); ?></td></tr>
                            <tr><th>Phone</th><td><?php echo htmlspecialchars($patient['phone_number']); ?></td></tr>
                            <tr><th>Address</th><td><?php echo htmlspecialchars($patient['address']); ?></td></tr>
                            <tr><th>Emergency Contact</th><td><?php echo htmlspecialchars($patient['emergency_contact']); ?></td></tr>
                        </tbody>
                    </table>
                    <?php else: ?><div class="empty">No patient details</div><?php endif; ?>
                </div>
                <div id="appointments" class="card">
                    <div class="section-title">Appointments</div>
                    <table>
                        <thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach($appointments as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['appt_date']); ?></td>
                                    <td><?php echo htmlspecialchars($a['appt_time']); ?></td>
                                    <td><?php echo htmlspecialchars($a['appt_type']); ?></td>
                                    <td><?php echo htmlspecialchars($a['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($appointments)): ?><tr><td colspan="4" class="empty">No appointments</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="notes" class="card">
                    <div class="section-title">Clinical Notes</div>
                    <table>
                        <thead><tr><th>Date</th><th>Content</th></tr></thead>
                        <tbody>
                            <?php foreach($notes as $n): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($n['note_date']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($n['content'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($notes)): ?><tr><td colspan="2" class="empty">No clinical notes</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="prescriptions" class="card">
                    <div class="section-title">Prescriptions</div>
                    <table>
                        <thead><tr><th>Date</th><th>Medication</th><th>Dosage</th><th>Instructions</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach($prescriptions as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['prescribed_date']); ?></td>
                                    <td><?php echo htmlspecialchars($p['medication_name']); ?></td>
                                    <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($p['instructions'])); ?></td>
                                    <td><?php echo htmlspecialchars($p['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($prescriptions)): ?><tr><td colspan="5" class="empty">No prescriptions</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="labs" class="card">
                    <div class="section-title">Lab Reports</div>
                    <table>
                        <thead><tr><th>Date</th><th>Type</th><th>Summary</th></tr></thead>
                        <tbody>
                            <?php foreach($labs as $l): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($l['report_date']); ?></td>
                                    <td><?php echo htmlspecialchars($l['report_type']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($l['summary'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($labs)): ?><tr><td colspan="3" class="empty">No lab reports</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php endif; ?>
    </div>
</body>
</html>
