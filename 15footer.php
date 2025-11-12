<?php
// PHP LOGIC: TRANSLATION AND LANGUAGE SELECTION
// This code assumes you have a translation setup in your main files.
// It includes a fallback translation structure for robust functionality.

// --- START Translation Setup ---

// Define translations for the footer items
$translations = [
    'en' => [
        'brand_name' => 'JANANI', // Brand Name
        'what_if_afraid' => 'What can I do if I am afraid to give birth?',
        'about_us' => 'About us',
        'our_experts' => 'Our experts',
        'faq' => 'FAQ',
        'contact' => 'Contact',
    ],
    // Add more languages like Hindi ('hi'), Gujarati ('gu') if needed
];

// Determine the current language (Default is 'en')
$allowed_langs = array_keys($translations);
$current_lang = $_GET['lang'] ?? 'en';
if (!in_array($current_lang, $allowed_langs)) {
    $current_lang = 'en';
}

// ✅ Prevent “Cannot redeclare t()” error
if (!function_exists('t')) {
    function t($key) {
        global $translations, $current_lang;
        return $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
    }
}

// Allowed languages for the dropdown and their labels
$lang_options = [
    'en' => 'EN',
    'hi' => 'HI',
    'gu' => 'GU',
];

// Define the path to your SRS logo image
$srs_logo_path = 'img/srs_logo.png';
$use_srs_logo = file_exists($srs_logo_path); // Check if the file exists for dynamic display

// App Store & Google Play badge paths
$app_store_img_path = file_exists('img/app_store_badge.svg')
    ? 'img/app_store_badge.svg'
    : 'https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg';

$google_play_img_path = file_exists('img/google_play_badge.png')
    ? 'img/google_play_badge.png'
    : 'https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png';

// --- END Translation Setup ---
?>

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
/* --- General Reset & Layout --- */
body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* --- Footer --- */
.large-app-footer {
    background-color: #FFC0CB;
    color: #494f2fff;
    padding: 60px 50px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 30px;
    width: 100%;
    box-sizing: border-box;
    line-height: 1.6;
}

.footer-column {
    min-width: 150px;
    max-width: 280px;
    flex-basis: auto;
}

.footer-column:first-child {
    max-width: 350px;
}

.footer-heading {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #2F4F4F;
}

.footer-link,
.footer-brand {
    color: #2F4F4F;
    text-decoration: none;
    display: block;
    margin-bottom: 10px;
    font-size: 1rem;
    transition: color 0.2s;
}

.footer-link:hover {
    color: #000;
}

/* --- JANANI Brand --- */
.janani-brand-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 25px;
}

.srs-logo-placeholder {
    font-size: 1.5rem;
    font-weight: bold;
    color: #2F4F4F;
    margin-bottom: 5px;
}

.srs-logo-img {
    max-width: 100px;
    height: auto;
    display: block;
    margin-bottom: 5px;
}

.footer-brand-text {
    font-size: 1.8rem;
    font-weight: 700;
    text-decoration: none;
    color: #2F4F4F;
    margin-top: 5px;
}

/* --- Social Icons --- */
.social-icons {
    display: flex;
    gap: 15px;
    margin-top: 5px;
}

.social-icons a {
    font-size: 24px;
    color: #2F4F4F;
    transition: color 0.2s;
}

.social-icons a:hover {
    color: #000;
}

/* --- App Download Buttons --- */
.app-download-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 5px;
}

.app-download-buttons img {
    width: 130px;
    height: auto;
}

/* --- Region Dropdown --- */
.region-select-wrapper {
    margin-top: 15px;
    position: relative;
    display: inline-flex;
    align-items: center;
    width: fit-content;
    gap: 8px;
    border: 1px solid #2F4F4F;
    border-radius: 5px;
    padding: 8px 15px;
    cursor: pointer;
    background-color: transparent;
    font-weight: 600;
    font-size: 0.9rem;
}

.region-select-wrapper i {
    font-size: 1.2rem;
}

.language-selector {
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

@media (max-width: 992px) {
    .large-app-footer {
        padding: 50px 30px;
    }
}

@media (max-width: 768px) {
    .large-app-footer {
        flex-direction: column;
        align-items: flex-start;
        padding: 40px 20px;
        gap: 30px;
    }
    .footer-column {
        min-width: 100%;
        max-width: 100%;
    }
}
</style>

<footer class="large-app-footer">
    <div class="footer-column">
        <div class="janani-brand-container">
            <?php if ($use_srs_logo): ?>
                <a href="index.php?lang=<?php echo $current_lang; ?>">
                    <img src="<?php echo htmlspecialchars($srs_logo_path); ?>" alt="SRS Logo" class="srs-logo-img">
                </a>
            <?php endif; ?>

            <a href="index.php?lang=<?php echo $current_lang; ?>" class="footer-brand-text">
                <?php echo t('brand_name'); ?>
            </a>
        </div>
    </div>

    <div class="footer-column">
        <h4 class="footer-heading"><?php echo t('popular_articles'); ?></h4>
        <a href="articles.php?lang=<?php echo $current_lang; ?>" class="footer-link">
            <?php echo t('what_if_afraid'); ?>
        </a>
    </div>

    <div class="footer-column">
        <h4 class="footer-heading"><?php echo t('company'); ?></h4>
        <a href="about_us.php?lang=<?php echo $current_lang; ?>" class="footer-link"><?php echo t('about_us'); ?></a>
        <a href="our_experts.php?lang=<?php echo $current_lang; ?>" class="footer-link"><?php echo t('our_experts'); ?></a>
        <a href="support_faq.php?lang=<?php echo $current_lang; ?>" class="footer-link"><?php echo t('faq'); ?></a>
        <a href="contact_us.php?lang=<?php echo $current_lang; ?>" class="footer-link"><?php echo t('contact'); ?></a>
    </div>

    <div class="footer-column">


        <div class="region-select-wrapper">
            <i class='bx bx-globe'></i>
            <span><?php echo htmlspecialchars($lang_options[$current_lang]); ?></span>
            <select id="language-switcher-footer" class="language-selector" onchange="window.location.href = this.value;">
                <?php foreach ($lang_options as $lang => $label): ?>
                    <option value="?lang=<?php echo htmlspecialchars($lang); ?>" <?php echo ($lang === $current_lang) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</footer>
