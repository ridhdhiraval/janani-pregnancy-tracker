<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$current_lang = $_GET['lang'] ?? 'en';

$hospital_bag = [
    'title' => 'Hospital Bag Checklist',
    'items' => [
        'For Mum' => [
            'Comfortable clothes and toiletries',
            'Maternity pads and large underwear',
            'Nursing bras/pads (if breastfeeding)',
            'Photo ID, insurance papers, birth plan'
        ],
        'For Baby' => [
            'Newborn diapers and wipes',
            'Two or three outfits (onesies)',
            'Going-home outfit and blanket',
            'Car seat (installed and checked)'
        ],
        'For Partner' => [
            'Snacks, water bottle, and change of clothes',
            'Phone charger and camera/video recorder',
            'Pillow and light blanket'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Partner • Hospital Bag</title>
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
        .grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
        .kit{ border:1px solid var(--border); border-radius:12px; background:var(--white); box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .kit h3{ margin:0; padding:12px 14px; border-bottom:1px solid var(--border); font-size:15px; }
        .kit ul{ list-style:none; margin:0; padding:12px 14px; }
        .kit li{ padding:8px 0; border-bottom:1px dashed #eee; }
        .kit li:last-child{ border-bottom:none; }
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
                <a href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Checklist</a>
                <a href="partner_hospital_bag.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">Hospital Bag</a>
                <a href="partner_doctor_details.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Doctor Details</a>
                <a href="partner-profile.php?lang=<?php echo htmlspecialchars($current_lang); ?>">My Profile</a>
                <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Emergency</a>
                <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
            </nav>
        </aside>
        <div class="content">
            <div class="top"><div class="title">Hospital Bag</div></div>
            <div class="wrap">
                <div class="grid">
                    <?php foreach($hospital_bag['items'] as $group => $list): ?>
                        <div class="kit">
                            <h3><?php echo htmlspecialchars($group); ?></h3>
                            <ul>
                                <?php foreach($list as $item): ?>
                                    <li><?php echo htmlspecialchars($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

