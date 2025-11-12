<?php
// --- PHP ICON FUNCTION (COPIED FOR CONSISTENCY) ---
function getArticleIconSvg($name) {
    $svg_contents = [
        'pregnant_woman' => '<img width="60" height="60" src="https://img.icons8.com/external-icongeek26-outline-icongeek26/64/external-pregnancy-pregnancy-amp-maternity-icongeek26-outline-icongeek26.png" alt="Pregnancy Icon"/>',
        'medical_bag' => '<img width="64" height="64" src="https://img.icons8.com/external-outlines-amoghdesign/64/external-first-aid-education-vol-02-outlines-amoghdesign.png" alt="Symptoms & Diseases Icon"/>',
        'fetal_movement' => '<img width="80" height="80" src="https://img.icons8.com/dotty/80/embryo.png" alt="Fetal Movement Icon"/>',
        'diet_advice' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/tableware.png" alt="Diet Advice Icon"/>',
        'informed_choices' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/information--v2.png" alt="Informed Choices Icon"/>',
        'hospital' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/hospital--v3.png" alt="Labour/Hospital Icon"/>', // Key icon for this page
        'breastfeeding' => '<img width="96" height="96" src="https://img.icons8.com/parakeet-line/96/1A1A1A/breastfeeding.png" alt="breastfeeding"/>',
        'mental_health' => '<img width="68" height="68" src="https://img.icons8.com/external-smashingstocks-mixed-smashing-stocks/68/1A1A1A/external-mental-health-managerial-psychology-smashingstocks-mixed-smashing-stocks.png" alt="mental health"/>',
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'LABOUR & DELIVERY';

// Map topic title to an icon name
$icon_map = [
    'PREGNANCY' => 'pregnant_woman',
    'SYMPTOMS & DISEASES' => 'medical_bag',
    'FETAL MOVEMENT' => 'fetal_movement',
    'DIET ADVICE' => 'diet_advice',
    'INFORMED CHOICES' => 'informed_choices',
    'LABOUR & DELIVERY' => 'hospital',
    'LABOUR' => 'hospital', // Both are mapped to the hospital icon
    'BREASTFEEDING GUIDE' => 'breastfeeding',
    'MENTAL HEALTH' => 'mental_health'
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'hospital'; // Default to Hospital icon
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $topic_title; ?> - Guide</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* --- Inherited Styles for consistency (Desktop/Max-Width View) --- */
:root {
    --primary-color: #5d5c61; /* Dark Gray */
    --accent-color: #b0c4de; /* Light Slate Gray */
    --soft-bg: #f8f8f8; 
    --white: #ffffff;
    --text: #333;
    --highlight-color-light: #e6f7ff; /* Light Blue for Stage/Tip Highlights */
    --highlight-color-dark: #80bfff; /* Medium Blue for headers */
    --warning-red: #ff6347; 
}
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: var(--soft-bg);
    color: var(--text);
    padding: 0;
}
.app-container {
    width: 90%;
    max-width: 1000px; 
    margin: 40px auto;
    padding: 40px;
    background: var(--white);
    min-height: 80vh;
    box-shadow: 0 0 25px rgba(0,0,0,0.05);
    border-radius: 12px;
}
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0 20px;
    border-bottom: 1px solid #eee; 
    margin-bottom: 20px;
}
.title {
    font-size: 24px; 
    font-weight: 700;
    margin: 0;
    color: var(--text);
    flex-grow: 1;
    text-align: center;
}
.back-arrow {
    font-weight: 700;
    font-size: 30px;
    color: var(--text);
    text-decoration: none;
    padding-right: 15px;
}
.favorite-icon {
    font-size: 24px;
    color: var(--primary-color);
    text-decoration: none;
    padding-left: 15px;
}

/* --- Article Specific Styles --- */
.article-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 0 10px;
}
.article-icon-lg {
    display: inline-block;
    padding: 20px; 
    border-radius: 50%;
    background: var(--accent-color);
    margin-bottom: 20px;
}
.article-icon-lg img, .article-icon-lg svg {
    width: 70px !important; 
    height: 70px !important;
    max-width: 70px;
    max-height: 70px;
    color: var(--primary-color); 
}
.article-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-color);
    line-height: 1.3;
    margin: 0;
    text-transform: uppercase;
}

.article-content {
    padding: 0 20px;
}

.article-content h2 {
    font-size: 22px;
    color: var(--primary-color);
    margin-top: 30px;
    border-left: 5px solid var(--accent-color);
    padding-left: 15px;
}
.article-content h3 {
    font-size: 18px;
    color: var(--primary-color);
    margin-top: 20px;
    margin-bottom: 10px;
}
.article-content p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 20px;
    color: var(--text);
    text-align: justify;
}
.article-content ul {
    padding-left: 20px;
    margin-bottom: 20px;
}
.article-content li {
    margin-bottom: 12px;
    list-style-type: disc;
    font-size: 16px;
}

