<?php
// --- START: PHP Translation Logic Block ---
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();

// Define translations for the pregnancy page
$translations = [
    'en' => [
        'page_title' => 'My Pregnancy',
        'progress_text_start' => 'of my pregnancy is over',
        'days_text_unit' => 'days',
        'due_date_label' => 'ESTIMATED DUE DATE',
        'grid_week' => 'WEEK',
        'grid_pregnant_for' => "I'VE BEEN PREGNANT FOR",
        'grid_trimester' => 'TRIMESTER',
        'grid_calendar_month' => 'CALENDAR MONTH',
        'grid_pregnancy_month' => 'PREGNANCY MONTH',
        'grid_days_left' => 'DAYS LEFT TO EDD',
        'share_button' => 'SHARE MY PREGNANCY',
        'go_to_fruit' => 'GO TO WEEKLY FRUIT',
        'baby_size_text' => 'YOUR BABY IS NOW THE SIZE OF A POPPY SEED', 
        'info_trimester' => 'Pregnancy is divided into three stages (trimesters).',
        'info_calendar_month' => 'Calendar months are standard months. Pregnancy lasts slightly over 9 calendar months.',
        'info_pregnancy_month' => 'Pregnancy months are calculated based on 4 weeks (28 days). Pregnancy lasts 10 pregnancy months.',
    ],
    'hi' => [
        'page_title' => 'मेरी गर्भावस्था',
        'progress_text_start' => 'मेरी गर्भावस्था पूरी हुई',
        'days_text_unit' => 'दिनों',
        'due_date_label' => 'अनुमानित प्रसव तिथि (EDD)',
        'grid_week' => 'सप्ताह',
        'grid_pregnant_for' => 'मुझे गर्भवती हुए',
        'grid_trimester' => 'तिमाही',
        'grid_calendar_month' => 'कैलेंडर माह',
        'grid_pregnancy_month' => 'गर्भावस्था माह',
        'grid_days_left' => 'प्रसव के लिए बचे हुए दिन',
        'share_button' => 'मेरी गर्भावस्था साझा करें',
        'go_to_fruit' => 'साप्ताहिक फल पर जाएँ',
        'baby_size_text' => 'आपका शिशु अब खसखस ​​के बीज के आकार का है',
        'info_trimester' => 'गर्भावस्था को तीन चरणों (तिमाही) में बांटा गया है।',
        'info_calendar_month' => 'कैलेंडर माह सामान्य महीने होते हैं। गर्भावस्था 9 कैलेंडर माह से अधिक चलती है।',
        'info_pregnancy_month' => 'गर्भावस्था माह 4 सप्ताह (28 दिन) के आधार पर गणना किए जाते हैं। गर्भावस्था 10 गर्भावस्था माह तक चलती है।',
    ],
    'gu' => [
        'page_title' => 'મારી ગર્ભાવસ્થા',
        'progress_text_start' => 'મારી ગર્ભાવસ્થા પૂર્ણ થઈ',
        'days_text_unit' => 'દિવસોમાંથી',
        'due_date_label' => 'અંદાજિત ડિલિવરી તારીખ (EDD)',
        'grid_week' => 'અઠવાડિયું',
        'grid_pregnant_for' => 'હું ગર્ભવતી છું',
        'grid_trimester' => 'ત્રિમાસિક',
        'grid_calendar_month' => 'કેલેન્ડર મહિનો',
        'grid_pregnancy_month' => 'ગર્ભાવસ્થાનો મહિનો',
        'grid_days_left' => 'EDD માટે બાકી દિવસો',
        'share_button' => 'મારી ગર્ભાવસ્થા શેર કરો',
        'go_to_fruit' => 'સાપ્તાહિક ફળ પર જાઓ',
        'baby_size_text' => 'તમારું બાળક હવે ખસખસના બીજ જેટલું મોટું છે',
        'info_trimester' => 'ગર્ભાવસ્થાને ત્રણ તબક્કામાં (ત્રિમાસિક) વહેંચવામાં આવે છે.',
        'info_calendar_month' => 'કેલેન્ડર મહિના સામાન્ય મહિના હોય છે. ગર્ભાવસ્થા 9 કેલેન્ડર મહિનાથી વધુ ચાલે છે.',
        'info_pregnancy_month' => 'ગર્ભાવસ્થાના મહિનાની ગણતરી 4 અઠવાડિયા (28 દિવસ)ના આધારે કરવામાં આવે છે. ગર્ભાવસ્થા 10 ગર્ભાવસ્થાના મહિના સુધી ચાલે છે.',
    ]
];

// Determine the current language
$allowed_langs = array_keys($translations);
$current_lang = 'en'; // Default language

// Check for language parameter in URL
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $current_lang = $_GET['lang'];
}

// Translation function
function t($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
}
// --- END: PHP Translation Logic Block ---


