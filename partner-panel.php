<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$target_user_id = $user ? (int)$user['id'] : null;
if ($user && ($user['role'] ?? '') === 'partner') {
    $q = $pdo->prepare('SELECT mother_user_id FROM partner_links WHERE partner_user_id = ? LIMIT 1');
    $q->execute([$user['id']]);
    $link = $q->fetch();
    if ($link && !empty($link['mother_user_id'])) { $target_user_id = (int)$link['mother_user_id']; }
}
$appData = ['week_number'=>0,'days_to_go'=>280,'percent_done'=>0,'checklist_percent'=>0,'progress_width'=>0];
if ($target_user_id) {
    $stmt = $pdo->prepare('SELECT id, edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$target_user_id]);
    $p = $stmt->fetch();
    if ($p && !empty($p['edd'])) {
        $edd = $p['edd'];
        $today = new DateTimeImmutable('today');
        $dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $edd);
        if ($dueDate) {
            $interval = $today->diff($dueDate);
            $days_to_go = (int)$interval->format('%r%a');
            $total_days = 280;
            $days_done = max(0, $total_days - max(0, $days_to_go));
            $percent_done = (int)round(($days_done / $total_days) * 100);
            $weeks_done = floor($days_done / 7);
            $appData['week_number'] = max(0, $weeks_done);
            $appData['days_to_go'] = $days_to_go;
            $appData['percent_done'] = $percent_done;
            $appData['progress_width'] = max(0, min(100, $percent_done));
            $current_trimester = ($weeks_done <= 12) ? 1 : (($weeks_done <= 27) ? 2 : 3);
            try {
                $tableCheck = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
                $hasChecklistTable = $tableCheck && $tableCheck->rowCount() > 0;
                if ($hasChecklistTable) {
                    $stmt_checklist = $pdo->prepare('SELECT COUNT(*) as total, SUM(is_completed) as completed FROM checklist_items WHERE user_id = ? AND trimester <= ?');
                    $stmt_checklist->execute([$target_user_id, $current_trimester]);
                    $checklist_stats = $stmt_checklist->fetch();
                    if ($checklist_stats && $checklist_stats['total'] > 0) {
                        $appData['checklist_percent'] = (int)round((($checklist_stats['completed'] ?? 0) / $checklist_stats['total']) * 100);
                    } else { $appData['checklist_percent'] = 0; }
                } else { $appData['checklist_percent'] = 0; }
            } catch (Exception $e) { $appData['checklist_percent'] = 0; }
        }
    }
}
$current_lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$week_actions = [
    ['id'=>1,'title'=>'Meals plan and hydration','tag'=>'support','star'=>true,'created'=>date('Y-m-d'),'updated'=>date('Y-m-d')],
    ['id'=>2,'title'=>'Doctor questions list','tag'=>'prep','star'=>false,'created'=>date('Y-m-d'),'updated'=>date('Y-m-d')],
    ['id'=>3,'title'=>'Hospital bag checklist','tag'=>'bag','star'=>true,'created'=>date('Y-m-d'),'updated'=>date('Y-m-d')],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Partner Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary-pink:#f8e5e8; --secondary-pink:#ffc0cb; --dark-pink:#e07f91; --text:#5d5d5d; --white:#fff; --bg:#f5f5f5; }
        body{ font-family:'Inter',sans-serif; margin:0; background:var(--bg); }
        .layout{ display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
        .sidebar{ background:#1f2937; color:#cbd5e1; padding:16px; }
        .brand{ font-weight:800; color:#fff; margin-bottom:16px; }
        .nav a{ display:block; color:#cbd5e1; text-decoration:none; padding:10px 12px; border-radius:8px; margin-bottom:6px; }
        .nav a.active, .nav a:hover{ background:#374151; color:#fff; }
        .top{ background:var(--white); padding:14px 24px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }
        .title{ font-weight:800; color:var(--dark-pink); }
        .content{ background:var(--white); }
        .summary{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; padding:20px 24px; }
        .summary .card{ background:var(--primary-pink); border:1px solid var(--secondary-pink); border-radius:12px; padding:14px; color:var(--dark-pink); }
        .summary .value{ font-size:28px; font-weight:800; }
        .main{ display:grid; grid-template-columns:1.6fr 1fr; gap:18px; padding:0 24px 24px; }
        .panel{ background:var(--white); border:1px solid #eee; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .panel h3{ margin:0; padding:14px 16px; border-bottom:1px solid #eee; color:#374151; font-size:16px; }
        .panel .body{ padding:16px; }
        table{ width:100%; border-collapse:collapse; }
        th, td{ text-align:left; padding:10px; border-bottom:1px solid #f0f0f0; color:#374151; font-size:14px; }
        .tag{ display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; background:#e5e7eb; color:#111; }
        .star{ color:#f59e0b; }
        .actions a{ margin-right:8px; color:#6b7280; text-decoration:none; }
        .gridcards{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        .btn{ display:inline-block; padding:8px 12px; border-radius:8px; background:var(--secondary-pink); color:var(--dark-pink); text-decoration:none; font-weight:700; }
        .progress{ height:6px; background:#f0f0f0; border-radius:3px; overflow:hidden; }
        .progress span{ display:block; height:100%; background:var(--dark-pink); width:0%; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded',()=>{ const bar=document.getElementById('checklistBar'); if(bar) bar.style.width='<?php echo (int)$appData['checklist_percent']; ?>%'; });
    </script>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">Partner</div>
            <nav class="nav">
                <a href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">Dashboard</a>
                <a href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">This Week</a>
                <a href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Checklist</a>
                <a href="partner_hospital_bag.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Hospital Bag</a>
                <a href="partner_doctor_details.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Doctor Details</a>
                <a href="partner-profile.php?lang=<?php echo htmlspecialchars($current_lang); ?>">My Profile</a>
                <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Emergency</a>
                <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
            </nav>
        </aside>
        <div class="content">
            <div class="top">
                <div class="title">Dashboard</div>
                <div style="color:#6b7280;">Week <?php echo $appData['week_number']; ?></div>
            </div>
            <div class="summary">
                <div class="card"><div>Week</div><div class="value"><?php echo $appData['week_number']; ?></div></div>
                <div class="card"><div>Days to go</div><div class="value"><?php echo $appData['days_to_go']; ?></div></div>
                <div class="card"><div>Progress</div><div class="value"><?php echo $appData['percent_done']; ?>%</div></div>
                <div class="card"><div>Checklist</div><div class="value"><?php echo $appData['checklist_percent']; ?>%</div></div>
            </div>
            <div class="main">
                <div class="panel">
                    <h3>Actions this week</h3>
                    <div class="body">
                        <table>
                            <thead><tr><th>ID</th><th>Title</th><th>Tags</th><th>Star</th><th>Created</th><th>Updated</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach($week_actions as $row): ?>
                                    <tr>
                                        <td><?php echo (int)$row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><span class="tag"><?php echo htmlspecialchars($row['tag']); ?></span></td>
                                        <td><?php echo $row['star'] ? '★' : '☆'; ?></td>
                                        <td><?php echo htmlspecialchars($row['created']); ?></td>
                                        <td><?php echo htmlspecialchars($row['updated']); ?></td>
                                        <td class="actions">
                                            <a href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">view</a>
                                            <a href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>">open</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel">
                    <h3>Quick links</h3>
                    <div class="body">
                        <div class="gridcards">
                            <div>
                                <div style="margin-bottom:8px; color:#374151; font-weight:700;">This Week</div>
                                <a class="btn" href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Open</a>
                            </div>
                            <div>
                                <div style="margin-bottom:8px; color:#374151; font-weight:700;">Checklist</div>
                                <div class="progress"><span id="checklistBar"></span></div>
                                <a class="btn" href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>" style="margin-top:8px;">View</a>
                            </div>
                            <div>
                                <div style="margin-bottom:8px; color:#374151; font-weight:700;">Hospital Bag</div>
                                <a class="btn" href="partner_hospital_bag.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Planner</a>
                            </div>
                            <div>
                                <div style="margin-bottom:8px; color:#374151; font-weight:700;">Emergency</div>
                                <a class="btn" href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Alert</a>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
