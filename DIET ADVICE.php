<?php
// --- PHP ICON FUNCTION (COPIED FOR CONSISTENCY) ---
function getArticleIconSvg($name) {
    $svg_contents = [
        'pregnant_woman' => '<img width="60" height="60" src="https://img.icons8.com/external-icongeek26-outline-icongeek26/64/external-pregnancy-pregnancy-amp-maternity-icongeek26-outline-icongeek26.png" alt="Pregnancy Icon"/>',
        'medical_bag' => '<img width="64" height="64" src="https://img.icons8.com/external-outlines-amoghdesign/64/external-first-aid-education-vol-02-outlines-amoghdesign.png" alt="Symptoms & Diseases Icon"/>',
        'fetal_movement' => '<img width="80" height="80" src="https://img.icons8.com/dotty/80/embryo.png" alt="Fetal Movement Icon"/>',
        'diet_advice' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/tableware.png" alt="Diet Advice Icon"/>', // Key icon for this page
        'informed_choices' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/information--v2.png" alt="Informed Choices Icon"/>',
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
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'DIET ADVICE';

// Map topic title to an icon name
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
$icon_name = $icon_map[$icon_key] ?? 'diet_advice'; // Default to Diet Advice icon
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
    --highlight-color: #f0fff0; /* Very light green for food tips */
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

.content-wrapper {
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
.nutrient-list li {
    list-style-type: none; /* Remove default list style for custom formatting */
    border-left: 3px solid var(--accent-color);
    padding-left: 10px;
}
.food-safety-tip {
    background-color: var(--highlight-color);
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #d0e0d0;
    margin-top: 25px;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Nutrition Guide</h1>
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

    <div class="content-wrapper article-content">
        
        <h2>🥗 Diet Advice During Pregnancy</h2>
        <p>A **healthy diet** during pregnancy is extremely important because what you eat directly affects your baby’s growth and development. A balanced diet provides energy, essential nutrients, and boosts immunity for both mother and baby.</p>

        <h2>🍎 Essential Nutrients</h2>
        <p>Aapke aur aapke baby ke liye zaroori vitamins aur minerals ki jankari:</p>
        <ul class="nutrient-list">
            <li>
                <h3>Folic Acid</h3>
                <p>Important for **proper brain and spinal development** of the baby. <br>Found in: spinach, broccoli, lentils, fortified cereals.</p>
            </li>
            <li>
                <h3>Iron</h3>
                <p>Needed for red blood cells; helps **prevent anemia** during pregnancy. <br>Found in: red meat, beans, green leafy vegetables.</p>
            </li>
            <li>
                <h3>Calcium</h3>
                <p>Essential for **baby’s bones and teeth**. <br>Found in: milk, yogurt, cheese, fortified plant-based milk.</p>
            </li>
            <li>
                <h3>Protein</h3>
                <p>Supports the development of **baby’s cells and tissues**. <br>Found in: eggs, chicken, fish (low mercury), beans, tofu.</p>
            </li>
            <li>
                <h3>Vitamin D</h3>
                <p>Helps the body **absorb calcium**. <br>Found through sunlight and fortified foods.</p>
            </li>
            <li>
                <h3>Omega-3 Fatty Acids</h3>
                <p>Important for **baby’s brain and eye development**. <br>Found in: salmon, flax seeds, walnuts.</p>
            </li>
        </ul>

        <h2>🥑 Healthy Eating Tips</h2>
        <ul style="list-style-type: circle;">
            <li>**Eat small, frequent meals** – Helps manage nausea or acidity.</li>
            <li>**Stay hydrated** – Drink plenty of water, fresh juice, or coconut water.</li>
            <li>**Limit junk food** – Avoid excessive processed, oily, or sugary foods.</li>
            <li>**Reduce caffeine** – Avoid too much coffee or tea (consult your doctor for safe limits).</li>
            <li>**Practice food safety** – Avoid raw meat, unpasteurized dairy, and undercooked eggs.</li>
        </ul>

        <div class="food-safety-tip">
            <h2>🌸 Special Notes</h2>
            <ul>
                <li>If you are **vegetarian or vegan**, ensure adequate intake of protein, iron, vitamin B12, and omega-3s through diet or supplements.</li>
                <li>**Monitor sugar and refined carbs** to reduce the risk of gestational diabetes.</li>
                <li>Healthy snack options include: **nuts, fresh fruits, yogurt, or smoothies**.</li>
            </ul>
        </div>
        
    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>