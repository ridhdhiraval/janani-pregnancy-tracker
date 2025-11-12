<?php
// --- PHP ICON FUNCTION ---
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

// --- END ICON FUNCTION ---

$topic_title = isset($_GET['topic']) ? htmlspecialchars($_GET['topic']) : 'FETAL MOVEMENT';

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
$icon_name = $icon_map[$icon_key] ?? 'fetal_movement';
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
    width: 80px !important;
    height: 80px !important;
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
.action-box {
    border: 2px solid var(--primary-color);
    padding: 20px;
    border-radius: 10px;
    margin-top: 25px;
    background-color: #f0f4ff;
}
</style>
</head>
<body>

<div class="app-container">
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&lt;</a>
        <h1 class="title">Fetal Movement</h1>
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
        <h2>🩷 Fetal Movement (Baby Kicks)</h2>
        <p>Feeling your baby move for the first time is one of the most exciting parts of pregnancy. These gentle flutters, often called <strong>“quickening”</strong>, are a reassuring sign that your baby is growing and developing well.</p>

        <h2>🤰 When Do Fetal Movements Start?</h2>
        <p>Most women begin to feel fetal movement between <strong>18 to 25 weeks</strong> of pregnancy.</p>
        <ul>
            <li>First pregnancy: around <strong>24–25 weeks</strong>.</li>
            <li>Previous pregnancies: as early as <strong>16 weeks</strong>.</li>
        </ul>
        <p>Movements start soft — like <strong>butterflies or tiny bubbles</strong> — and become stronger as your baby grows.</p>

        <h2>💞 Why Fetal Movements Are Important</h2>
        <ul>
            <li>Baby’s muscles and bones developing properly.</li>
            <li>Ensures baby is getting enough oxygen and nutrients.</li>
            <li>Placenta is working effectively.</li>
        </ul>

        <h2>⏰ How Often Should You Feel the Baby Move?</h2>
        <ul>
            <li>Third trimester: several times a day.</li>
            <li>More active after meals, in evening, or at rest.</li>
        </ul>
        <p>If movement suddenly decreases, contact your doctor immediately.</p>

        <div class="action-box">
            <h2>🧘‍♀️ Tips to Notice Baby Movements</h2>
            <ul>
                <li>Lie quietly on your <strong>left side</strong>.</li>
                <li>Have something <strong>cold or sweet</strong> to eat/drink.</li>
                <li>Count movements for <strong>2 hours</strong> — should feel at least <strong>10 movements</strong>.</li>
                <li>If not, <strong>call your healthcare provider</strong>.</li>
            </ul>
        </div>

        <h2>💬 Remember</h2>
        <p>Every pregnancy is different — some babies are naturally more active. Trust your instincts and always get checked if something feels off.</p>

        <h2>🌸 Key Takeaway</h2>
        <p>Fetal movements are your baby’s way of saying, <strong>“I’m growing!”</strong> Tracking them regularly helps ensure your health and your baby’s safety.</p>
    </div>
</div>
    <?php include '15footer.php'; ?>
m
</body>
</html>
