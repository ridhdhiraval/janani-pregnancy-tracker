<?php
// --- 1. PHP Translation Logic Block ---

// Define translations for all static texts used on THIS page and common elements.
$translations = [
    'en' => [
        // Common Keys (Used in Top Bar)
        'tab_pregnancy' => 'Pregnancy',
        'tab_child' => 'Child',
        'settings_title' => 'Settings',
        'stats_title' => 'Stats',
        'mail_title' => 'Mailbox',
        'trimester_link' => 'Trimester 1', // Placeholder for link text
        'percent_complete' => 'COMPLETE',
        'days_to_go_label' => 'DAYS TO GO',
        'week_label' => 'Week',
        'days_to_go_small' => '%s days to go', // %s will be replaced by $appData['days_to_go']
        'title' => 'Child Tracker App - Features',

        // Page-Specific Keys (6child.php Content)
        'child_growth_header' => 'Baby Growth Tracking',
        'child_growth_text' => 'Track your baby\'s development milestones and growth.',
        'child_growth_link' => 'VIEW',
        
        'movements_log_header' => 'Daily Activities Log',
        'movements_log_text' => 'Record your baby\'s daily activities and milestones.',
        'movements_log_link' => 'LOG NOW',
        
        'gallery_header' => 'Baby Photo Gallery',
        'gallery_text' => 'Upload and view your baby\'s precious moments.',
        'gallery_link' => 'OPEN',
        
        'bonding_header' => 'Parent-Baby Bonding',
        'bonding_text' => 'Activities to strengthen your bond with your baby.',
        'bonding_link' => 'START',
        
        'health_insights_header' => 'Baby Health & Care',
        'health_insights_text' => 'Tips on baby care, feeding, and health monitoring.',
        'health_insights_link' => 'READ',
        
        'delivery_prep_header' => 'Post-Birth Care',
        'delivery_prep_text' => 'Postpartum care, recovery tips, and newborn essentials.',
        'delivery_prep_link' => 'CHECKLIST',
    ],
    'hi' => [
        // Common Keys
        'tab_pregnancy' => 'गर्भावस्था',
        'tab_child' => 'शिशु',
        'settings_title' => 'सेटिंग्स',
        'stats_title' => 'आँकड़े',
        'mail_title' => 'मेलबॉक्स',
        'trimester_link' => 'पहली तिमाही',
        'percent_complete' => 'पूरा',
        'days_to_go_label' => 'दिन बाकी',
        'week_label' => 'सप्ताह',
        'days_to_go_small' => '%s दिन बाकी',
        'title' => 'शिशु ट्रैकर ऐप - फीचर्स',

        // Page-Specific Keys (6child.php Content)
        'child_growth_header' => 'शिशु की साप्ताहिक वृद्धि',
        'child_growth_text' => 'शिशु का विकास दिखाएं — दिल की धड़कन, वजन, हलचल।',
        'child_growth_link' => 'देखें',
        
        'movements_log_header' => 'शिशु हलचल लॉग',
        'movements_log_text' => 'माँ रिकॉर्ड कर सकती हैं कि उन्हें कब लात या हलचल महसूस होती है।',
        'movements_log_link' => 'अभी लॉग करें',
        
        'gallery_header' => 'अल्ट्रासाઉન્ડ ગેલેરી',
        'gallery_text' => 'शिशु स्कैन तस्वीरें अपलोड करें और देखें।',
        'gallery_link' => 'खोलें',
        
        'bonding_header' => 'बॉन्डिंग गतिविधियाँ',
        'bonding_text' => 'शिशु के लिए संगीत, शिशु से बात करना, नाम के विचार, लोरी।',
        'bonding_link' => 'शुरू करें',
        
        'health_insights_header' => 'शिशु के स्वास्थ्य की जानकारी',
        'health_insights_text' => 'इस सप्ताह शिशु के लिए कौन से पोषक तत्व मदद करते हैं, इसके सुझाव।',
        'health_insights_link' => 'पढ़ें',
        
        'delivery_prep_header' => 'प्रसव तैयारी अनुभाग',
        'delivery_prep_text' => 'पैकिंग सूची, अस्पताल बैग चेकलिस्ट, परिवार के लिए अनुस्मारक।',
        'delivery_prep_link' => 'चेकलिस्ट',
    ],
    'gu' => [
        // ... (Gujarati translations should be added here) ...
        'tab_pregnancy' => 'ગર્ભાવસ્થા',
        'tab_child' => 'બાળક',
        'settings_title' => 'સેટિંગ્સ',
        'stats_title' => 'આંકડા',
        'mail_title' => 'મેઇલબોક્સ',
        'trimester_link' => 'ટ્રાઇમેસ્ટર ૧',
        'percent_complete' => 'પૂર્ણ',
        'days_to_go_label' => 'દિવસ બાકી',
        'week_label' => 'અઠવાડિયું',
        'days_to_go_small' => '%s દિવસ બાકી',
        'title' => 'બાળક ટ્રેકર એપ્લિકેશન - સુવિધાઓ',
        
        'child_growth_header' => 'બાળકની સાપ્તાહિક વૃદ્ધિ',
        'child_growth_text' => 'બાળકનો વિકાસ બતાવો - ધબકારા, વજન, હલનચલન.',
        'child_growth_link' => 'જુઓ',

        'movements_log_header' => 'બાળક હલનચલન લોગ',
        'movements_log_text' => 'માતા કિક અથવા હલનચલન ક્યારે અનુભવે છે તે રેકોર્ડ કરી શકે છે.',
        'movements_log_link' => 'હમણાં લોગ કરો',

        'gallery_header' => 'અલ્ટ્રાસાઉન્ડ ગેલેરી',
        'gallery_text' => 'બાળક સ્કેન ફોટા અપલોડ કરો અને જુઓ.',
        'gallery_link' => 'ખોલો',

        'bonding_header' => 'બોન્ડિંગ પ્રવૃત્તિઓ',
        'bonding_text' => 'બાળક માટે સંગીત, બાળક સાથે વાત કરવી, નામ સૂચનો, લોરીઓ.',
        'bonding_link' => 'શરૂ કરો',

        'health_insights_header' => 'બાળકના સ્વાસ્થ્યની સમજ',
        'health_insights_text' => 'આ અઠવાડિયે બાળકને કયા પોષક તત્વો મદદ કરે છે તેની ટિપ્સ.',
        'health_insights_link' => 'વાંચો',

        'delivery_prep_header' => 'ડિલિવરી તૈયારી વિભાગ',
        'delivery_prep_text' => 'પેકિંગ સૂચિ, હોસ્પિટલ બેગ ચેકલિસ્ટ, પરિવાર માટે રીમાઇન્ડર્સ.',
        'delivery_prep_link' => 'ચેકલિસ્ટ',
    ]
];

