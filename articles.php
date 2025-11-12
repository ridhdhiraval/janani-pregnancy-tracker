<?php
// --- PHP ICON FUNCTION (UPDATED WITH SVG PLACEHOLDERS FOR NEW CHILD ICONS) ---
function getArticleIconSvg($name) {
    $svg_contents = [
        'pregnant_woman' => '<img width="60" height="60" src="https://img.icons8.com/external-icongeek26-outline-icongeek26/64/external-pregnancy-pregnancy-amp-maternity-icongeek26-outline-icongeek26.png" alt="Pregnancy Icon"/>',
        'medical_bag' => '<img width="64" height="64" src="https://img.icons8.com/external-outlines-amoghdesign/64/external-first-aid-education-vol-02-outlines-amoghdesign.png" alt="Symptoms & Diseases Icon"/>',
        'fetal_movement' => '<img width="80" height="80" src="https://img.icons8.com/dotty/80/embryo.png" alt="Fetal Movement Icon"/>',
        'diet_advice' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/tableware.png" alt="Diet Advice Icon"/>',
        'hospital' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/hospital--v3.png" alt="Labour/Hospital Icon"/>',
        'first_weeks' => '<img width="96" height="96" src="https://img.icons8.com/pulsar-line/96/1A1A1A/baby-feet.png" alt="baby-feet"/>',
        'baby_care_guide' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/mother.png" alt="mother"/>',
        'baby_0_24_months' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/day-care.png" alt="day-care"/>',
        'clothing' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/romper.png" alt="romper"/>',
        'breastfeeding' => '<img width="96" height="96" src="https://img.icons8.com/parakeet-line/96/1A1A1A/breastfeeding.png" alt="breastfeeding"/>',
        'mental_health' => '<img width="68" height="68" src="https://img.icons8.com/external-smashingstocks-mixed-smashing-stocks/68/1A1A1A/external-mental-health-managerial-psychology-smashingstocks-mixed-smashing-stocks.png" alt="mental health"/>',
    ];

    return $svg_contents[$name] ?? '';
}

$article_topics = [
    'Pregnancy' => [
        ['label' => 'PREGNANCY', 'icon' => 'pregnant_woman', 'snippet' => 'Weekly changes, growth tracking, and what to expect.'],
        ['label' => 'SYMPTOMS & DISEASES', 'icon' => 'medical_bag', 'snippet' => 'How to manage morning sickness, aches, and minor concerns.'],
        ['label' => 'FETAL MOVEMENT', 'icon' => 'fetal_movement', 'snippet' => 'Track baby kicks and understand movement patterns.'],
        ['label' => 'MENTAL HEALTH', 'icon' => 'mental_health', 'snippet' => 'Emotional well-being and managing pregnancy stress.'],
        ['label' => 'DIET ADVICE', 'icon' => 'diet_advice', 'snippet' => 'Safe food guide, nutrition plans, and supplements.'],
        ['label' => 'LABOUR', 'icon' => 'hospital', 'snippet' => 'Signs of labor, delivery options, and coping techniques.'],
        ['label' => 'BREASTFEEDING GUIDE', 'icon' => 'breastfeeding', 'snippet' => 'Tips and guides for a successful breastfeeding journey.'],
    ],
    'Child' => [
        ['label' => 'FIRST WEEKS', 'icon' => 'first_weeks', 'snippet' => 'Caring for a newborn in the initial phase.'],
        ['label' => 'BABY CARE GUIDE', 'icon' => 'baby_care_guide', 'snippet' => 'Bathing, diapering, and health checks.'],
        ['label' => 'BABY 0-24 MONTHS', 'icon' => 'baby_0_24_months', 'snippet' => 'Development milestones and feeding schedules.'],
        ['label' => 'CLOTHING', 'icon' => 'clothing', 'snippet' => 'Sizing, washing, and what to buy.'],
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Articles & Guides</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary-color: #5d5c61;
    --accent-color: #b0c4de;
    --soft-bg: #f8f8f8;
    --white: #ffffff;
    --text: #333;
}
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: var(--soft-bg);
    color: var(--text);
}

