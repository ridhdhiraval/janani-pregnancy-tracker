<?php
require_once __DIR__ . '/config/db.php';
$default_images = [
'images/father1.jpg', // या पूरा पाथ दें जैसे: '/images/father1.jpg'
    'images/father2.jpg', // या पूरा पाथ दें जैसे: '/images/father2.jpg'
    'images/father3.jpg'  // या पूरा पाथ दें जैसे: '/images/father3.jpg'];
];
$pregnancy_data = [
    1 => ['range'=>'0+0 - 0+6','p1'=>'Yatra ki shuruaat. Saathi appointment aur vitamins par dhyan rakhe, emotional support de.','p2'=>'Routine set kare: rest, hydration aur healthy meals me madad kare.'],
    3 => ['range'=>'2+0 - 2+6','p1'=>'Conception ke aas-paas ka samay. Saathi logistics plan kare: doctor search, insurance docs.','p2'=>'Stress kam rakhne ke liye daily walk aur light household support kare.'],
    5 => ['range'=>'4+0 - 4+6','p1'=>'Early symptoms badh sakte hain. Saathi prenatal visit schedule aur notes maintain kare.','p2'=>'Food aversions par dhyan de, small-meals plan banaye aur kitchen support de.'],
    8 => ['range'=>'7+0 - 7+6','p1'=>'Nausea peak par ho sakti hai. Saathi morning routine, ginger snacks, and hydration track kare.','p2'=>'Household workload share kare, rest windows ensure kare, positivity banaye rakhe.'],
    12 => ['range'=>'11+0 - 11+6','p1'=>'Energy wapas aane lagegi. Saathi second-trimester screenings ke reminders set kare.','p2'=>'Financial planning aur leave policy check kare, travel/hospital preferences discuss kare.'],
    20 => ['range'=>'19+0 - 19+6','p1'=>'Baby movements mehsoos hone lagte hain. Saathi kick-count app/diary me participate kare.','p2'=>'Anatomy scan attend kare, questions list ready rakhe, birth classes explore kare.'],
    30 => ['range'=>'29+0 - 29+6','p1'=>'Backache/heartburn common. Saathi nightly routines, pillows/support setup kare.','p2'=>'Hospital bag (mom/baby/partner) checklist start kare, car-seat installation verify kare.'],
    40 => ['range'=>'39+0 - 39+6','p1'=>'Full term. Saathi on-call rahe, route-to-hospital rehearse kare, contacts standby.','p2'=>'Early-labour comfort measures seekhe: breathing, massage, timing contractions, logistics handle kare.']
];

$full_week_map = [];
for ($i=1;$i<=43;$i++){
    if($i<=13) $trimester=1; elseif($i<=27) $trimester=2; else $trimester=3;
    $start=$i-1;
    $range="{$start}+0 - {$start}+6";
    $content=$pregnancy_data[$i]??$pregnancy_data[max(array_filter(array_keys($pregnancy_data), fn($w)=>$w<$i))];
    $content['images']=$default_images;
    $full_week_map[$i]=array_merge($content,['range'=>$range,'trimester'=>$trimester]);
}
$pregnancy_data=$full_week_map;
$trimester_weeks=[];
foreach($pregnancy_data as $w=>$d) $trimester_weeks[$d['trimester']][]=['week'=>$w,'range'=>$d['range']];

$default_week=3;
$current_week=isset($_GET['week'])?(int)$_GET['week']:$default_week;
$min_week=1; $max_week=43;
$current_week=max($min_week,min($max_week,$current_week));
$data=$pregnancy_data[$current_week];
$current_images=$data['images'];