// Determine the current language
$allowed_langs = array_keys($translations);
$current_lang = 'en'; // Default language

if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $current_lang = $_GET['lang'];
}

// Translation function
function t($key, ...$args) {
    global $translations, $current_lang;
    $text = $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
    if (!empty($args)) {
        return sprintf($text, ...$args);
    }
    return $text;
}

// PHP structure for child.php page.
$appData = [
    'percent_done' => 0.40, 
    'week_number' => 1,
    'days_to_go' => 279,
    'trimester' => t('trimester_link') // Using translated text now
];

// Load baby data for logged-in user
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
$baby_data = null;
if ($user) {
    // Check if baby data exists
    $stmt = $pdo->prepare('SELECT * FROM babies WHERE user_id = ? ORDER BY dob DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $baby_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($baby_data) {
        // Calculate baby age and tracking data based on birth date
        $birth_date = new DateTimeImmutable($baby_data['dob']);
        $today = new DateTimeImmutable('today');
        $age_interval = $birth_date->diff($today);
        
        // Calculate weeks and days since birth
        $days_since_birth = (int)$age_interval->format('%r%a');
        $weeks_since_birth = floor($days_since_birth / 7);
        $remaining_days = $days_since_birth % 7;
        
        // Update app data for baby tracking
        $appData['week_number'] = $weeks_since_birth;
        $appData['days_to_go'] = $remaining_days;
        $appData['trimester'] = 'Post-Birth';
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo t('title'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        /* ... CSS STYLES REMAINS THE SAME ... */
        :root {
            /* Using the same colors for consistency as index.php */
            --primary-pink: #f8e5e8;
            --secondary-pink: #ffc0cb;
            --dark-pink: #e07f91;
            --text-color: #5d5d5d;
            --white: #ffffff;
            --circle-border: #f0a5af;
            --app-bg: #f5f5f5;
        }
        /* (All other CSS remains here) */
        
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--app-bg);
            min-height: 100vh;
        }
        
        .app-container {
            width: 100%;
            min-height: 100vh;
            background-color: var(--white);
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            padding-bottom: 70px;
        }

        /* Top Bar Styling - Exact Match */
        .top-bar {
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-pink);
            flex-shrink: 0;
        }
        .icon-link {
            width: 28px;
            height: 28px;
            color: var(--dark-pink);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .tab-bar {
            display: inline-flex;
            background-color: var(--dark-pink);
            border-radius: 20px;
            padding: 4px;
        }
        .tab {
            padding: 6px 20px;
            color: var(--white);
            font-size: 16px;
            font-weight: 500;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .tab.active {
            background-color: var(--white);
            color: var(--dark-pink);
        }

        /* WEEK TRACKER SECTION (COPIED FROM INDEX.PHP) */
        .week-tracker {
            text-align: center;
            background-color: var(--primary-pink);
            padding: 40px 20px; 
            position: relative;
            z-index: 10;
            border-bottom-left-radius: 50% 10%;
            border-bottom-right-radius: 50% 10%;
            box-shadow: 0 5px 10px -5px rgba(0, 0, 0, 0.1);
        }

        .week-circle-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px auto;
            gap: 80px; 
            flex-wrap: wrap;
        }

        .stat-left, .stat-right {
            color: var(--dark-pink);
            font-size: 16px;
            text-transform: uppercase;
            line-height: 1.2;
            flex-basis: auto; 
            white-space: nowrap;
        }

        .stat-left strong, .stat-right strong {
            display: block;
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
        }
        
        .week-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 10px solid var(--secondary-pink);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: var(--white);
            box-shadow: 0 0 0 10px var(--white), 0 0 0 13px var(--circle-border);
            color: var(--dark-pink);
            font-size: 18px;
            font-weight: 500;
        }

        .week-number {
            font-size: 90px;
            font-weight: 900;
            line-height: 1;
        }

        .week-days {
            font-size: 14px;
            margin-top: 5px;
        }

        .trimester {
            display: block;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: var(--dark-pink);
            text-decoration: none;
            border-bottom: 2px solid var(--dark-pink);
            width: fit-content;
            margin-inline: auto;
        }

        /* INFO CARD STYLING */
        .main-content {
            padding: 20px 0; 
            max-width: 1200px;
            margin: 0 auto;
        }
        .info-card-base {
            background-color: var(--white);
            padding: 20px 25px;
            border-radius: 20px;
            color: var(--text-color);
            margin-bottom: 20px; 
            width: 90%;
            max-width: 1100px;
            margin-inline: auto;
            display: flex; 
            align-items: center; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--primary-pink); 
            cursor: pointer;
            transition: transform 0.1s;
        }
        .info-card-base:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card-header-small {
            font-size: 18px;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 2px;
        }
        .card-text-small {
            font-size: 14px;
            color: var(--text-color);
            line-height: 1.3;
        }
        .card-action-link {
            font-size: 14px;
            font-weight: bold;
            color: var(--dark-pink);
            flex-shrink: 0;
            margin-left: auto;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="app-container">
        
        <script>
            // Populate Week Tracker Data for Baby
            document.addEventListener('DOMContentLoaded', () => {
                <?php if ($baby_data): ?>
                document.getElementById('week-number').textContent = '<?php echo $appData['week_number']; ?>';
                document.getElementById('week-days-count').textContent = 'Weeks + <?php echo $appData['days_to_go']; ?> days';
                <?php endif; ?>
            });
        </script>

        <div class="top-bar">
            <a href="settings.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('settings_title'); ?>">
                </a>

            <div class="tab-bar">
                <a href="5index.php?lang=<?php echo $current_lang; ?>" class="tab"><?php echo t('tab_pregnancy'); ?></a>
                <a href="child.php?lang=<?php echo $current_lang; ?>" class="tab active"><?php echo t('tab_child'); ?></a> 
            </div>
            
            <div style="display: flex; gap: 10px;">
                <a href="stats.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('stats_title'); ?>">
                    </a>
                <a href="mail.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('mail_title'); ?>">
                    </a>
            </div>
        </div>

        <div class="week-tracker">
            <?php if ($baby_data): ?>
            <div class="week-circle-container">
                <div class="week-circle">
                    Age
                    <div class="week-number" id="week-number"><?php echo $appData['week_number']; ?></div>
                    <div class="week-days" id="week-days-count">Weeks + <?php echo $appData['days_to_go']; ?> days</div>
                </div>
                <div class="stat-right">
                    <strong><?php echo htmlspecialchars($baby_data['name']); ?></strong>
                    <small><?php echo htmlspecialchars($baby_data['sex']); ?></small>
                </div>
            </div>
            <div class="trimester">Born: <?php echo date('M d, Y', strtotime($baby_data['dob'])); ?></div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; color: var(--dark-pink);">
                <h3>No Baby Data Found</h3>
                <p>Please record your baby's delivery details first.</p>
                <a href="baby_delivery.php?lang=<?php echo $current_lang; ?>" class="trimester">Add Baby Details</a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="main-content-wrapper">
            <div class="main-content" style="padding-top: 20px;">
                
                <div class="info-card-base" onclick="location.href='growth.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('child_growth_header'); ?></div>
                        <div class="card-text-small"><?php echo t('child_growth_text'); ?></div>
                    </div>
                    <a href="growth.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('child_growth_link'); ?></a>
                </div>

                <div class="info-card-base" onclick="location.href='movements.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('movements_log_header'); ?></div>
                        <div class="card-text-small"><?php echo t('movements_log_text'); ?></div>
                    </div>
                    <a href="movements.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('movements_log_link'); ?></a>
                </div>

                <div class="info-card-base" onclick="location.href='gallery.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('gallery_header'); ?></div>
                        <div class="card-text-small"><?php echo t('gallery_text'); ?></div>
                    </div>
                    <a href="gallery.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('gallery_link'); ?></a>
                </div>

                <div class="info-card-base" onclick="location.href='bonding.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('bonding_header'); ?></div>
                        <div class="card-text-small"><?php echo t('bonding_text'); ?></div>
                    </div>
                    <a href="bonding.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('bonding_link'); ?></a>
                </div>

                <div class="info-card-base" onclick="location.href='health-insights.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('health_insights_header'); ?></div>
                        <div class="card-text-small"><?php echo t('health_insights_text'); ?></div>
                    </div>
                    <a href="health-insights.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('health_insights_link'); ?></a>
                </div>

                <div class="info-card-base" onclick="location.href='delivery-prep.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('delivery_prep_header'); ?></div>
                        <div class="card-text-small"><?php echo t('delivery_prep_text'); ?></div>
                    </div>
                    <a href="delivery-prep.php?lang=<?php echo $current_lang; ?>" class="card-action-link"><?php echo t('delivery_prep_link'); ?></a>
                </div>

            </div>
        </div>
    </div>
        <?php include '15footer.php'; ?>

</body>
</html>