/* --- MAIN CONTAINER ADJUSTED FOR DESKTOP --- */
.app-container {
    width: 90%;
    max-width: 1400px;
    margin: 40px auto;
    background: var(--white);
    padding: 40px;
    box-shadow: 0 0 25px rgba(0,0,0,0.05);
    border-radius: 12px;
}

/* Top bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 25px;
    border-bottom: 2px solid var(--accent-color);
}
.title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
}
.back-arrow {
    font-size: 34px;
    color: var(--text);
    text-decoration: none;
}
.top-bar a {
    color: var(--primary-color);
    font-size: 26px;
    text-decoration: none;
}

/* Search */
.search-container {
    margin: 10px 0;
    position: relative;
}
.search-input {
    width: 100%;
    padding: 15px 0px 15px 50px;
    border: none;
    border-radius: 50px;
    background-color: var(--accent-color);
    font-size: 17px;
    outline: none;
}
.search-container::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 20px;
    transform: translateY(-50%);
    width: 22px; height: 22px;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="%235d5c61" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>');
    background-repeat: no-repeat;
}

/* Tabs */
.tab-bar {
    display: flex;
    gap: 15px;
    background: var(--soft-bg);
    border-radius: 30px;
    padding: 8px;
    width: fit-content;
    margin: 0 auto 40px;
}
.tab {
    padding: 10px 35px;
    border-radius: 25px;
    color: var(--primary-color);
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}
.tab.active {
    background: var(--primary-color);
    color: white;
}

/* Grid adjusted for desktop */
.topics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    padding: 0;
}

.topic-card {
    text-align: center;
    background: var(--white);
    border-radius: 15px;
    padding: 20px;
    border: 1px solid #eee;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
    text-decoration: none;
    color: var(--text);
    min-height: 200px;
}
.topic-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.topic-icon {
    margin-bottom: 15px;
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.topic-icon img {
    max-width: 70px;
    max-height: 70px;
}
.topic-label {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
}
</style>
</head>
<body>
<div class="app-container">
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Articles</h1>
        <a href="#">&#10084;</a>
    </div>


    <div class="tab-bar">
        <a href="#" class="tab" data-tab="Pregnancy">Pregnancy</a>
        <a href="#" class="tab active" data-tab="Child">Child</a>
    </div>

    <div id="Pregnancy" class="topics-grid" style="display:none;">
        <?php 
        $files = ['pregnancy-guide.php','SYMPTOMS & DISEASES.php','FETAL MOVEMENT.php','MENTAL HEALTH.php','DIET ADVICE.php','LABOUR.php','BREASTFEEDING GUIDE.php'];
        foreach ($article_topics['Pregnancy'] as $i => $topic): ?>
            <a href="<?php echo htmlspecialchars($files[$i] ?? 'DIET ADVICE.php'); ?>" class="topic-card">
                <div class="topic-icon"><?php echo getArticleIconSvg($topic['icon']); ?></div>
                <div class="topic-label"><?php echo htmlspecialchars($topic['label']); ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <div id="Child" class="topics-grid active">
        <?php 
        $files = ['FIRST WEEKS.php','BABY CARE GUIDE.php','BABY 0-24 MONTHS.php','CLOTHING.php'];
        foreach ($article_topics['Child'] as $i => $topic): ?>
            <a href="<?php echo htmlspecialchars($files[$i] ?? 'child-guide.php'); ?>" class="topic-card">
                <div class="topic-icon"><?php echo getArticleIconSvg($topic['icon']); ?></div>
                <div class="topic-label"><?php echo htmlspecialchars($topic['label']); ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script>
const tabs=document.querySelectorAll(".tab"),grids=document.querySelectorAll(".topics-grid");
tabs.forEach(tab=>tab.addEventListener("click",e=>{
e.preventDefault();
tabs.forEach(t=>t.classList.remove("active"));
grids.forEach(g=>g.style.display="none");
tab.classList.add("active");
document.getElementById(tab.dataset.tab).style.display="grid";
}));
document.addEventListener("DOMContentLoaded",()=>{
grids.forEach(g=>g.style.display="none");
document.querySelector('.tab[data-tab="Child"]').classList.add('active');
document.getElementById('Child').style.display='grid';
});
</script>
    <?php include '15footer.php'; ?>

</body>
</html>
