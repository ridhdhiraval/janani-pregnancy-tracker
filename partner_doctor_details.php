<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$current_lang = $_GET['lang'] ?? 'en';
$target_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$target_id && $user) {
    $target_id = (int)$user['id'];
    if (($user['role'] ?? '') === 'partner') {
        try { $q = $pdo->prepare('SELECT mother_user_id FROM partner_links WHERE partner_user_id = ? LIMIT 1'); $q->execute([$user['id']]); $link = $q->fetch(); if ($link && !empty($link['mother_user_id'])) { $target_id = (int)$link['mother_user_id']; } } catch (Exception $e) {}
    }
}

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
    <title>Partner • Doctor Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{ --bg:#f5f5f5; --sidebar:#1f2937; --text:#374151; --white:#fff; --muted:#6b7280; --accent:#e07f91; --chip:#e5e7eb; --border:#e5e7eb; }
        body{ margin:0; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); }
        .layout{ display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
        aside{ background:var(--sidebar); color:#cbd5e1; padding:16px; }
        .brand{ font-weight:800; color:#fff; margin-bottom:16px; }
        .nav a{ display:block; color:#cbd5e1; text-decoration:none; padding:10px 12px; border-radius:8px; margin-bottom:6px; }
        .nav a.active, .nav a:hover{ background:#374151; color:#fff; }
        .top{ background:var(--white); padding:14px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .title{ font-weight:800; color:var(--accent); }
        .content{ background:var(--white); }
        .wrap{ padding:24px; display:grid; grid-template-columns:1.35fr 1fr; gap:18px; }
        .panel{ background:var(--white); border:1px solid var(--border); border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .panel h3{ margin:0; padding:14px 16px; border-bottom:1px solid var(--border); font-size:16px; color:#111; }
        .panel .body{ padding:16px; }
        table{ width:100%; border-collapse:collapse; }
        th, td{ text-align:left; padding:10px; border-bottom:1px solid var(--border); font-size:14px; }
        thead th{ background:#fafafa; }
        .empty{ color:var(--muted); font-style:italic; padding:8px 0; }
        .chip{ display:inline-block; background:var(--chip); color:#111; padding:4px 8px; border-radius:999px; font-size:12px; }
        @media (max-width: 900px){ .layout{ grid-template-columns:1fr; } aside{ display:none; } .wrap{ grid-template-columns:1fr; padding:16px; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <div class="brand">Partner</div>
            <nav class="nav">
                <a href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Dashboard</a>
                <a href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">This Week</a>
                <a href="checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Checklist</a>
                <a href="bonding.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Hospital Bag</a>
                <a href="partner_doctor_details.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">Doctor Details</a>
                <a href="partner-profile.php?lang=<?php echo htmlspecialchars($current_lang); ?>">My Profile</a>
                <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Emergency</a>
                <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
            </nav>
        </aside>
        <div class="content">
            <div class="top">
                <div class="title">Doctor Details</div>
                <div style="color:#6b7280;">User ID <?php echo (int)$target_id; ?></div>
            </div>
            <div class="wrap">
                <div class="panel">
                    <h3>Doctors</h3>
                    <div class="body">
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
                                        <td><span class="chip"><?php echo htmlspecialchars($d['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($doctors)): ?><tr><td colspan="7" class="empty">No doctors found</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel">
                    <h3>Patient</h3>
                    <div class="body">
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
                </div>
                <div class="panel">
                    <h3>Appointments</h3>
                    <div class="body">
                        <table>
                            <thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach($appointments as $a): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($a['appt_date']); ?></td>
                                        <td><?php echo htmlspecialchars($a['appt_time']); ?></td>
                                        <td><?php echo htmlspecialchars($a['appt_type']); ?></td>
                                        <td><span class="chip"><?php echo htmlspecialchars($a['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($appointments)): ?><tr><td colspan="4" class="empty">No appointments</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel">
                    <h3>Clinical Notes</h3>
                    <div class="body">
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
                </div>
                <div class="panel">
                    <h3>Prescriptions</h3>
                    <div class="body">
                        <table>
                            <thead><tr><th>Date</th><th>Medication</th><th>Dosage</th><th>Instructions</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach($prescriptions as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['prescribed_date']); ?></td>
                                        <td><?php echo htmlspecialchars($p['medication_name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['dosage']); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($p['instructions'])); ?></td>
                                        <td><span class="chip"><?php echo htmlspecialchars($p['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($prescriptions)): ?><tr><td colspan="5" class="empty">No prescriptions</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel">
                    <h3>Lab Reports</h3>
                    <div class="body">
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
                </div>
            </div>
        </div>
    </div>
</body>
</html>