// PHP logic: Fetch and calculate pregnancy data (dynamic)
$page_title = t('page_title'); // Now uses translation
$pregnancy_progress_percent = 0;
$days_over = 0;
$total_days = 280;
$due_date = null;
$week = 0;
$days_pregnant = '0w + 0d';
$trimester = 0;
$calendar_month = 0;
$pregnancy_month = 0;
$days_left = 0;
$baby_size_text = t('baby_size_text');

if ($user) {
    $stmt = $pdo->prepare('SELECT edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $p = $stmt->fetch();
    if ($p && !empty($p['edd'])) {
        $due_date = $p['edd'];
        $today = new DateTimeImmutable('today');
        $due = DateTimeImmutable::createFromFormat('Y-m-d', $due_date);
        if ($due) {
            $interval = $today->diff($due);
            $days_left = (int)$interval->format('%r%a');
            $days_done = max(0, $total_days - max(0, $days_left));
            $pregnancy_progress_percent = round(($days_done / $total_days) * 100);
            $weeks_done = floor($days_done / 7);
            $week = max(0, $weeks_done);
            $days_pregnant = $weeks_done . 'w + ' . ($days_done % 7) . 'd';
            $trimester = (int)floor($weeks_done / 13) + 1;
            $pregnancy_month = (int)ceil(($weeks_done * 7) / 28);
            $days_over = $days_done; // display correct completed days
            // Format EDD for display like other pages (d/m/Y)
            $due_date = $due->format('d/m/Y');
        }
    }
}

// Helper function to generate heart icons
function generate_hearts() {
    $html = '';
    $total_hearts = 10;
    for ($i = 0; $i < $total_hearts; $i++) {
        $html .= "<i class='bx bx-heart'></i>"; 
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* --- Color Palette from App Image --- */
        :root {
            --dark-pink-bg: #E3B0BA; 
            --heart-pink: #FEE7E9; 
            --header-icon-color: #333;
            --text-dark: #333;
            --text-light: #555;
            --share-button-bg: #FFFFFF;
            --share-button-border: #ccc;
            --info-icon-color: #555;
            --grid-border-color: rgba(255, 255, 255, 0.7); 
            --side-padding: 25px;
            --max-content-width: 800px;
        }

        /* --- Base & FULL DESKTOP COVERAGE --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark-pink-bg); 
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            width: 100vw; 
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
        }

        .container {
            width: 100%; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center; 
            background-color: var(--dark-pink-bg); 
            position: relative; 
        }
        
        .centered-content {
            width: 100%;
            max-width: var(--max-content-width);
            box-sizing: border-box; 
            padding: 0 var(--side-padding); 
        }
        
        /* --- Corner Buttons (Absolute Positioning) --- */
        .close-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--header-icon-color); 
            z-index: 10;
        }
        
        .header-icons {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.3rem;
            color: var(--header-icon-color);
            z-index: 10;
        }
        
        .header-icons i {
            margin-left: 10px;
            cursor: pointer;
        }

        /* --- Header Section (Only Title is Centered) --- */
        .header {
            padding: 20px 0; 
            width: 100%; 
            text-align: center;
        }
        
        .header h1 {
            font-size: 1.2rem;
            font-weight: 500;
            margin: 0;
            display: inline-block; 
        }
        
        /* --- Main Progress Area --- */
        .progress-area {
            text-align: center;
            padding: 20px 0;
        }

        .progress-percent {
            font-size: 5rem;
            font-weight: 300;
            line-height: 1;
        }

        .progress-text {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 25px;
        }

        /* --- HEART ICONS STYLES --- */
        .heart-icons i {
            font-size: 1.4rem;
            color: var(--heart-pink); 
            margin: 0 2px;
        }

        .due-date-section {
            margin-top: 40px;
            margin-bottom: 30px;
        }
        
        .due-date-label {
            font-size: 0.7rem;
            color: var(--text-light);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .due-date-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* --- Data Grid --- */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 1px solid var(--grid-border-color); 
            margin-bottom: 20px; 
        }

        .grid-item {
            padding: 15px 5px;
            text-align: center;
            position: relative;
            border-right: 1px solid var(--grid-border-color);
            border-bottom: 1px solid var(--grid-border-color);
        }
        
        .grid-item:nth-child(3n) {
            border-right: none;
        }

        .data-grid > .grid-item:nth-last-child(-n+3) { 
            border-bottom: none;
        }
        
        .grid-item-label {
            font-size: 0.7rem;
            color: var(--text-light);
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: 500;
            padding-right: 20px; 
        }
        
        .grid-item-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }
        
        .info-icon {
            position: absolute;
            top: 5px; 
            right: 5px; 
            font-size: 0.9rem;
            color: var(--info-icon-color);
            cursor: pointer;
            width: 20px; 
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        /* --- Share Button --- */
        .share-button {
            width: 100%;
            padding: 18px 0;
            margin: 40px 0;
            border: 1px solid var(--share-button-border);
            border-radius: 30px;
            background-color: var(--share-button-bg);
            color: var(--text-dark); 
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            
            display: flex; 
            justify-content: center;
            align-items: center;
        }
        
        .share-button i {
            margin-right: 8px; 
            font-size: 1.2rem; 
        }

        /* --- Bottom Section (Baby Size & Go To Fruit) --- */
        .bottom-section {
            text-align: center;
            padding-bottom: 40px;
        }

        .baby-size-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .baby-fruit-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white; 
            border-radius: 50%;
            border: 1px solid #ccc;
        }
        
        .baby-fruit-icon i {
            font-size: 2rem;
            color: #718c6f;
        }

        .go-to-fruit-link {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        /* MEDIA QUERY: Adjust positioning for mobile */
        @media (max-width: 600px) {
            .close-button {
                left: 10px; 
            }
            .header-icons {
                right: 10px;
            }
        }

    </style>
    <script>
        // Get the current language from the PHP variable
        const currentLang = "<?php echo $current_lang; ?>";

        // PHP translations for the JavaScript alerts (for demo purposes)
        const infoTranslations = {
            'Trimester': {
                'en': '<?php echo t('info_trimester'); ?>',
                'hi': '<?php echo t('info_trimester'); ?>',
                'gu': '<?php echo t('info_trimester'); ?>'
            },
            'Calendar Month': {
                'en': '<?php echo t('info_calendar_month'); ?>',
                'hi': '<?php echo t('info_calendar_month'); ?>',
                'gu': '<?php echo t('info_calendar_month'); ?>'
            },
            'Pregnancy Month': {
                'en': '<?php echo t('info_pregnancy_month'); ?>',
                'hi': '<?php echo t('info_pregnancy_month'); ?>',
                'gu': '<?php echo t('info_pregnancy_month'); ?>'
            }
        };

        // Close button: Navigates to 5index.php, passing the current language
        function goBackToMainPage() {
            // Ensures the language state is maintained when going back
            window.location.href = '5index.php?lang=' + currentLang;
        }

        function sharePregnancy() {
            alert('Opening sharing options for current week...');
        }
        
        function showInfo(itemKey) {
            // Retrieves the correct translated info text
            const infoText = infoTranslations[itemKey][currentLang] || infoTranslations[itemKey]['en'];
            alert(infoText);
        }
    </script>
</head>
<body>

<div class="container">
    
    <span class="close-button" onclick="goBackToMainPage()"><i class='bx bx-x'></i></span>
    <div class="centered-content">
        
        <div class="header">
            <h1><?php echo $page_title; ?></h1>
        </div>
        
        <div class="progress-area">
            <div class="progress-percent"><?php echo $pregnancy_progress_percent; ?>%</div>
            <div class="progress-text">
                <?php echo $days_over; ?> <?php echo t('days_text_unit'); ?> <?php echo t('progress_text_start'); ?>
                (<?php echo $days_over; ?> of <?php echo $total_days; ?> <?php echo t('days_text_unit'); ?>)
            </div>
            
            <div class="heart-icons">
                <?php echo generate_hearts(); ?>
            </div>

            <div class="due-date-section">
                <div class="due-date-label"><?php echo t('due_date_label'); ?></div>
                <div class="due-date-value"><?php echo $due_date; ?></div>
            </div>
        </div>

        <div class="data-grid">
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_week'); ?></div>
                <div class="grid-item-value"><?php echo $week; ?></div>
            </div>
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_pregnant_for'); ?></div>
                <div class="grid-item-value"><?php echo $days_pregnant; ?></div>
            </div>
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_trimester'); ?></div>
                <div class="grid-item-value"><?php echo $trimester; ?></div>
                <i class='bx bx-info-circle info-icon' onclick="showInfo('Trimester')"></i>
            </div>
            
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_calendar_month'); ?></div>
                <div class="grid-item-value"><?php echo $calendar_month; ?></div>
                <i class='bx bx-info-circle info-icon' onclick="showInfo('Calendar Month')"></i>
            </div>
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_pregnancy_month'); ?></div>
                <div class="grid-item-value"><?php echo $pregnancy_month; ?></div>
                <i class='bx bx-info-circle info-icon' onclick="showInfo('Pregnancy Month')"></i>
            </div>
            <div class="grid-item">
                <div class="grid-item-label"><?php echo t('grid_days_left'); ?></div>
                <div class="grid-item-value"><?php echo $days_left; ?></div>
            </div>
        </div>

        <button class="share-button" onclick="sharePregnancy()">
            <i class='bx bx-share'></i> 
            <?php echo t('share_button'); ?>
        </button>

        <div class="bottom-section">
            <div class="baby-size-text"><?php echo $baby_size_text; ?></div>
            <div class="baby-fruit-icon">
                <i class='bx bxs-leaf'></i> 
            </div>
            <a href="weekly_fruit.php?lang=<?php echo $current_lang; ?>" class="go-to-fruit-link"><?php echo t('go_to_fruit'); ?></a>
        </div>
        
    </div>
</div>

</body>
</html>