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
        'first_weeks' => '<img width="96" height="96" src="https://img.icons8.com/pulsar-line/96/1A1A1A/baby-feet.png" alt="baby-feet"/>', // Key icon for this page
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'BABY’S FIRST WEEKS';

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
    'BABY’S FIRST WEEKS' => 'first_weeks',
    'FIRST WEEKS' => 'first_weeks',
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'first_weeks'; // Default to Baby Feet icon
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
    --baby-pink: #ffe0e6; /* Soft pink for new baby content */
    --safe-sleep-red: #ff7080;
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
    background-color: var(--baby-pink);
    border: 1px solid var(--safe-sleep-red);
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
        <h1 class="title">Newborn Care</h1>
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
        
        <h2>👶 Baby’s First Weeks: What to Expect</h2>
        <p>The first few weeks of a newborn’s life are full of excitement, adjustment, and learning — for both the baby and parents. Understanding these initial phases can help you feel more **confident and prepared**.</p>

        <h2>🍼 Feeding Your Newborn</h2>
        <ul>
            <li>**Breastfeeding:** Feed **on demand**, which is usually every 2–3 hours. Look for hunger cues like rooting, sucking, or fussiness.</li>
            <li>**Formula feeding:** Follow the instructions on the formula package and consult your pediatrician for amounts and frequency.</li>
            <li>**Signs of sufficient feeding:** Baby has **6–8 wet diapers a day**, gains weight steadily, and appears alert after feeding.</li>
        </ul>

        <h2>🛌 Sleep Patterns & Safety</h2>
        <ul>
            <li>Newborns sleep a lot—around **14–17 hours a day**, but typically in short stretches of 2–4 hours. Night and day cycles are often mixed up initially.</li>
        </ul>
        
        <div class="safety-box">
            <h3>Safe Sleep Guidelines (SIDS Prevention):</h3>
            <ul>
                <li>Always place your baby **on their back** to sleep, on a **firm mattress**.</li>
                <li>Avoid loose blankets, pillows, bumper pads, or toys in the crib.</li>
                <li>The baby should sleep in the same room as the parents, but in their own separate bed/crib, for the first six months.</li>
            </ul>
        </div>

        <h2>🧼 Diapering & Hygiene</h2>
        <ul>
            <li>Expect frequent diaper changes — every **2–3 hours** or immediately when soiled.</li>
            <li>Clean gently with plain water or mild baby wipes to prevent diaper rash.</li>
            <li>Monitor stools for color and consistency; changes can indicate dietary or health issues. (The first few stools, **meconium**, will be dark and sticky).</li>
        </ul>

        <h2>🏥 Health & Checkups</h2>
        <ul>
            <li>Schedule your baby’s **first pediatric visit** within the first week after discharge.</li>
            <li>Keep track of **vaccinations** and growth milestones.</li>
            <li>**Monitor for any signs of illness:** persistent crying, fever (high or low), difficulty feeding, or unusual lethargy. Contact your doctor immediately if these occur.</li>
        </ul>
        
        <h2>💞 Bonding & Comfort</h2>
        <p>Building a strong connection is vital for your baby’s development:</p>
        <ul>
            <li>**Skin-to-skin contact** helps regulate your baby’s temperature, heart rate, and promotes deep emotional bonding.</li>
            <li>Speak, sing, and gently touch your baby to foster emotional connection and stimulate their senses.</li>
            <li>Responding to cries promptly helps your baby feel **safe and secure**, building trust.</li>
        </ul>
        
        <h2>🌿 Soothing Techniques</h2>
        <ul>
            <li>**Swaddling** can help babies feel secure (like being in the womb), but stop once the baby shows signs of being able to roll over.</li>
            <li>Gentle rocking, soft music, or white noise can often calm a fussy baby.</li>
            <li>Pacifiers can be used if needed, but avoid using them to delay feeding.</li>
        </ul>

    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>