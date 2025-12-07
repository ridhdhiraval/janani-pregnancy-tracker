<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
if (!$user) { header('Location: /JANANI/1signinsignup.php?mode=signin'); exit; }

$profileStmt = $pdo->prepare('SELECT first_name, last_name, phone, gender, dob FROM user_profiles WHERE user_id = ? LIMIT 1');
$profileStmt->execute([$user['id']]);
$profile = $profileStmt->fetch() ?: ['first_name'=>null,'last_name'=>null,'phone'=>null,'gender'=>null,'dob'=>null];

$mother = null; $preg = null; $partner = null; $partner_profile = null;
try {
    $tbl = $pdo->query("SHOW TABLES LIKE 'partner_links'");
    $has = $tbl && $tbl->rowCount() > 0;
} catch (Exception $e) { $has = false; }
if ($has) {
    if (($user['role'] ?? '') === 'partner') {
        $linkStmt = $pdo->prepare('SELECT mother_user_id FROM partner_links WHERE partner_user_id = ? LIMIT 1');
        $linkStmt->execute([$user['id']]);
        $linkRow = $linkStmt->fetch();
        if ($linkRow) {
            $mother = findUserById((int)$linkRow['mother_user_id']);
            if ($mother) {
                $mProfStmt = $pdo->prepare('SELECT first_name, last_name FROM user_profiles WHERE user_id = ? LIMIT 1');
                $mProfStmt->execute([$mother['id']]);
                $mother_profile = $mProfStmt->fetch() ?: ['first_name'=>null,'last_name'=>null];
                $pregStmt = $pdo->prepare('SELECT edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
                $pregStmt->execute([$mother['id']]);
                $preg = $pregStmt->fetch();
            }
        }
    } else if (($user['role'] ?? '') === 'mother') {
        $plinkStmt = $pdo->prepare('SELECT partner_user_id FROM partner_links WHERE mother_user_id = ? LIMIT 1');
        $plinkStmt->execute([$user['id']]);
        $plinkRow = $plinkStmt->fetch();
        if ($plinkRow) {
            $partner = findUserById((int)$plinkRow['partner_user_id']);
            if ($partner) {
                $pprofStmt = $pdo->prepare('SELECT first_name, last_name, phone FROM user_profiles WHERE user_id = ? LIMIT 1');
                $pprofStmt->execute([$partner['id']]);
                $partner_profile = $pprofStmt->fetch() ?: ['first_name'=>null,'last_name'=>null,'phone'=>null];
            }
        }
    }
}

$name = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
$initials = strtoupper(substr($profile['first_name'] ?? ($user['username'] ?? 'P'),0,1));
$role_display = (($user['role'] ?? '') === 'partner') ? 'father' : ($user['role'] ?? '');
$current_lang = $_GET['lang'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Partner Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{ --pink:#f8e5e8; --pink2:#ffc0cb; --pink3:#e07f91; --text:#374151; --muted:#6b7280; --bg:#f5f5f5; --white:#fff; }
        body{ margin:0; font-family:'Inter',sans-serif; background:var(--bg); }
        .layout{ display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
        .sidebar{ background:#1f2937; color:#cbd5e1; padding:16px; }
        .brand{ font-weight:800; color:#fff; margin-bottom:16px; }
        .nav a{ display:block; color:#cbd5e1; text-decoration:none; padding:10px 12px; border-radius:8px; margin-bottom:6px; }
        .nav a.active, .nav a:hover{ background:#374151; color:#fff; }
        .top{ background:var(--white); padding:14px 24px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }
        .title{ font-weight:800; color:var(--pink3); }
        .content{ padding:24px; }
        .profile-card{ display:grid; grid-template-columns:160px 1fr; gap:18px; background:var(--white); border:1px solid #eee; border-radius:12px; padding:18px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .avatar{ width:160px; height:160px; border-radius:50%; background:var(--pink2); display:flex; align-items:center; justify-content:center; font-size:64px; font-weight:800; color:#fff; overflow:hidden; }
        .avatar img{ width:100%; height:100%; object-fit:cover; display:none; }
        .grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        .field{ background:#fafafa; border:1px solid #eee; border-radius:8px; padding:10px 12px; }
        .label{ font-size:12px; color:var(--muted); }
        .value{ font-size:16px; color:var(--text); font-weight:600; }
        .section{ margin-top:18px; background:var(--white); border:1px solid #eee; border-radius:12px; padding:18px; }
        .btn{ display:inline-block; padding:8px 12px; border-radius:8px; background:var(--pink2); color:#111; text-decoration:none; font-weight:700; }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">Partner</div>
        <nav class="nav">
            <a href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Dashboard</a>
            <a href="partner-info.php?lang=<?php echo htmlspecialchars($current_lang); ?>">This Week</a>
            <a href="partner_checklist.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Checklist</a>
            <a href="partner_hospital_bag.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Hospital Bag</a>
            <a href="partner_doctor_details.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Doctor Details</a>
            <a href="partner-profile.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">My Profile</a>
            <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Emergency</a>
            <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
        </nav>
    </aside>
    <div>
        <div class="top"><div class="title">My Profile</div></div>
        <div class="content">
            <div class="profile-card">
                <div class="avatar"><span><?php echo htmlspecialchars($initials); ?></span><img id="avatarImg" alt="avatar"></div>
                <div class="grid">
                    <div class="field"><div class="label">Name</div><div class="value"><?php echo htmlspecialchars($name ?: $user['username']); ?></div></div>
                    <div class="field"><div class="label">Email</div><div class="value"><?php echo htmlspecialchars($user['email']); ?></div></div>
                    <div class="field"><div class="label">Phone</div><div class="value"><?php echo htmlspecialchars($profile['phone'] ?: '—'); ?></div></div>
                    <div class="field"><div class="label">Gender</div><div class="value"><?php echo htmlspecialchars($profile['gender'] ?: '—'); ?></div></div>
                    <div class="field"><div class="label">Date of birth</div><div class="value"><?php echo htmlspecialchars($profile['dob'] ?: '—'); ?></div></div>
                    <div class="field"><div class="label">Role</div><div class="value"><?php echo htmlspecialchars($role_display); ?></div></div>
                </div>
            </div>

            <?php if ($mother): ?>
            <div class="section">
                <div style="font-weight:800; color:var(--text); margin-bottom:8px;">Linked Mother</div>
                <div class="grid">
                    <div class="field"><div class="label">Name</div><div class="value"><?php echo htmlspecialchars(trim(($mother_profile['first_name'] ?? '') . ' ' . ($mother_profile['last_name'] ?? '')) ?: $mother['username']); ?></div></div>
                    <div class="field"><div class="label">Email</div><div class="value"><?php echo htmlspecialchars($mother['email']); ?></div></div>
                    <div class="field"><div class="label">EDD</div><div class="value"><?php echo htmlspecialchars($preg['edd'] ?? '—'); ?></div></div>
                </div>
                <a class="btn" href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Go to Dashboard</a>
            </div>
            <?php endif; ?>

            <?php if ($partner): ?>
            <div class="section">
                <div style="font-weight:800; color:var(--text); margin-bottom:8px;">Linked Partner</div>
                <div class="grid">
                    <div class="field"><div class="label">Name</div><div class="value"><?php echo htmlspecialchars(trim(($partner_profile['first_name'] ?? '') . ' ' . ($partner_profile['last_name'] ?? '')) ?: $partner['username']); ?></div></div>
                    <div class="field"><div class="label">Email</div><div class="value"><?php echo htmlspecialchars($partner['email']); ?></div></div>
                    <div class="field"><div class="label">Role</div><div class="value">father</div></div>
                    <div class="field"><div class="label">Phone</div><div class="value"><?php echo htmlspecialchars($partner_profile['phone'] ?? '—'); ?></div></div>
                </div>
                <a class="btn" href="partner-panel.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Open Partner Dashboard</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// placeholder for future image load if profile image exists; keeps layout safe
const imgEl = document.getElementById('avatarImg');
if (imgEl && imgEl.src && imgEl.src.length > 0) { imgEl.style.display='block'; }
</script>
</body>
</html>
