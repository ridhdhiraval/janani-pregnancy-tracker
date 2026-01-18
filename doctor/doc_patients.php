<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../config/db.php';

// Fetch all users from the database
$users = [];
try {
    $stmt = $pdo->query("SELECT id, username, email, role, status, created_at 
                         FROM users 
                         ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}

$users_json = json_encode($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Doctor Patients</title>
<style>
:root {
    --bg:#fef9fb; --card:#fff; --accent:#f7a8b8; --accent-dark:#d77890;
    --muted:#8b646f; --border:#f3e5e8; --soft-shadow:0 12px 24px rgba(0,0,0,0.08);
    --hover-shadow:0 16px 32px rgba(0,0,0,0.15); --radius-lg:16px; --radius-md:10px;
    font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color:#333;
}
html, body{height:100%;margin:0;background:var(--bg);}
.dashboard-container{max-width:1200px;margin:0 auto;padding:0 32px;}
.header{display:flex;align-items:center;justify-content:space-between;padding:24px 0;border-bottom:1px solid var(--border);margin-bottom:20px;}
.title-group{display:flex;align-items:center;gap:18px;}
.back{font-size:24px;padding:8px;border-radius:var(--radius-md);color:var(--muted);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:0.2s;}
.back:hover{background-color:#fcebeb;}
.page-title{font-size:32px;font-weight:800;color:var(--accent-dark);}
.main-content{display:flex;flex-direction:column;gap:20px;}
.stats-bar{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:30px;}
.stat-card{background:var(--card);padding:20px;border-radius:var(--radius-lg);box-shadow:0 6px 15px rgba(0,0,0,0.05);border-left:5px solid;}
.stat-card h4{margin:0 0 4px 0;font-size:14px;font-weight:600;color:var(--muted);letter-spacing:0.5px;}
.stat-card .value{font-size:28px;font-weight:900;color:#222;}
.stat-card.status_active{border-left-color:#0f8a4a;}
.stat-card.status_inactive{border-left-color:#c33a53;}
.search-bar{display:flex;gap:15px;margin-bottom:20px;}
#searchInput{flex-grow:1;padding:12px 20px;border:1px solid var(--border);border-radius:var(--radius-md);font-size:16px;outline:none;transition:0.2s;}
#searchInput:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(247,168,184,0.3);}
.filter-tabs{display:flex;gap:10px;margin-bottom:20px;overflow-x:auto;padding-bottom:5px;}
.filter-tab{padding:10px 18px;border-radius:20px;background:#ffffffa1;cursor:pointer;white-space:nowrap;border:1px solid #d8b5e6;}
.filter-tab.active{background:#d088e1;color:white;border:1px solid #be74cc;}
.grid{display:flex;flex-direction:column;gap:16px;padding-bottom:40px;overflow-y:auto;max-height:calc(100vh - 300px);}
.grid::-webkit-scrollbar{display:none;}
.card{background:var(--card);padding:20px 24px;border-radius:var(--radius-lg);border:1px solid var(--border);box-shadow:var(--soft-shadow);transition:transform 0.2s ease, box-shadow 0.2s ease, opacity 0.3s;cursor:pointer;display:grid;grid-template-columns:2fr 2fr 2fr 1fr;align-items:center;gap:20px;}
.card:hover{transform:translateY(-5px);box-shadow:var(--hover-shadow);border-color:rgba(247,168,184,0.4);}
.card-info h3{margin:0;font-size:18px;font-weight:700;color:#222;}
.card-info p{margin:4px 0 0 0;color:var(--muted);font-size:14px;}
.details, .email, .role, .status-badge{text-align:left;}
.status-badge{padding:8px 14px;border-radius:20px;font-weight:700;font-size:13px;text-align:center;white-space:nowrap;}
.status_active{background:#eafcf0;color:#0f8a4a;border:1px solid #c7ecd2;}
.status_inactive{background:#ffecf0;color:#c33a53;border:1px solid #ffc9d2;}
.hidden{display:none !important;opacity:0;}
</style>
</head>
<body>

<div class="dashboard-container">
    <header class="header">
        <div class="title-group">
            <a href="doc_dashboard.php" class="back">&#8592;</a>
            <div class="page-title">Patient Care Overview</div>
        </div>
        <div style="color: var(--muted); font-size:16px;">
            Today: <span id="currentDate"></span>
        </div>
    </header>

    <div class="main-content">
        <section class="stats-bar" id="statsBar"></section>

        <!-- Filter Tabs -->
        <div class="filter-tabs" id="filterTabs">
            <div class="filter-tab active" data-filter="all" onclick="changeFilter(this)">All</div>
            <div class="filter-tab" data-filter="active" onclick="changeFilter(this)">Active</div>
            <div class="filter-tab" data-filter="inactive" onclick="changeFilter(this)">Inactive</div>
        </div>

        <section class="search-bar">
            <input type="text" id="searchInput" placeholder="Search users by name, email or ID..." onkeyup="filterUsers()">
        </section>

        <main class="grid" id="grid"></main>
    </div>
</div>

<script>
const USERS_DATA = <?php echo $users_json; ?>;
let currentFilter = 'all';
const grid = document.getElementById('grid');
const statsBar = document.getElementById('statsBar');
const currentDateElement = document.getElementById('currentDate');

document.addEventListener('DOMContentLoaded', () => {
    currentDateElement.textContent = new Date().toLocaleDateString('en-IN', {weekday:'long', year:'numeric', month:'short', day:'numeric'});
    renderUsers(USERS_DATA);
    renderStats(USERS_DATA);
});

function renderStats(users){
    const total = users.length;
    const active = users.filter(u=>u.status==='active').length;
    const inactive = users.filter(u=>u.status==='inactive').length;
    statsBar.innerHTML=`
        <div class="stat-card status_active"><h4>ACTIVE USERS</h4><div class="value">${active}</div></div>
        <div class="stat-card status_inactive"><h4>INACTIVE USERS</h4><div class="value">${inactive}</div></div>
        <div class="stat-card total"><h4>TOTAL USERS</h4><div class="value">${total}</div></div>`;
}

function renderUsers(users){
    grid.innerHTML='';
    users.filter(u=>currentFilter==='all'||u.status===currentFilter).forEach(u=>{
        const card=document.createElement('div');
        card.className='card';
        card.setAttribute('data-name', u.username.toLowerCase());
        card.setAttribute('data-email', u.email.toLowerCase());
        card.setAttribute('data-id', u.id);
        card.innerHTML=`
            <div class="card-info"><h3>${u.username}</h3><p>ID: ${u.id}</p></div>
            <div class="email"><p>${u.email}</p></div>
            <div class="role"><p>${u.role}</p></div>
            <div class="status-badge status_${u.status}">${u.status}</div>
        `;
        card.onclick=()=>window.location.href=`doc_patient_details.php?id=${u.id}`;
        grid.appendChild(card);
    });
}

function filterUsers(){
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = grid.querySelectorAll('.card');
    cards.forEach(card=>{
        const name = card.getAttribute('data-name');
        const email = card.getAttribute('data-email');
        const id = card.getAttribute('data-id');
        const matches = name.includes(searchTerm) || email.includes(searchTerm) || id.toString().includes(searchTerm);
        card.style.opacity = matches ? '1' : '0';
        setTimeout(()=>{ card.style.display = matches ? 'grid' : 'none'; }, 300);
    });
}

function changeFilter(tab){
    document.querySelectorAll('.filter-tab').forEach(t=>t.classList.remove('active'));
    tab.classList.add('active');
    currentFilter = tab.getAttribute('data-filter');
    renderUsers(USERS_DATA);
}
</script>

</body>
</html>
