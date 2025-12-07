<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$current_lang = $_GET['lang'] ?? 'en';

$checklist_data = [
    ['id' => 1, 'category' => 'First Trimester', 'task' => 'Schedule first prenatal visit', 'completed' => 0],
    ['id' => 2, 'category' => 'First Trimester', 'task' => 'Start taking prenatal vitamins', 'completed' => 0],
    ['id' => 3, 'category' => 'First Trimester', 'task' => 'Quit smoking and avoid alcohol', 'completed' => 0],
    ['id' => 4, 'category' => 'Second Trimester', 'task' => 'Schedule second trimester screening', 'completed' => 0],
    ['id' => 5, 'category' => 'Second Trimester', 'task' => 'Start thinking about baby names', 'completed' => 0],
    ['id' => 6, 'category' => 'Third Trimester', 'task' => 'Attend childbirth classes', 'completed' => 0],
    ['id' => 7, 'category' => 'Third Trimester', 'task' => 'Pack hospital bag', 'completed' => 0],
];

$categories = array_values(array_unique(array_map(fn($t) => $t['category'], $checklist_data)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Partner • Checklist</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{ --bg:#f5f5f5; --sidebar:#1f2937; --text:#374151; --white:#fff; --muted:#6b7280; --accent:#e07f91; --chip:#e5e7eb; --border:#e5e7eb; --accent2:#ffc0cb; }
        body{ margin:0; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); }
        .layout{ display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
        aside{ background:var(--sidebar); color:#cbd5e1; padding:16px; }
        .brand{ font-weight:800; color:#fff; margin-bottom:16px; }
        .nav a{ display:block; color:#cbd5e1; text-decoration:none; padding:10px 12px; border-radius:8px; margin-bottom:6px; }
        .nav a.active, .nav a:hover{ background:#374151; color:#fff; }
        .top{ background:var(--white); padding:14px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
        .title{ font-weight:800; color:var(--accent); }
        .content{ background:var(--white); }
        .wrap{ padding:24px; }
        .filters{ display:flex; gap:8px; margin-bottom:12px; }
        .tab{ padding:8px 12px; border:1px solid var(--border); border-radius:999px; background:#fafafa; cursor:pointer; }
        .tab.active{ background:var(--accent2); color:#111; border-color:var(--accent2); }
        .grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:14px; }
        .card{ border:1px solid var(--border); border-radius:12px; background:var(--white); box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .card h3{ margin:0; padding:12px 14px; border-bottom:1px solid var(--border); font-size:15px; }
        .card .body{ padding:12px 14px; }
        .item{ display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px dashed #eee; }
        .item:last-child{ border-bottom:none; }
        .check{ width:18px; height:18px; }
        .muted{ color:var(--muted); font-size:12px; }
        @media (max-width: 900px){ .layout{ grid-template-columns:1fr; } aside{ display:none; } .grid{ grid-template-columns:1fr; } .wrap{ padding:16px; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside>
            <div class="brand">Partner</div>
            <nav class="nav">
                <a href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Dashboard</a>
                <a href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">This Week</a>
                <a href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">Checklist</a>
                <a href="partner_hospital_bag.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Hospital Bag</a>
                <a href="partner_doctor_details.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Doctor Details</a>
                <a href="partner-profile.php?lang=<?php echo htmlspecialchars($current_lang); ?>">My Profile</a>
                <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Emergency</a>
                <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
            </nav>
        </aside>
        <div class="content">
            <div class="top"><div class="title">Checklist</div></div>
            <div class="wrap">
                <div class="filters" id="filterTabs">
                    <div class="tab active" data-filter="all">All</div>
                    <?php foreach($categories as $cat): ?>
                        <div class="tab" data-filter="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></div>
                    <?php endforeach; ?>
                </div>
                <div class="grid" id="grid">
                    <?php foreach($categories as $cat): ?>
                        <div class="card" data-category="<?php echo htmlspecialchars($cat); ?>">
                            <h3><?php echo htmlspecialchars($cat); ?></h3>
                            <div class="body">
                                <?php foreach($checklist_data as $t): if ($t['category'] !== $cat) continue; ?>
                                    <label class="item">
                                        <input class="check" type="checkbox" <?php echo $t['completed'] ? 'checked' : ''; ?> />
                                        <span><?php echo htmlspecialchars($t['task']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                                <div class="muted">Tap to mark complete</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    const tabs = document.querySelectorAll('.tab');
    const cards = document.querySelectorAll('.card');
    tabs.forEach(t => t.addEventListener('click', () => {
        tabs.forEach(x => x.classList.remove('active'));
        t.classList.add('active');
        const f = t.getAttribute('data-filter');
        cards.forEach(c => {
            const cat = c.getAttribute('data-category');
            c.style.display = (f === 'all' || f === cat) ? 'block' : 'none';
        });
    }));
    </script>
</body>
</html>

