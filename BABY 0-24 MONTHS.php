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
        'baby_0_24_months' => '<img width="100" height="100" src="https://img.icons8.com/ios/100/1A1A1A/day-care.png" alt="day-care"/>', // Key icon for this page
    ];

    return $svg_contents[$name] ?? '';
}
// --- END PHP ICON FUNCTION ---

// Get the topic name from the URL query string
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'BABY 0–24 MONTHS';

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
    '0-24 MONTHS' => 'baby_0_24_months',
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'baby_0_24_months'; // Default to Day Care icon
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
    --section-highlight: #e0f0ff; /* Light blue for milestones */
    --safety-box: #fffacd; /* Light yellow for safety */
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

.milestone-box {
    background-color: var(--section-highlight);
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
    margin-bottom: 25px;
}
.safety-alert {
    background-color: var(--safety-box);
    border: 1px solid #c4b05a;
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
        <h1 class="title">Early Childhood Development</h1>
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
        
        <h2>👶 Baby 0–24 Months: Growth, Milestones & Care Guide</h2>
        <p>The first two years of a child’s life are full of **rapid growth, intense learning, and exciting milestones**. Understanding what to expect helps parents support their baby’s development and ensure their well-being.</p>

        <h2>🍼 Nutrition & Feeding</h2>
        <ul>
            <li>**0–6 Months:** **Exclusive breastfeeding** is recommended. Formula is an alternative. Feed on demand.</li>
            <li>**6–12 Months (Introducing Solids):** Introduce solid foods gradually while continuing breast milk or formula. Start with pureed fruits, vegetables, and **iron-rich cereals**.</li>
            <li>**12–24 Months (Toddler):** Offer a variety of healthy family foods. Encourage **self-feeding** with safe finger foods and transition from bottles to cups.</li>
        </ul>

        <h2>🛌 Sleep & Routine</h2>
        <ul>
            <li>**Newborns (0–3 Months):** Sleep **14–17 hours/day** in short stretches.</li>
            <li>**Infants (4–12 Months):** Sleep **12–16 hours/day**, including naps.</li>
            <li>**Toddlers (1–2 Years):** Sleep **11–14 hours/day**, including 1–2 naps.</li>
        </ul>
        <p>Establish a **consistent bedtime routine** (bath, book, cuddle) to help your child feel secure and improve sleep quality.</p>
        
        <h2>👶 Physical Growth & Milestones</h2>
        <p>Skills develop progressively, often in this order:</p>
        <ul>
            <li>**Gross Motor Skills:** Rolling, sitting, crawling, standing, and eventually walking/running.</li>
            <li>**Fine Motor Skills:** Grasping, holding toys, and using fingers (pincer grasp) to explore and pick up small items.</li>
        </ul>

        <div class="milestone-box">
            <h3>Key Milestones by Age (Approximate):</h3>
            <ul>
                <li>**6 Months:** Sits with support, reaches for objects, rolls over both ways.</li>
                <li>**12 Months:** Pulls up to stand, may take first steps ("cruising"), waves bye-bye.</li>
                <li>**18–24 Months:** Walks steadily, begins running, can climb stairs (with help), can build small towers with blocks.</li>
            </ul>
        </div>
        
        <h2>🗣️ Language & Cognitive Development</h2>
        <ul>
            <li>**0–6 Months:** Recognizes voices, coos, smiles, and laughs.</li>
            <li>**6–12 Months:** Babbles (e.g., "mama," "dada"), responds to simple words, and understands gestures (e.g., pointing).</li>
            <li>**12–24 Months:** Uses **50+ words**, forms simple two-word sentences, follows basic one-step instructions, and engages in pretend play.</li>
        </ul>

        <h2>💞 Emotional & Social Growth</h2>
        <ul>
            <li>Shows strong **attachment to primary caregivers** and seeks comfort from them.</li>
            <li>Develops curiosity and begins to interact with other children and adults through play.</li>
            <li>Develops independence gradually (saying "No," wanting to do things alone) while still needing firm guidance and reassurance.</li>
        </ul>

        <div class="safety-alert">
            <h2>🛁 Health & Safety Priorities</h2>
            <ul>
                <li>Keep up with **vaccinations** and routine pediatric check-ups.</li>
                <li>**Baby-proof your home:** cover sharp edges, secure furniture/TVs, lock cabinets, and ensure safe, supervised play areas.</li>
                <li>Monitor signs of illness: high fever, unusual lethargy, vomiting, persistent diarrhea, or spreading rash.</li>
            </ul>
        </div>
        
        <h2>🌿 Play & Learning</h2>
        <p>Play is the child's work and is **crucial for development**:</p>
        <ul>
            <li>Use **age-appropriate toys**, books, and sensory activities (e.g., water play, textures).</li>
            <li>Encourage movement, problem-solving, and language through interactive games (like peek-a-boo).</li>
            <li>**Read to your child daily** to stimulate language skills and strengthen bonding.</li>
        </ul>

    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>