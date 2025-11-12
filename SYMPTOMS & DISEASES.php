<?php
// --- PHP ICON FUNCTION (COPIED FOR CONSISTENCY) ---
function getArticleIconSvg($name) {
    $svg_contents = [
        'pregnant_woman' => '<img width="60" height="60" src="https://img.icons8.com/external-icongeek26-outline-icongeek26/64/external-pregnancy-pregnancy-amp-maternity-icongeek26-outline-icongeek26.png" alt="Pregnancy Icon"/>',
        'medical_bag' => '<img width="64" height="64" src="https://img.icons8.com/external-outlines-amoghdesign/64/external-first-aid-education-vol-02-outlines-amoghdesign.png" alt="Symptoms & Diseases Icon"/>', // Key icon for this page
        'fetal_movement' => '<img width="80" height="80" src="https://img.icons8.com/dotty/80/embryo.png" alt="Fetal Movement Icon"/>',
        'diet_advice' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/tableware.png" alt="Diet Advice Icon"/>',
        'informed_choices' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/information--v2.png" alt="Informed Choices Icon"/>',
        'hospital' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/hospital--v3.png" alt="Labour/Hospital Icon"/>',
        'breastfeeding' => '<img width="96" height="96" src="https://img.icons8.com/parakeet-line/96/1A1A1A/breastfeeding.png" alt="breastfeeding"/>',
        'mental_health' => '<img width="68" height="68" src="https://img.icons8.com/external-smashingstocks-mixed-smashing-stocks/68/1A1A1A/external-mental-health-managerial-psychology-smashingstocks-mixed-smashing-stocks.png" alt="mental health"/>',
    ];

    $inner_content = $svg_contents[$name] ?? '';
    if (empty($inner_content)) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 12h.01"/><path d="M12 12h.01"/><path d="M16 12h.01"/></svg>';
    }
    // Check if the content is already an <img> tag (like the current setup)
    if (strpos($inner_content, '<img') !== false) {
        return $inner_content;
    }
    // If it's a raw SVG path/code, wrap it (though none of the current icons are raw SVG code)
    return '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">' . $inner_content . '</svg>';
}

// --- END PHP ICON FUNCTION ---

// Set the current topic title for this page
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'SYMPTOMS & DISEASES';

$icon_map = [
    'PREGNANCY' => 'pregnant_woman',
    'SYMPTOMS & DISEASES' => 'medical_bag',
    'FETAL MOVEMENT' => 'fetal_movement',
    'DIET ADVICE' => 'diet_advice',
    'INFORMED CHOICES' => 'informed_choices',
    'LABOUR' => 'hospital',
    'BREASTFEEDING GUIDE' => 'breastfeeding',
    'MENTAL HEALTH' => 'mental_health'
];

$icon_key = trim(strtoupper($topic_title));
// Default to 'medical_bag' if topic isn't found in map
$icon_name = $icon_map[$icon_key] ?? 'medical_bag'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $topic_title; ?> - Guide</title>
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
.app-container {
    width: 85%;
    max-width: 1100px;
    margin: 40px auto;
    padding: 50px;
    background: var(--white);
    min-height: 100vh;
    box-shadow: 0 0 25px rgba(0,0,0,0.05);
    border-radius: 15px;
}
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 25px;
    border-bottom: 2px solid var(--accent-color);
    margin-bottom: 40px;
}
.title {
    font-size: 26px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
    flex: 1;
    text-align: center;
}
.back-arrow {
    font-weight: 700;
    font-size: 38px;
    color: var(--text);
    text-decoration: none;
    padding-right: 20px;
}
.favorite-icon {
    font-size: 28px;
    color: var(--primary-color);
    text-decoration: none;
    padding-left: 20px;
}
.article-header {
    text-align: center;
    margin-bottom: 40px;
}
.article-icon-lg {
    display: inline-block;
    padding: 20px;
    border-radius: 50%;
    background: var(--accent-color);
    margin-bottom: 15px;
}
.article-icon-lg img, .article-icon-lg svg {
    width: 70px !important;
    height: 70px !important;
}
.article-title {
    font-size: 34px;
    font-weight: 700;
    color: var(--primary-color);
    text-transform: uppercase;
    margin: 10px 0;
}
.article-content {
    font-size: 18px;
    line-height: 1.8;
    color: var(--text);
}
.article-content h2 {
    font-size: 24px;
    color: var(--primary-color);
    margin-top: 40px;
    border-left: 5px solid var(--accent-color);
    padding-left: 12px;
}
.article-content h3 {
    font-size: 20px;
    color: var(--primary-color);
    margin-top: 25px;
}
.article-content p {
    margin-bottom: 20px;
    text-align: justify;
}
.article-content ul {
    margin-left: 30px;
    margin-bottom: 20px;
}
.article-content li {
    margin-bottom: 10px;
}
.warning-box {
    border: 2px solid #ff6347;
    padding: 20px;
    border-radius: 10px;
    margin-top: 25px;
    background-color: #fff0f0;
}
</style>
</head>
<body>

