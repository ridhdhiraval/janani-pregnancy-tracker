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
$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'MENTAL HEALTH';

// Map topic title to an icon name
$icon_map = [
    'PREGNANCY' => 'pregnant_woman',
    'SYMPTOMS & DISEASES' => 'medical_bag',
    'FETAL MOVEMENT' => 'fetal_movement',
    'DIET ADVICE' => 'diet_advice',
    'INFORMED CHOICES' => 'informed_choices',
    'LABOUR' => 'hospital',
    'BREASTFEEDING GUIDE' => 'breastfeeding',
    'MENTAL HEALTH' => 'mental_health', // Key icon for this page
    'FIRST WEEKS' => 'first_weeks',
    'BABY CARE GUIDE' => 'baby_care_guide',
    'BABY 0-24 MONTHS' => 'baby_0_24_months',
    'CLOTHING' => 'clothing'
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? 'mental_health'; // Default to Mental Health icon
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
    --warning-red: #ff6347; /* Tomato Red */
    --soft-green: #e0f8e0; /* Soft Green for well-being */
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
    max-width: 1000px; /* Wider container for desktop view */
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
    padding: 20px; /* Slightly larger padding */
    border-radius: 50%;
    background: var(--accent-color);
    margin-bottom: 20px;
}
.article-icon-lg img, .article-icon-lg svg {
    width: 70px !important; /* Larger icon size */
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

/* Content Layout (Optional: for wider screens) */
.content-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr; /* Main content + side section */
    gap: 40px;
    padding: 0 20px;
}
@media (max-width: 768px) {
    .content-wrapper {
        grid-template-columns: 1fr;
        gap: 20px;
    }
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
    margin-bottom: 8px;
    list-style-type: disc;
}

.highlight-box {
    padding: 20px;
    border-radius: 10px;
    margin-top: 25px;
}
.warning-box {
    background-color: #fff0f0; 
    border: 1px solid var(--warning-red);
}
.self-care-box {
    background-color: var(--soft-green);
    border: 1px solid #70a670;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Mental Health Support</h1>
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

    <div class="content-wrapper">
        <div class="main-content">
            
            <h2>🌿 Mental Health During Pregnancy</h2>
            <p>Pregnancy brings not only physical changes but also significant **emotional and mental shifts**. While this is normal, sometimes stress or anxiety can become overwhelming, posing a challenge for both the mother and the baby.</p>

            <h2>🤰 Common Emotional Changes</h2>
            <p>These are some common emotional fluctuations you might experience during pregnancy:</p>
            <ul>
                <li>**Mood swings:** Feeling happy one moment and irritable the next, often due to hormonal changes.</li>
                <li>**Anxiety:** Tension regarding the baby's health, the delivery process, and future parenting.</li>
                <li>**Tearfulness:** Feeling emotional or teary-eyed over minor things is normal.</li>
            </ul>
            <p style="font-style: italic;">**Remember:** These are often normal feelings and are usually short-term.</p>
            
            <div class="highlight-box warning-box">
                <h2>💡 Signs You Shouldn’t Ignore</h2>
                <p>If these symptoms persist or worsen, it’s important to **consult a doctor or mental health professional immediately**:</p>
                <ul>
                    <li>Persistent sadness or a sense of hopelessness</li>
                    <li>Intense anxiety or recurrent panic attacks</li>
                    <li>Sleep problems that severely impact daily functioning</li>
                    <li>Feeling emotionally disconnected from your body or your baby</li>
                </ul>
            </div>
        </div>

        <div class="side-content">
            
            <div class="highlight-box self-care-box">
                <h2>🌸 Self-Care Tips</h2>
                <p>To nurture your mind and heart, try incorporating these self-care tips into your routine:</p>
                <ul>
                    <li>**Talk openly** – Share your feelings with family, friends, or a support group.</li>
                    <li>**Exercise gently** – Activities like walking, prenatal yoga, and stretching can uplift your mood.</li>
                    <li>**Rest properly** – Ensuring adequate sleep and taking naps are crucial.</li>
                    <li>**Mindfulness & Meditation** – Breathing exercises and meditation can bring a sense of calmness.</li>
                    <li>**Healthy diet** – Balanced meals nourish both your body and brain.</li>
                    <li>**Professional help** – If stress or depression is severe, reach out to a certified counselor or psychologist.</li>
                </ul>
            </div>
            
            <h2>🌼 Partner & Family Role</h2>
            <p>Family support helps make pregnancy a stress-free and joyful experience:</p>
            <ul>
                <li>Provide emotional support.</li>
                <li>Assist with daily routines and household chores.</li>
                <li>Listen actively without judgment.</li>
            </ul>
        </div>
    </div>

    <div class="article-content" style="padding: 0 20px;">
        <h2 style="margin-top: 40px;">🧘 Key Takeaway</h2>
        <p style="font-weight: 500;">Don't neglect your mental health. Acknowledge your emotions, seek help when necessary, and make daily self-care a priority. **Happy, healthy mom = happy, healthy baby** 💕</p>
    </div>

</div>
    <?php include '15footer.php'; ?>

</body>
</html>