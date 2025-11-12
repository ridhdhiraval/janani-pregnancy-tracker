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
        'breastfeeding' => '<img width="96" height="96" src="https://img.icons8.com/parakeet-line/96/1A1A1A/breastfeeding.png" alt="breastfeeding"/>', // Key icon for this page
        'mental_health' => '<img width="68" height="68" src="https://img.icons8.com/external-smashingstocks-mixed-smashing-stocks/68/1A1A1A/external-mental-health-managerial-psychology-smashingstocks-mixed-smashing-stocks.png" alt="mental health"/>',
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'BREASTFEEDING GUIDE';

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
$icon_name = $icon_map[$icon_key] ?? 'breastfeeding'; // Default to Breastfeeding icon
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
    --milk-color: #f7f7e8; /* Soft creamy color for highlights */
    --challenge-color: #ffe0e0; /* Light red for challenges */
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

.benefit-box {
    background-color: var(--milk-color);
    border: 1px solid #e0e0c0;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.challenge-box {
    background-color: var(--challenge-color);
    border: 1px solid #ffb3b3;
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
        <h1 class="title">Newborn Feeding</h1>
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
        
        <h2>🤱 Breastfeeding Guide: Nourishing Your Baby Naturally</h2>
        <p>Breastfeeding is one of the most important things you can do for your baby’s health and development. It provides optimal nutrition, strengthens immunity, and fosters a **deep emotional bond** between mother and baby.</p>

        <div class="benefit-box">
            <h2>🍼 Benefits of Breastfeeding</h2>
            
            <h3>For your baby:</h3>
            <ul>
                <li>Provides **essential nutrients** in the right proportions.</li>
                <li>Strengthens the **immune system** and reduces the risk of infections.</li>
                <li>Supports brain development and healthy growth.</li>
            </ul>

            <h3>For you as a mother:</h3>
            <ul>
                <li>Helps the uterus return to its normal size faster.</li>
                <li>Reduces the risk of postpartum bleeding.</li>
                <li>Supports weight loss and improves **maternal-infant bonding**.</li>
            </ul>
        </div>

        <h2>🤰 When to Start Breastfeeding</h2>
        <ul>
            <li>**Early initiation:** Try to breastfeed **within the first hour** after birth.</li>
            <li>**Colostrum:** The first milk is **rich in antibodies and nutrients**. It’s small in quantity but highly concentrated and incredibly beneficial.</li>
        </ul>

        <h2>🪑 Proper Latching & Positioning</h2>
        <p>A good latch ensures your baby gets enough milk efficiently and helps **prevent nipple pain** for you:</p>
        
        <h3>Common Positions:</h3>
        <ul>
            <li>**Cradle hold:** Baby’s head rests in the crook of your arm.</li>
            <li>**Cross-cradle hold:** Supports baby’s head with the opposite hand.</li>
            <li>**Football hold:** Tucks baby under your arm like a football (often helpful after C-section).</li>
        </ul>

        <h3>Tips for a Proper Latch:</h3>
        <ul>
            <li>Baby’s mouth covers **both the nipple and a large part of the areola**.</li>
            <li>Lips should be **flanged outward** (like a fish mouth), not tucked in.</li>
            <li>Baby’s chin touches your breast, with the nose slightly away from the breast.</li>
        </ul>

        <h2>⏰ How Often to Breastfeed (Feeding on Demand)</h2>
        <ul>
            <li>Newborns usually feed frequently: every **2–3 hours**, totaling about **8–12 times a day**.</li>
            <li>**Watch for hunger cues** like rooting (turning head to seek the breast), sucking on hands, or fussiness, rather than strictly following the clock.</li>
            <li>Feeding on demand is key to establishing and maintaining a strong milk supply.</li>
        </ul>

        <h2>🌿 Tips for Successful Breastfeeding</h2>
        <ul>
            <li>**Stay hydrated:** Drink plenty of water and nutritious fluids.</li>
            <li>**Eat balanced meals:** Include proteins, healthy fats, fruits, and vegetables.</li>
            <li>**Rest whenever possible:** Fatigue can negatively affect milk production.</li>
            <li>**Avoid smoking and alcohol:** Both can impact milk quality and baby's health.</li>
            <li>**Seek help if needed:** Lactation consultants or pediatricians can provide essential guidance on issues like latching problems or low milk supply.</li>
        </ul>

        <div class="challenge-box">
            <h2>⚠️ Common Challenges</h2>
            <ul>
                <li>**Sore nipples:** Ensure proper latch; use doctor-approved nipple creams if necessary.</li>
                <li>**Engorgement:** Feed frequently and gently express milk to relieve pressure.</li>
                <li>**Blocked ducts or mastitis:** Use warm compresses, gently massage the affected area, and continue feeding from that side to help clear the blockage.</li>
            </ul>
        </div>

        <h2>🌸 Key Takeaway</h2>
        <p>Breastfeeding is a journey — it takes **patience, practice, and support**. Every mother and baby pair is unique, so trust your instincts and don’t hesitate to ask for professional guidance. Nourishing your baby naturally is a beautiful gift, and even small efforts make a big difference. 💕</p>

    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>