<div class="app-container">
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a>
        <h1 class="title">Health Information</h1>
        <a href="#" class="favorite-icon">&#10084;</a>
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
        <h2>🌺 Symptoms & Diseases During Pregnancy</h2>
        <p>During pregnancy, the body undergoes many **physical and emotional changes**. These changes are a natural part of a healthy pregnancy — however, some symptoms should never be ignored.</p>

        <h2>🤰 Common Pregnancy Symptoms</h2>
        <h3>1. Morning Sickness:</h3>
        <p>Nausea and vomiting are common in the early months. Eating fresh fruits and light, frequent meals provides relief.</p>
        <h3>2. Fatigue:</h3>
        <p>The body uses more energy for fetal development, so **getting adequate rest is essential**.</p>
        <h3>3. Mood Swings:</h3>
        <p>Emotions can fluctuate due to hormonal changes. **Family support** is very helpful during this time.</p>
        <h3>4. Back Pain & Leg Cramps:</h3>
        <p>Back pain occurs as the baby's weight increases. Light **stretching and walking** are beneficial.</p>
        <h3>5. Swelling in Feet or Hands:</h3>
        <p>Slight swelling is normal, but if it becomes excessive, **consult your doctor** immediately.</p>
        <h3>6. Frequent Urination:</h3>
        <p>This symptom is caused by increased pressure on the bladder — do not reduce your water intake; **stay hydrated**.</p>

        <h2>⚠️ Common Complications in Pregnancy</h2>
        <ul>
            <li>**Anemia:** Weakness due to a lack of iron and folic acid. Consume green leafy vegetables and take doctor-prescribed supplements.</li>
            <li>**Gestational Diabetes:** Blood sugar levels can increase during pregnancy. Get tested as advised by your doctor and maintain **strict diet control**.</li>
            <li>**High Blood Pressure (Preeclampsia):** If you experience signs like headache, significant swelling, or blurred vision, contact your doctor right away.</li>
            <li>**Urinary Tract Infection (UTI):** If you feel a burning sensation or pain, increase your water intake and seek medical help.</li>
            <li>**Thyroid Problems:** Thyroid imbalance can affect the baby's growth, making **regular check-ups** necessary.</li>
        </ul>

        <div class="warning-box">
            <h2>💡 When to Call Your Doctor</h2>
            <p>Seek urgent medical attention if you experience any of the following:</p>
            <ul>
                <li>Vaginal bleeding or spotting.</li>
                <li>Severe abdominal pain or persistent cramping.</li>
                <li>A sudden decrease in baby movement (after 20 weeks).</li>
                <li>High fever or severe dizziness.</li>
            </ul>
        </div>

        <h2>🌼 Stay Safe, Stay Healthy</h2>
        <p>Pregnancy is a natural process, but a little **awareness and proactive care** keep both you and your baby healthy.</p>
        <p>By maintaining a balanced diet, getting enough rest, and keeping up with regular doctor visits, you can enjoy a safe and happy pregnancy 💕</p>
    </div>
</div>
    <?php include '15footer.php'; ?>

</body>
</html>