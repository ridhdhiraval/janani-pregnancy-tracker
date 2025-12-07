<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$current_lang = $_GET['lang'] ?? 'en';

$saved = false;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['contact_name'] ?? '');
    $phone = trim($_POST['contact_phone'] ?? '');
    if ($name === '' || $phone === '') {
        $errors[] = 'Name and phone required';
    } else {
        try {
            $msg = 'Primary contact: ' . $name . ' (' . $phone . ')';
            $stmt = $pdo->prepare('INSERT INTO emergency_alerts (user_id, latitude, longitude, location_link, alert_time, status, message) VALUES (?, NULL, NULL, NULL, NOW(), "saved", ?)');
            $stmt->execute([$user ? $user['id'] : null, $msg]);
            $saved = true;
        } catch (Exception $e) {
            $errors[] = 'Save failed';
        }
    }
}

$rows = [];
try {
    $q = $pdo->prepare('SELECT id, alert_time, status, message FROM emergency_alerts WHERE user_id ' . ($user ? '= ?' : 'IS NULL') . ' ORDER BY alert_time DESC LIMIT 50');
    if ($user) { $q->execute([$user['id']]); } else { $q->execute(); }
    $rows = $q->fetchAll();
} catch (Exception $e) { $rows = []; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Emergency</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{ --pink:#f8e5e8; --pink2:#ffc0cb; --pink3:#e07f91; --text:#374151; --bg:#f5f5f5; --white:#fff; }
        body{ margin:0; font-family:'Inter',sans-serif; background:var(--bg); }
        .layout{ display:grid; grid-template-columns:260px 1fr; min-height:100vh; }
        .sidebar{ background:#1f2937; color:#cbd5e1; padding:16px; }
        .brand{ font-weight:800; color:#fff; margin-bottom:16px; }
        .nav a{ display:block; color:#cbd5e1; text-decoration:none; padding:10px 12px; border-radius:8px; margin-bottom:6px; }
        .nav a.active, .nav a:hover{ background:#374151; color:#fff; }
        .top{ background:var(--white); padding:14px 24px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }
        .title{ font-weight:800; color:var(--pink3); }
        .content{ padding:24px; }
        .card{ background:var(--white); border:1px solid #eee; border-radius:12px; padding:18px; box-shadow:0 4px 10px rgba(0,0,0,.04); }
        .grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
        .btn{ display:inline-block; padding:10px 14px; border-radius:8px; background:var(--pink2); color:#111; text-decoration:none; font-weight:700; }
        .input{ width:100%; padding:.6rem 1rem; border:1px solid #ddd; border-radius:8px; }
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
            
            <a href="alert.php?lang=<?php echo htmlspecialchars($current_lang); ?>" class="active">Emergency</a>
            <a href="5index.php?lang=<?php echo htmlspecialchars($current_lang); ?>">Back to Home</a>
        </nav>
    </aside>
    <div>
        <div class="top"><div class="title">Emergency</div></div>
        <div class="content">
            <div class="grid">
                <div class="card">
                    <div style="font-weight:800; color:#111; margin-bottom:8px;">Primary Contact</div>
                    <form method="POST">
                        <input class="input" type="text" name="contact_name" placeholder="Name" value="<?php echo isset($_POST['contact_name'])?htmlspecialchars($_POST['contact_name']):''; ?>">
                        <input class="input" type="tel" name="contact_phone" placeholder="Phone" style="margin-top:8px;" value="<?php echo isset($_POST['contact_phone'])?htmlspecialchars($_POST['contact_phone']):''; ?>">
                        <button class="btn" type="submit" style="margin-top:10px;">Save</button>
                    </form>
                    <?php if ($saved): ?><div style="margin-top:8px; color:green; font-weight:600;">Saved</div><?php endif; ?>
                    <?php if ($errors): ?><div style="margin-top:8px; color:#b91c1c; font-weight:600;">Error: <?php echo htmlspecialchars(implode(', ',$errors)); ?></div><?php endif; ?>
                </div>
                <div class="card">
                    <div style="font-weight:800; color:#111; margin-bottom:8px;">Quick Action</div>
                    <a class="btn" id="sendAlertBtn" href="#">Send Alert</a>
                </div>
            </div>
            <div class="card" style="margin-top:12px;">
                <div style="font-weight:800; color:#111; margin-bottom:8px;">Saved Alerts</div>
                <table style="width:100%; border-collapse:collapse;">
                    <thead><tr><th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Time</th><th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Status</th><th style="text-align:left; padding:8px; border-bottom:1px solid #eee;">Message</th></tr></thead>
                    <tbody>
                        <?php foreach($rows as $r): ?>
                            <tr>
                                <td style="padding:8px; border-bottom:1px solid #f5f5f5;"><?php echo htmlspecialchars($r['alert_time']); ?></td>
                                <td style="padding:8px; border-bottom:1px solid #f5f5f5;"><?php echo htmlspecialchars($r['status']); ?></td>
                                <td style="padding:8px; border-bottom:1px solid #f5f5f5;"><?php echo htmlspecialchars($r['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="3" style="padding:8px; color:#6b7280;">No saved alerts yet</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('sendAlertBtn');
  if (!btn) return;
  function showToast(msg, timeout = 4000) {
    const t = document.createElement('div');
    t.innerHTML = msg;
    Object.assign(t.style, {position:'fixed',right:'20px',bottom:'20px',background:'#fff',color:'#111',padding:'10px 14px',borderRadius:'8px',boxShadow:'0 6px 18px rgba(0,0,0,0.12)',zIndex:999999,fontFamily:'Inter, sans-serif'});
    document.body.appendChild(t);
    setTimeout(() => t.remove(), timeout);
  }

  btn.addEventListener('click', (ev) => {
    ev.preventDefault();
    if (!confirm('Send EMERGENCY alert now?')) return;
    if (!navigator.geolocation) { alert('Geolocation not supported.'); return; }
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    navigator.geolocation.getCurrentPosition((position) => {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      const payload = {
        source: 'janani_web',
        date: date,
        time: time,
        latitude: lat,
        longitude: lon,
        user_id: <?php echo ($user ? (int)$user['id'] : 'null'); ?>,
        user_name: <?php echo ($user ? json_encode($user['name'] ?? '') : '""'); ?>
      };
      fetch('send_alert.php', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)})
        .then(r => r.json())
        .then(res => {
          if (res && res.status === 'success') {
            showToast('Alert sent ✅');
            alert('Alert sent successfully.');
            location.reload();
          } else {
            showToast('Failed to send alert — check console', 6000);
            alert('Failed to send alert: ' + (res.message || JSON.stringify(res)));
          }
        })
        .catch(err => { alert('Network error: ' + (err.message || err)); });
    }, () => { alert('Unable to access location. Enable GPS and try again.'); }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 });
  });
});
</script>
</body>
</html>
