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

    $inner_content = $svg_contents[$name] ?? '';
    if (empty($inner_content)) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 12h.01"/><path d="M12 12h.01"/><path d="M16 12h.01"/></svg>';
    }
    if (strpos($inner_content, '<img') !== false) {
        return $inner_content;
    }
    return '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">' . $inner_content . '</svg>';
}

// --- END PHP ICON FUNCTION ---

$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'Child Care Guide';

$icon_map = [
    'FIRST WEEKS' => 'first_weeks',
    'BABY CARE GUIDE' => 'baby_care_guide',
    'BABY 0-24 MONTHS' => 'baby_0_24_months',
    'CLOTHING' => 'clothing',
    'BREASTFEEDING GUIDE' => 'breastfeeding',
    'MENTAL HEALTH' => 'mental_health'
];

$icon_key = trim(strtoupper($topic_title));
$icon_name = $icon_map[$icon_key] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $topic_title; ?> - Child Guide</title>
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

/* --- Desktop Optimized Container --- */
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

/* Header Bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 25px;
    border-bottom: 2px solid var(--accent-color);
    margin-bottom: 30px;
}
.title {
    font-size: 26px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
    text-align: center;
    flex: 1;
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

/* --- Article Section --- */
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

/* --- Article Content --- */
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
</style>
</head>
<body>

<div class="app-container">
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a> 
        <h1 class="title">Childhood Guide</h1>
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
        <h2>🌸 Pregnancy: A Beautiful Journey of Life</h2>
        <p>Pregnancy ek aisi journey hai jo ek woman ke liye sabse emotional, exciting aur life-changing experience hoti hai. Ye sirf ek physical process nahi, balki ek emotional aur spiritual journey bhi hai — jahan ek nayi zindagi ka aarambh hota hai.</p>

        <h2>💖 First Trimester (0 – 12 Weeks)</h2>
        <p>Is period mein baby ka development shuru hota hai. Woman ke body mein hormonal changes aate hain — jaise nausea, vomiting, fatigue aur mood swings.</p>
        <h3>Care Tips:</h3>
        <ul>
            <li>Healthy diet lo (fruits, vegetables, protein-rich food).</li>
            <li>Folic acid supplements lena na bhulo.</li>
            <li>Doctor se regular check-up karwati raho.</li>
        </ul>

        <h2>🌼 Second Trimester (13 – 27 Weeks)</h2>
        <p>Ye phase comparatively comfortable hota hai. Baby ka heartbeat sunai dena shuru hota hai, aur tummy clearly visible ho jata hai.</p>
        <h3>Care Tips:</h3>
        <ul>
            <li>Iron aur calcium-rich food lena zaroori hai.</li>
            <li>Light walk aur mild exercise doctor ke kehne par kar sakti ho.</li>
            <li>Baby movements feel karne lagti ho – ye sabse special moment hota hai.</li>
        </ul>

        <h2>🌷 Third Trimester (28 – 40 Weeks)</h2>
        <p>Ab delivery ke near aane ke signs milte hain. Back pain, swelling, aur tiredness common hoti hai.</p>
        <h3>Care Tips:</h3>
        <ul>
            <li>Enough rest lo aur heavy kaam avoid karo.</li>
            <li>Hospital bag pehle se ready rakh lo.</li>
            <li>Regularly doctor se consult karo, especially agar koi pain ya unusual symptom feel ho.</li>
        </ul>

        <h2>🌺 Emotional Health Matters</h2>
        <p>Pregnancy mein emotional changes normal hain. Kabhi anxiety, kabhi happiness — ye sab hormones ke changes ke karan hota hai. Partner aur family ka support sabse important role play karta hai.</p>

        <h2>🍼 Final Thoughts</h2>
        <p>Har pregnancy unique hoti hai. Apne body ki suno, apni health ko priority do, aur apne baby ke saath is journey ko enjoy karo.</p>
        <p><strong>Remember:</strong> “A healthy mother means a healthy baby.” ❤️</p>
    </div>
</div>
    <?php include '15footer.php'; ?>

</body>
</html>