.stage-box {
    border-left: 5px solid var(--highlight-color-dark);
    background-color: var(--highlight-color-light);
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 5px;
}
.stage-box h3 {
    margin-top: 5px;
    color: var(--primary-color);
}
.warning-callout {
    border: 1px solid var(--warning-red);
    background-color: #fff0f0;
    padding: 15px;
    border-radius: 8px;
    margin-top: 25px;
    font-weight: 500;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Labour and Childbirth</h1>
        <a href="#" class="favorite-icon" title="Favorites">&#10084;</a>
    </div>

    <div class="article-header">
        <?php if ($icon_name): ?>
            <div class="article-icon-lg">
                <?php echo getArticleIconSvg($icon_name); ?>
            </div>
        <?php endif; ?>
        <h1 class="article-title"><?php echo $topic_title; ?></h1>
    </div>

    <div class="article-content">
        
        <h2>🏥 Labour & Delivery: What to Expect</h2>
        <p>Labour is the process through which your body prepares to give birth. While it can feel overwhelming, understanding the stages and signs can help you feel more confident and prepared for the big day.</p>

        <h2>🤰 Signs of Labour</h2>
        <p>Labour can start gradually or suddenly. Common signs include:</p>
        <ul>
            <li>**Regular contractions:** Tightening of the uterus that comes at regular intervals and gradually intensifies.</li>
            <li>**Water breaking (Rupture of Membranes):** A sudden gush or a slow trickle of amniotic fluid.</li>
            <li>**Backache and cramps:** Persistent lower back pain or abdominal cramps that don't ease with rest.</li>
            <li>**Bloody show:** A small amount of pink or brownish blood-tinged mucus, a sign that your cervix is beginning to dilate.</li>
        </ul>

        <div class="warning-callout">
            **Remember:** If you notice any unusual pain, heavy bleeding, or **reduced fetal movement**, contact your healthcare provider immediately.
        </div>

        <h2>⏳ Stages of Labour</h2>
        <p>Labour is typically divided into three main stages:</p>
        
        <div class="stage-box">
            <h3>Stage 1: Early Labour (Latent Phase)</h3>
            <ul>
                <li>Cervix gradually dilates from **0 to 4-6 cm**.</li>
                <li>Contractions may be mild and irregular, lasting 30-45 seconds.</li>
                <li>**Action:** Stay calm, rest, and hydrate at home.</li>
            </ul>
        </div>
        
        <div class="stage-box">
            <h3>Stage 1: Active Labour</h3>
            <ul>
                <li>Cervix dilates from **4-6 cm to 10 cm (Full Dilation)**.</li>
                <li>Contractions become stronger, longer (45-60+ seconds), and closer together.</li>
                <li>**Action:** Focus on breathing techniques, relaxation, and utilize support from your partner or birth companion. This is usually when you head to the hospital.</li>
            </ul>
        </div>
        
        <div class="stage-box">
            <h3>Stage 2 & 3: Delivery of Baby and Placenta</h3>
            <ul>
                <li>**Delivery:** Once fully dilated (10 cm), you will push during contractions to help your baby be born.</li>
                <li>**Placenta:** After the baby's birth, the placenta is delivered (Stage 3).</li>
                <li>**Post-delivery:** **Skin-to-skin contact** and early breastfeeding are highly recommended.</li>
            </ul>
        </div>

        <h2>💡 Pain Management Options</h2>
        <p>Every birth experience is unique. Discuss these options with your doctor in advance:</p>
        <ul>
            <li>**Breathing and relaxation techniques** – Helps you stay calm and focused through each contraction.</li>
            <li>**Movement and positions** – Walking, swaying, or changing positions (squatting, kneeling) may ease discomfort.</li>
            <li>**Medical support** – Options include Epidural anesthesia, nitrous oxide (gas and air), or other pain-relief medications as advised by your doctor.</li>
        </ul>
        
        <h2>🌼 Tips for a Positive Labour Experience</h2>
        <ul style="list-style-type: circle;">
            <li>**Prepare a birth plan:** Know your preferences for labour, delivery, and immediate postpartum care.</li>
            <li>**Stay hydrated and nourished:** Sip water and light snacks if allowed by your hospital policy.</li>
            <li>**Trust your support team:** Lean on your partner, doula, or healthcare providers for comfort and encouragement.</li>
            <li>**Focus on your breathing:** Controlled breathing helps manage pain and stay relaxed.</li>
            <li>**Stay flexible:** Labour may not always go exactly as planned, and that’s okay. Be prepared for changes.</li>
        </ul>

    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>