try {
    $tbl=$pdo->query("SHOW TABLES LIKE 'partner_week_content'");
    $has=$tbl && $tbl->rowCount()>0;
} catch (Exception $e) { $has=false; }
if ($has) {
    $stmt=$pdo->prepare('SELECT p1,p2,image_url1,image_url2,image_url3 FROM partner_week_content WHERE week = ? LIMIT 1');
    $stmt->execute([$current_week]);
    $row=$stmt->fetch();
    if ($row) {
        $data['p1']=$row['p1'] ?? $data['p1'];
        $data['p2']=$row['p2'] ?? $data['p2'];
        $imgs=[]; if(!empty($row['image_url1'])) $imgs[]=$row['image_url1']; if(!empty($row['image_url2'])) $imgs[]=$row['image_url2']; if(!empty($row['image_url3'])) $imgs[]=$row['image_url3'];
        if(count($imgs)>0) $current_images=$imgs;
        $pregnancy_data[$current_week]['p1']=$data['p1'];
        $pregnancy_data[$current_week]['p2']=$data['p2'];
        $pregnancy_data[$current_week]['images']=$current_images;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Partner Week <?= $current_week ?></title>
<style>
:root { --primary-pink:#ff91a4; --active-text:#333; --background-color:#fcf8f6; --content-color:#4a4a4a; --white-bg:#fff; }
body{ font-family:"Segoe UI", Roboto, Arial; background:var(--background-color); margin:0; display:flex; justify-content:center; padding:50px 0; }
.container{ width:95%; max-width:1200px; background:var(--white-bg); border-radius:20px; box-shadow:0 6px 35px rgba(0,0,0,0.1); overflow:hidden; display:flex; flex-direction:column; }
.header{ display:flex; justify-content:space-between; align-items:center; padding:20px 35px; }
.close-btn{ background:none; border:none; font-size:36px; cursor:pointer; }
.week-selector{ border:1px solid #e0e0e0; border-radius:30px; padding:12px 25px; font-size:18px; display:flex; align-items:center; gap:12px; cursor:pointer; }
.visual-area{ position:relative; width:100%; height:550px; overflow:hidden; }
.slides-container{ display:flex; width:100%; height:100%; transition:transform 0.5s ease-in-out; }
.slide{ min-width:100%; height:100%; }
.slide img{ width:100%; height:100%; object-fit:cover; border-bottom:3px solid #f4f4f4; }
.slider-dots{ position:absolute; bottom:20px; left:50%; transform:translateX(-50%); display:flex; gap:12px; z-index:5; }
.dot{ width:12px; height:12px; background:rgba(255,255,255,0.7); border-radius:50%; cursor:pointer; transition:background 0.3s; }
.dot.active{ background:var(--primary-pink); }
.content-area{ padding:40px 80px; }
.info-title{ font-size:42px; font-weight:700; margin-bottom:30px; color:var(--active-text); }
.info-paragraph{ font-size:20px; color:var(--content-color); line-height:1.9; margin-bottom:30px; }
.footer{ display:flex; justify-content:space-between; align-items:center; border-top:1px solid #eee; padding:25px 80px; }
.footer a{ text-decoration:none; font-weight:600; font-size:20px; color:var(--primary-pink); display:flex; align-items:center; }
.arrow{ margin:0 10px; }
#week-modal{ position:fixed; top:0; left:0; width:100%; height:100%; background:var(--white-bg); z-index:1000; overflow-y:auto; display:none; padding:50px 150px; }
#modal-header{ display:flex; align-items:center; border-bottom:1px solid #eee; padding-bottom:25px; margin-bottom:30px; }
#modal-close-btn{ background:none; border:none; font-size:36px; cursor:pointer; margin-right:25px; }
.trimester-heading{ font-size:26px; font-weight:700; color:var(--primary-pink); margin:35px 0 20px 0; }
.week-grid{ display:grid; grid-template-columns:repeat(8,1fr); gap:18px; }
.week-item{ text-align:center; text-decoration:none; color:var(--active-text); padding:18px 0; border-radius:12px; border:1px solid #eee; cursor:pointer; transition:all 0.2s ease; }
.week-item:hover{ background:#ffeef1; }
.week-item.active{ background-color:var(--primary-pink); color:#fff; border-color:var(--primary-pink); }
.week-num-display{ font-size:18px; font-weight:600; }
.week-range-display{ font-size:14px; color:#777; }
</style>
</head>
<body>

<div class="container">
    <header class="header">
        <button class="close-btn" onclick="history.back()">&times;</button>
        <div class="week-selector" id="openModalBtn">
            Week <span id="week-num"><?= $current_week ?></span> (<span id="week-range"><?= $data['range'] ?></span>)
        </div>
    </header>

    <div class="visual-area">
        <div class="slides-container" id="slidesContainer">
            <?php foreach ($current_images as $image_url): ?>
                <div class="slide">
                    <img src="<?= htmlspecialchars($image_url) ?>" alt="Week Image" onerror="this.src='https://via.placeholder.com/1200x550?text=Week+Image'">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slider-dots" id="sliderDots"></div>
    </div>

    <main class="content-area">
        <h1 class="info-title" id="week-title">Week <?= $current_week ?></h1>
        <p class="info-paragraph" id="p1"><?= $data['p1'] ?></p>
        <p class="info-paragraph" id="p2"><?= $data['p2'] ?></p>
    </main>

    <footer class="footer">
        <a href="#" id="prevBtn"><span class="arrow">&#8592;</span>Prev</a>
        <a href="#" id="nextBtn">Next<span class="arrow">&#8594;</span></a>
    </footer>
</div>

<div id="week-modal">
    <div id="modal-header">
        <button id="modal-close-btn">&times;</button>
        <div style="font-size:24px; font-weight:700;">Select Week</div>
    </div>
    <?php foreach ($trimester_weeks as $trimester=>$weeks): ?>
        <div class="trimester-heading">Trimester <?= $trimester ?></div>
        <div class="week-grid">
            <?php foreach($weeks as $item): ?>
                <div class="week-item" data-week="<?= $item['week'] ?>">
                    <div class="week-num-display">W <?= $item['week'] ?></div>
                    <div class="week-range-display">(<?= $item['range'] ?>)</div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
let currentWeek = <?= $current_week ?>;
const minWeek = <?= $min_week ?>;
const maxWeek = <?= $max_week ?>;
const data = <?= json_encode($pregnancy_data) ?>;

const weekNumEl = document.getElementById("week-num");
const weekRangeEl = document.getElementById("week-range");
const weekTitleEl = document.getElementById("week-title");
const p1El = document.getElementById("p1");
const p2El = document.getElementById("p2");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const openModalBtn = document.getElementById("openModalBtn");
const weekModal = document.getElementById("week-modal");
const modalCloseBtn = document.getElementById("modal-close-btn");
const weekItems = document.querySelectorAll(".week-item");
const slidesContainer = document.getElementById('slidesContainer');
const sliderDots = document.getElementById('sliderDots');

let currentSlideIndex=0;
let totalSlides=0;
let slideInterval=null;

function updateSlideshow(images){
    slidesContainer.innerHTML=''; sliderDots.innerHTML='';
    totalSlides=images.length; currentSlideIndex=0;

    images.forEach((url,index)=>{
        const slideDiv=document.createElement('div'); slideDiv.classList.add('slide');
        const img=document.createElement('img'); img.src=url; img.alt=`Week ${currentWeek} image ${index+1}`; img.onerror=()=>{img.src='https://via.placeholder.com/1200x550?text=Week+Image'};
        slideDiv.appendChild(img); slidesContainer.appendChild(slideDiv);

        const dot=document.createElement('div'); dot.classList.add('dot'); dot.dataset.index=index;
        dot.addEventListener('click',()=>goToSlide(index));
        sliderDots.appendChild(dot);
    });

    sliderDots.style.display=totalSlides>1?'flex':'none';
    if(totalSlides>0) goToSlide(0);

    if(slideInterval) clearInterval(slideInterval);
    slideInterval=setInterval(()=>goToSlide(currentSlideIndex+1),3000);
}

function goToSlide(index){
    if(totalSlides===0) return;
    currentSlideIndex=(index+totalSlides)%totalSlides;
    slidesContainer.style.transform=`translateX(${-currentSlideIndex*100}%)`;
    document.querySelectorAll('.dot').forEach((dot,idx)=>dot.classList.toggle('active',idx===currentSlideIndex));
}

function updateWeekContent(week,skipModalUpdate=false){
    const weekData=data[week];
    weekNumEl.innerText=week;
    weekRangeEl.innerText=weekData.range;
    weekTitleEl.innerText="Week "+week;
    p1El.innerText=weekData.p1;
    p2El.innerText=weekData.p2;
    updateSlideshow(weekData.images);

    nextBtn.style.pointerEvents=(week>=maxWeek)?'none':'auto';
    prevBtn.style.pointerEvents=(week<=minWeek)?'none':'auto';
    nextBtn.style.color=(week>=maxWeek)?'#ccc':'var(--primary-pink)';
    prevBtn.style.color=(week<=minWeek)?'#ccc':'var(--primary-pink)';

    if(!skipModalUpdate){
        weekItems.forEach(item=>{ item.classList.remove('active');
            if(parseInt(item.dataset.week)===week) item.classList.add('active');
        });
    }
}

nextBtn.addEventListener("click",function(e){e.preventDefault();if(currentWeek<maxWeek){currentWeek++;updateWeekContent(currentWeek);}});
prevBtn.addEventListener("click",function(e){e.preventDefault();if(currentWeek>minWeek){currentWeek--;updateWeekContent(currentWeek);}});

openModalBtn.addEventListener('click',()=>{weekModal.style.display='block';updateWeekContent(currentWeek,true);});
modalCloseBtn.addEventListener('click',()=>{weekModal.style.display='none';});
weekItems.forEach(item=>{item.addEventListener('click',function(){currentWeek=parseInt(this.dataset.week);updateWeekContent(currentWeek);weekModal.style.display='none';});});

updateWeekContent(currentWeek);
</script>

</body>
</html>
