<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../config/db.php';

// Fetch 2-week dates
$today = new DateTime();
$weekday = (int)$today->format('N'); // 1-7
$monday = (clone $today)->modify('-' . ($weekday - 1) . ' days');

$week = [];
for ($i = 0; $i < 14; $i++) {
    $d = (clone $monday)->modify("+$i days");
    $week[] = ['iso'=>$d->format('Y-m-d'),'label'=>$d->format('D'),'num'=>$d->format('d')];
}

// Fetch appointments from DB
$stmt = $pdo->query("
    SELECT a.id, a.patient_id, u.username AS name, a.appt_date, a.appt_time, a.appt_type, a.status
    FROM appointments a
    JOIN users u ON a.patient_id=u.id
");
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$appointments_json = json_encode($appointments);
$week_json = json_encode($week);
$today_iso = $today->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Doctor — Appointments</title>
<!-- YOUR CSS -->
<style>
/* CSS same as your previous message */
:root{--bg:#fff7fa;--card:#ffffff;--accent:#d77890;--accent-dark:#b25a70;--muted:#8b646f;--border:#f3dce3;--soft-shadow:0 8px 28px rgba(0,0,0,0.06);font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
html,body{height:100%;margin:0;background:var(--bg);color:var(--accent-dark);}
a{color:inherit;text-decoration:none;}
.header{width:100%;display:flex;align-items:center;gap:18px;padding:20px 40px;background:linear-gradient(120deg,#ffeaf0,#fff3f6);border-bottom:1px solid var(--border);box-sizing:border-box;}
.back{font-size:22px;padding:8px;border-radius:10px;color:var(--accent-dark);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}
.page-title{font-size:28px;font-weight:700;}
.subtitle{margin-left:auto;color:var(--muted);font-size:16px;}
.controls{display:flex;justify-content:center;align-items:center;gap:20px;padding:16px 0;flex-wrap:wrap;}
.search{width:400px;padding:14px 18px;border-radius:14px;border:1px solid var(--border);outline:none;box-shadow:0 8px 20px rgba(0,0,0,0.03);font-size:18px;}
.select{padding:14px 18px;border-radius:14px;border:1px solid var(--border);background:var(--card);outline:none;font-weight:600;cursor:pointer;appearance:none;-webkit-appearance:none;transition:background 0.2s;font-size:18px;}
.select option:hover,.select option:focus{background:#ffe0e6;color:var(--accent-dark);}
.select:focus,.select:active{background:#ffe0e6;color:var(--accent-dark);}
.date-bar-wrap{padding:12px 24px;display:flex;align-items:center;gap:12px;overflow:hidden;}
.date-bar{display:flex;gap:12px;overflow-x:auto;flex:1;scroll-behavior:smooth; -ms-overflow-style: none; scrollbar-width: none;} 
.date-bar::-webkit-scrollbar{display:none;}
.date-pill{width:90px;height:90px;border-radius:50%;background:var(--card);display:flex;flex-direction:column;align-items:center;justify-content:center;border:1px solid var(--border);box-shadow:var(--soft-shadow);cursor:pointer;font-weight:700;color:var(--accent-dark);transition:all .18s;font-size:20px;}
.date-pill .day{font-size:16px;color:var(--muted);}
.date-pill .num{font-size:26px;margin-top:4px;}
.date-pill:hover{transform:translateY(-6px);}
.date-pill.active{background:linear-gradient(180deg,var(--accent),var(--accent-dark));color:white;border-color:var(--accent-dark);box-shadow:0 14px 40px rgba(215,120,144,0.2);}
.grid{display:flex;flex-direction:column;gap:20px;padding:24px 32px 140px;overflow-y:auto;max-height:calc(100vh - 280px); -ms-overflow-style:none; scrollbar-width:none;} 
.grid::-webkit-scrollbar{display:none;}
.card{background: var(--card);padding: 20px 24px;border-radius: 16px;border:1px solid var(--border);display:flex;align-items:center;justify-content:flex-start;gap:16px;box-shadow:var(--soft-shadow);transition: transform .12s ease, background .2s;font-size:18px;}
.card:hover{transform:translateY(-4px);background:#fff0f4;}
.avatar{width:80px;height:80px;border-radius:14px;object-fit:cover;flex-shrink:0;}
.info{flex:1;display:flex;flex-direction:column;justify-content:center;gap:2px;}
.info h3{margin:0;font-size:20px;color:var(--accent-dark);}
.info p{margin:0;color:var(--muted);font-size:16px;}
.status-badge{flex-shrink:0;margin-left:10px;}
.badge{display:inline-block;padding:8px 14px;border-radius:12px;font-weight:700;font-size:14px;transition: transform .2s;}
.Upcoming{background:#ffeef6;color:var(--accent-dark);}
.Completed{background:#eafcf0;color:#0f8a4a;}
.Critical{background:#ffe6ea;color:#c33a53;}
.actions{display:flex;gap:10px;flex-shrink:0;}
.btn{padding:8px 16px;border-radius:12px;font-weight:700;font-size:16px;cursor:pointer;border:none;transition: all .2s;}
.btn-primary{background:var(--accent);color:white;}
.btn-outline{background:transparent;border:1px solid var(--border);color:var(--accent-dark);}
.fab{position:fixed;right:28px;bottom:28px;width:72px;height:72px;border-radius:50%;background:var(--accent);color:white;font-size:32px;border:none;box-shadow:0 12px 32px rgba(215,120,144,0.28);cursor:pointer}
.modal-back{position:fixed;inset:0;background:rgba(0,0,0,0.35);display:none;align-items:center;justify-content:center;z-index:9999;}
.modal{width:500px;background:linear-gradient(180deg,rgba(255,255,255,0.98),#fff);border-radius:12px;padding:20px;box-shadow:0 20px 60px rgba(0,0,0,0.25);}
.modal h3{margin:0 0 12px 0;}
.form-row{display:flex;gap:10px;margin-bottom:12px;}
.form-row input, .form-row select, .form-row textarea{flex:1;padding:14px;border-radius:8px;border:1px solid var(--border);outline:none;font-size:16px;}
.modal .actions{display:flex;justify-content:flex-end;gap:10px;margin-top:8px;}
.arrow{background:transparent;border:none;font-size:28px;color:var(--accent-dark);cursor:pointer;display:flex;align-items:center;padding:4px;}
@media (max-width:900px){
  .controls{flex-direction:column;align-items:center;gap:12px;}
  .grid{padding:16px;max-height:calc(100vh - 260px);}
  .modal{width:92%;}
  .date-pill{width:80px;height:80px;}
}
</style>
</head>
<body>

<header class="header">
  <a href="doc_dashboard.php" class="back">&#8592;</a>
  <div>
    <div class="page-title">Appointments</div>
    <div class="subtitle">Click any date to view appointments</div>
  </div>
</header>

<section class="controls">
  <input id="search" class="search" placeholder="Search by name, type or time...">
  <select id="statusFilter" class="select">
    <option value="all">All status</option>
    <option value="Upcoming">Upcoming</option>
    <option value="Completed">Completed</option>
    <option value="Critical">Critical</option>
  </select>
</section>

<div class="date-bar-wrap">
  <button id="prevBtn" class="arrow">&lsaquo;</button>
  <div class="date-bar" id="dateBar"></div>
  <button id="nextBtn" class="arrow">&rsaquo;</button>
</div>

<main class="grid" id="grid"></main>
<button id="fab" class="fab">+</button>

<div id="modalBack" class="modal-back">
  <div class="modal" role="dialog">
    <h3 id="modalTitle">Modal</h3>
    <div id="modalContent">
      <div style="color:var(--muted);font-size:14px" id="modalInfo"></div>
      <div class="form-row">
        <select id="mPatient">
          <option value="">Select Patient</option>
          <?php
          $users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll();
          foreach ($users as $u) {
              echo "<option value='{$u['id']}'>{$u['username']}</option>";
          }
          ?>
        </select>
        <input id="mTime" type="time">
      </div>
      <div class="form-row">
        <input id="mDate" type="date">
        <input id="mType" placeholder="Type (e.g. Checkup)">
      </div>
      <div class="form-row">
        <select id="mStatus">
          <option>Upcoming</option>
          <option>Completed</option>
          <option>Critical</option>
        </select>
      </div>
    </div>
    <div class="actions">
      <button id="modalClose" class="btn btn-outline">Cancel</button>
      <button id="modalSave" class="btn btn-primary">Save</button>
    </div>
  </div>
</div>

<script>
let appointments = <?php echo $appointments_json; ?>;
const week = <?php echo $week_json; ?>;
const todayISO = "<?php echo $today_iso; ?>";

const dateBar = document.getElementById('dateBar');
const grid = document.getElementById('grid');
const searchInput = document.getElementById('search');
const statusFilter = document.getElementById('statusFilter');
const modalBack = document.getElementById('modalBack');
const modalTitle = document.getElementById('modalTitle');
const modalSave = document.getElementById('modalSave');
const modalClose = document.getElementById('modalClose');
const fab = document.getElementById('fab');

let selectedDate = todayISO;
let modalMode = 'add';
let modalTargetId = null;

function renderDateBar(){
  dateBar.innerHTML = '';
  week.forEach(d=>{
    const pill = document.createElement('button');
    pill.className='date-pill';
    pill.dataset.date=d.iso;
    pill.innerHTML=`<div class="day">${d.label}</div><div class="num">${d.num}</div>`;
    pill.addEventListener('click',()=>{selectedDate=d.iso; setActivePill(); renderAppointments();});
    dateBar.appendChild(pill);
  });
  setActivePill();
}

function setActivePill(){
  document.querySelectorAll('.date-pill').forEach(p=>p.classList.remove('active'));
  const active=Array.from(document.querySelectorAll('.date-pill')).find(p=>p.dataset.date===selectedDate);
  if(active) active.classList.add('active');
  if(active) active.scrollIntoView({behavior:'smooth',inline:'center'});
}

document.getElementById('prevBtn').onclick = () => { dateBar.scrollBy({left:-100,behavior:'smooth'}); };
document.getElementById('nextBtn').onclick = () => { dateBar.scrollBy({left:100,behavior:'smooth'}); };

function renderAppointments(){
  grid.innerHTML='';
  const q=searchInput.value.trim().toLowerCase();
  const status=statusFilter.value;
  let list=appointments.filter(a=>a.appt_date===selectedDate);
  if(status!=='all') list=list.filter(a=>a.status===status);
  if(q) list=list.filter(a=>(a.name+' '+a.appt_type+' '+a.appt_time).toLowerCase().includes(q));

  if(list.length===0){
    const no=document.createElement('div');
    no.textContent="No appointments for this day.";
    no.style.padding='36px';
    no.style.color='var(--muted)';
    no.style.textAlign='center';
    grid.appendChild(no);
    return;
  }

  list.forEach(a=>{
    const card=document.createElement('div');
    card.className='card';
    card.innerHTML=`
      <div class="info">
        <h3>${a.name}</h3>
        <p>${a.appt_time} • ${a.appt_type}</p>
      </div>
      <div class="status-badge">
        <div class="badge ${a.status}">${a.status}</div>
      </div>
      <div class="actions">
        <button class="btn btn-primary" onclick="showModal('edit',${a.id})">Open</button>
        <button class="btn btn-outline" onclick="deleteAppointment(${a.id}, ${a.patient_id})">Delete</button>
      </div>
    `;
    grid.appendChild(card);
  });
}

function showModal(mode,id=null){
  modalMode=mode; modalTargetId=id;
  modalBack.style.display='flex';
  const patient=document.getElementById('mPatient');
  const date=document.getElementById('mDate');
  const time=document.getElementById('mTime');
  const type=document.getElementById('mType');
  const status=document.getElementById('mStatus');
  const modalInfo=document.getElementById('modalInfo');

  if(mode==='add'){
    modalTitle.textContent="Add Appointment";
    patient.value=''; date.value=selectedDate; time.value='10:00'; type.value=''; status.value='Upcoming';
    modalInfo.textContent='';
  }else if(mode==='edit'){
    const ap=appointments.find(a=>a.id===id);
    if(!ap) return;
    modalTitle.textContent="Edit Appointment";
    patient.value=ap.patient_id; date.value=ap.appt_date; time.value=ap.appt_time;
    type.value=ap.appt_type; status.value=ap.status;
    modalInfo.textContent=`ID: ${ap.id}`;
  }
}

modalClose.onclick=()=>modalBack.style.display='none';
fab.onclick=()=>showModal('add');
modalBack.onclick=e=>{if(e.target===modalBack) modalBack.style.display='none';};

modalSave.onclick=()=>{
  const patient=document.getElementById('mPatient').value;
  const date=document.getElementById('mDate').value;
  const time=document.getElementById('mTime').value;
  const type=document.getElementById('mType').value;
  const status=document.getElementById('mStatus').value;

  if(!patient||!date||!time||!type){
    alert('Please fill all fields');
    return;
  }

  fetch('appointments_action.php',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({
      action: modalMode==='add'?'add':'update',
      type:'appointments',
      id: modalTargetId,
      patient_id: patient,
      date: date,
      time: time,
      type_name: type,
      status: status
    })
  }).then(res=>res.json()).then(res=>{
    if(res.success){
      if(modalMode==='add'){
        appointments.push({id:res.id,patient_id:patient,name:document.querySelector(`#mPatient option[value='${patient}']`).textContent,appt_date:date,appt_time:time,appt_type:type,status:status});
      }else{
        const idx=appointments.findIndex(a=>a.id===modalTargetId);
        appointments[idx]={...appointments[idx],patient_id:patient,appt_date:date,appt_time:time,appt_type:type,status:status,name:document.querySelector(`#mPatient option[value='${patient}']`).textContent};
      }
      modalBack.style.display='none';
      renderAppointments();
    }else alert(res.error);
  });
};

function deleteAppointment(id, patient_id){
  if(!confirm('Delete this appointment?')) return;
  fetch('appointments_action.php',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({action:'delete',type:'appointments',id:id,patient_id:patient_id})
  }).then(res=>res.json()).then(res=>{
    if(res.success){
      appointments=appointments.filter(a=>a.id!==id);
      renderAppointments();
    }else alert(res.error);
  });
}

searchInput.oninput=renderAppointments;
statusFilter.onchange=renderAppointments;

renderDateBar();
renderAppointments();
</script>
</body>
</html>
