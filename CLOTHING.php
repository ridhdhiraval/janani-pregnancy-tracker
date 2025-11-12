<?php
// --- PHP ICON FUNCTION (COPIED FOR CONSISTENCY) ---
function getArticleIconSvg($name) {
    $svg_contents = [
        'pregnant_woman' => '<img width="60" height="60" src="https://img.icons8.com/external-icongeek26-outline-icongeek26/64/external-pregnancy-pregnancy-amp-maternity-icongeek26-outline-icongeek26.png" alt="Pregnancy Icon"/>',
        'medical_bag' => '<img width="64" height="64" src="https://img.icons8.com/external-outlines-amoghdesign/64/external-first-aid-education-vol-02-outlines-amoghdesign.png" alt="Symptoms & Diseases Icon"/>',
        'fetal_movement' => '<img width="80" height="80" src="https://img.icons8.com/dotty/80/embryo.png" alt="Fetal Movement Icon"/>',
        'diet_advice' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/tableware.png" alt="Diet Advice Icon"/>',
        'informed_choices' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/information--v2.png" alt="Informed Choices Icon"/>',
        'hospital' => '<img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/1A1A1A/hospital--v3.png" alt="Labour/Hospital Icon"/>',
        'breastfeeding' => '<img width="96" height="96" src="https://img.icons8.com/parakeet-line/96/1A1A1A/breastfeeding.png" alt="breastfeeding"/>',
        'mental_health' => '<img width="68" height="68" src="https://img.icons8.com/external-smashingstocks-mixed-smashing-stocks/68/1A1A1A/external-mental-health-managerial-psychology-smashingstocks-mixed-smashing-stocks.png" alt="mental health"/>',
        'first_weeks' => '<img width="96" height="96" src="https://img.icons8.com/pulsar-line/96/1A1A1A/baby-feet.png" alt="baby-feet"/>',
        'baby_care_guide' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/mother.png" alt="mother"/>',
        'baby_0_24_months' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/day-care.png" alt="day-care"/>',
        'clothing' => '<img width="52" height="52" src="https://img.icons8.com/metro/52/romper.png" alt="romper"/>', // Key icon for this page
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'BABY CLOTHING GUIDE';

// Map topic title to an icon name
$icon_map = [
    'PREGNANCY' => 'pregnant_woman',
    'SYMPTOMS & DISEASES' => 'medical_bag',
    'FETAL MOVEMENT' => 'fetal_movement',
    'DIET ADVICE' => 'diet_advice',
    'INFORMED CHOICES' => 'informed_choices',
    'LABOUR' => 'hospital',
    'BREASTFEEDING GUIDE' => 'breastfeeding',
    'MENTAL HEALTH' => 'mental_health',
    'FIRST WEEKS' => 'first_weeks',
    'BABY CARE GUIDE' => 'baby_care_guide',
    'BABY 0–24 MONTHS' => 'baby_0_24_months',
    'BABY CLOTHING GUIDE' => 'clothing',
    'CLOTHING' => 'clothing',
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'clothing'; // Default to Clothing icon
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
    --section-highlight: #e6f9ff; /* Light blue for sections */
    --safety-alert: #ffdddd; /* Soft red for safety */
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

.fabric-tip {
    background-color: var(--section-highlight);
    border: 1px solid var(--accent-color);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 25px;
}
.safety-box {
    background-color: var(--safety-alert);
    border: 1px solid #cc0000;
    padding: 15px;
    border-radius: 8px;
    margin-top: 25px;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Dressing Your Baby</h1>
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
        
        <h2>👕 Baby Clothing Guide: Comfort, Safety & Style</h2>
        <p>Dressing your baby might seem simple, but choosing the right clothing is important for **comfort, health, and safety**. Babies have delicate skin, regulate temperature differently, and need clothes that allow **free movement**.</p>

        <h2>🌿 Choosing the Right Fabric</h2>
        <div class="fabric-tip">
            <ul>
                <li>**Soft & Breathable:** **Cotton** is ideal for everyday wear because it’s soft, breathable, and gentle on delicate skin.</li>
                <li>**Avoid Rough Materials:** Wool or synthetic fabrics can irritate the skin; use them only as **outer layers**.</li>
                <li>**Sensitive Skin:** Choose **hypoallergenic fabrics** to help prevent rashes and allergies.</li>
            </ul>
        </div>

        <h2>👶 Clothing Essentials by Age</h2>
        <ul>
            <li>**Newborns (0–3 Months):** Focus on **onesies, sleepers**, mittens (to prevent scratching), and soft hats. Clothes with **snap buttons** make diaper changes easier.</li>
            <li>**Infants (3–12 Months):** Use comfortable tops, pants, bodysuits, and **rompers**. Easy-to-wear clothing with **elastic waistbands** is highly recommended.</li>
            <li>**Toddlers (12–24 Months):** Mix of casual wear, pajamas, and sturdy shoes. Clothing should allow maximum movement for crawling, walking, and play.</li>
        </ul>

        <h2>🛌 Sleepwear & Comfort</h2>
        <ul>
            <li>Use **sleepers or pajamas** suitable for the season.</li>
            <li>Ensure clothes are **not too tight**, with secure fasteners.</li>
            <li>**Avoid strings, loose ribbons, or small decorations** that can be pulled off and swallowed (choking hazards).</li>
            <li>**Never use loose blankets** in the crib; opt for sleep sacks or wearable blankets instead.</li>
        </ul>

        <h2>🧥 Layering & Weather Tips</h2>
        <p>Babies often need **one extra layer** of clothing compared to what an adult is comfortable wearing.</p>
        <ul>
            <li>**Layering:** Dress babies in multiple layers so you can easily **add or remove clothing** according to the temperature changes (e.g., a cotton undershirt, a long-sleeve shirt, and a vest).</li>
            <li>**Winter:** Use hats, mittens, and booties to protect extremities from the cold.</li>
            <li>**Summer:** Choose **lightweight, breathable cotton** clothing to keep babies cool and comfortable.</li>
        </ul>

        <div class="safety-box">
            <h2>🌸 Safety Considerations</h2>
            <ul>
                <li>Check labels for **fire-resistant fabrics** for all sleepwear.</li>
                <li>**Avoid small embellishments** like loose buttons, sequins, or removable bows that can be a choking hazard.</li>
                <li>Ensure hats and headbands are **not too tight** and **remove them while sleeping** to prevent suffocation.</li>
                <li>Keep clothes clean and dry to prevent skin irritation and rashes.</li>
            </ul>
        </div>

        <h2>🎨 Practical Tips for Parents</h2>
        <ul>
            <li>**Fasteners:** **Snap fasteners** on the bottom make diaper changes quick and easy, especially at night.</li>
            <li>**Size:** Choose clothes with **stretchable fabrics** and a little extra room to allow for rapid growth and flexibility.</li>
            <li>**Washing:** Always **wash new clothes** before first use with gentle, baby-friendly, fragrance-free detergents.</li>
        </ul>

    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>