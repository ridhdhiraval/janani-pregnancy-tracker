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
        'baby_care_guide' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/mother.png" alt="mother"/>', // Key icon for this page
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'BABY CARE GUIDE';

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
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'baby_care_guide'; // Default to Mother icon
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
    --section-highlight: #f0f8ff; /* Lightest blue for main sections */
    --safety-warning: #ffcccc; /* Light red for safety points */
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

.safety-box {
    background-color: var(--safety-warning);
    border: 1px solid #cc0000;
    padding: 15px;
    border-radius: 8px;
    margin-top: 25px;
}
.section-tip {
    background-color: var(--section-highlight);
    border-left: 3px solid var(--accent-color);
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Newborn Essentials</h1>
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
        
        <h2>👶 Baby Care Guide: Essentials for New Parents</h2>
        <p>Caring for a newborn can feel overwhelming at first, but understanding the basics can make the experience **joyful and manageable**. This guide covers essential aspects of baby care during the first months.</p>

        <h2>🍼 Feeding Your Baby</h2>
        <div class="section-tip">
            <ul>
                <li>**Breastfeeding:** Feed your baby **on demand**, usually every 2–3 hours. Look for hunger cues like rooting, sucking, or fussiness.</li>
                <li>**Formula Feeding:** Prepare formula as instructed, ensure proper **sterilization**, and feed on a schedule that suits your baby’s needs.</li>
                <li>**Signs of Adequate Feeding:** Baby should have **6–8 wet diapers per day**, gain weight steadily, and be alert after feeding.</li>
            </ul>
        </div>

        <h2>🛌 Sleep & Rest (Safe Sleep)</h2>
        <p>Newborns sleep **14–17 hours daily**, often in short stretches of 2–4 hours.</p>
        
        <div class="safety-box">
            <h3>SIDS Risk Reduction:</h3>
            <ul>
                <li>Always lay your baby **on their back** in a safe sleep environment.</li>
                <li>Keep the crib free of pillows, **loose blankets**, bumpers, and toys.</li>
                <li>Use a firm mattress and a fitted sheet.</li>
            </ul>
        </div>

        <h2>👶 Diapering & Hygiene</h2>
        <ul>
            <li>**Change frequently,** at least every 2–3 hours or when soiled.</li>
            <li>Clean gently with mild wipes or water, and allow the skin to air dry briefly to **prevent irritation** and diaper rash.</li>
            <li>Monitor stool and urine patterns — any sudden changes may require a pediatric consultation.</li>
        </ul>

        <h2>🛁 Bathing & Skin Care</h2>
        <ul>
            <li>**Sponge baths** are recommended until the umbilical cord stump falls off and heals completely.</li>
            <li>Use lukewarm water and mild, fragrance-free baby soap/wash.</li>
            <li>Pat the baby dry gently (avoid rubbing) and apply a baby-friendly moisturizer if the skin seems dry.</li>
        </ul>

        <h2>🩺 Health & Medical Care</h2>
        <div class="section-tip">
            <ul>
                <li>Schedule **regular pediatric checkups** and strictly follow **vaccination schedules**.</li>
                <li>**Watch for signs of illness:** fever, persistent crying, poor feeding, or unusual lethargy.</li>
                <li>Keep emergency numbers handy and know when to seek urgent care.</li>
            </ul>
        </div>

        <h2>💞 Bonding & Emotional Care</h2>
        <ul>
            <li>**Skin-to-skin contact** promotes bonding, regulates baby’s temperature, and calms both baby and parent.</li>
            <li>Talk, sing, and make gentle eye contact to foster emotional connection.</li>
            <li>**Respond to cries promptly** — it helps your baby feel secure and builds trust.</li>
        </ul>

        <h2>🌿 Soothing & Comfort Tips</h2>
        <ul>
            <li>**Swaddling** can help your baby feel secure and sleep better (stop swaddling when the baby can roll over).</li>
            <li>Gentle rocking, white noise (like a fan or dedicated machine), or soft lullabies may soothe a fussy baby.</li>
            <li>Pacifiers can be used if needed, but avoid using them as a substitute for feeding.</li>
        </ul>

    </div>

</div>
    <?php include '15footer.php'; ?>
b
</body>
</html>