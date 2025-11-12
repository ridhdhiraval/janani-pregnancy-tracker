<?php
// --- 1. Translation Data and Logic ---

// Define translations for all static texts
$translations = [
    'en' => [
        'title' => 'Pregnancy Tracker App - Full Desktop View',
        'tab_pregnancy' => 'Pregnancy',
        'tab_child' => 'Child',
        'complete' => 'COMPLETE',
        'days_to_go_caps' => 'DAYS TO GO', // Capitalized for STATS section
        'trimester' => 'Trimester 1',
        'header_whats_happening' => "What's happening this week?",
        'topic_baby' => 'BABY',
        'topic_mother' => 'MOTHER',
        'topic_partner' => 'PARTNER',
        'card_header_size' => 'MY BABBY\'S SIZE',
        'card_text_size' => 'Find out your baby\'s size by clicking the box.',
        'card_link_open' => 'OPEN NOW',
        'tools_button' => 'Tap here to find your tools',
        'card_header_checklist' => 'Checklist',
        'card_text_checklist' => '%s%% of your checklist is completed', // %s is placeholder
        'card_header_articles' => 'Articles',
        'card_text_articles' => 'Find weekly articles about pregnancy, baby and mother health.',
        'card_link_read' => 'READ NOW',
        'days_to_go_small' => 'days to go', // For the circle text
        'week' => 'Week',
        'alert_title' => 'Open Alerts',
        'settings_title' => 'Open Settings',
        'stats_title' => 'View Charts and Statistics',
        'mail_title' => 'Open Messages/Mailbox',
    ],
    'hi' => [
        'title' => 'गर्भावस्था ट्रैकर ऐप - पूर्ण डेस्कटॉप दृश्य',
        'tab_pregnancy' => 'गर्भावस्था',
        'tab_child' => 'शिशु',
        'complete' => 'पूर्ण',
        'days_to_go_caps' => 'दिन शेष',
        'trimester' => 'पहली तिमाही',
        'header_whats_happening' => 'इस सप्ताह क्या हो रहा है?',
        'topic_baby' => 'शिशु',
        'topic_mother' => 'माँ',
        'topic_partner' => 'साथी',
        'card_header_size' => 'मेरे शिशु का आकार',
        'card_text_size' => 'बॉक्स पर क्लिक करके अपने शिशु का आकार पता करें।',
        'card_link_open' => 'अभी खोलें',
        'tools_button' => 'अपने उपकरण खोजने के लिए यहाँ टैप करें',
        'card_header_checklist' => 'चेकलिस्ट',
        'card_text_checklist' => 'आपकी चेकलिस्ट का %s%% पूरा हो चुका है',
        'card_header_articles' => 'लेख',
        'card_text_articles' => 'गर्भावस्था, शिशु और माँ के स्वास्थ्य के बारे में साप्ताहिक लेख खोजें।',
        'card_link_read' => 'अभी पढ़ें',
        'days_to_go_small' => 'दिन शेष',
        'week' => 'सप्ताह',
        'alert_title' => 'चेतावनी खोलें',
        'settings_title' => 'सेटिंग्स खोलें',
        'stats_title' => 'चार्ट और आँकड़े देखें',
        'mail_title' => 'संदेश/मेलबॉक्स खोलें',
    ],
    'gu' => [
        'title' => 'ગર્ભાવસ્થા ટ્રેકર એપ્લિકેશન - સંપૂર્ણ ડેસ્કટોપ દૃશ્ય',
        'tab_pregnancy' => 'ગર્ભાવસ્થા',
        'tab_child' => 'બાળક',
        'complete' => 'સંપૂર્ણ',
        'days_to_go_caps' => 'દિવસ બાકી',
        'trimester' => 'પ્રથમ ત્રિમાસિક',
        'header_whats_happening' => 'આ અઠવાડિયે શું થઈ રહ્યું છે?',
        'topic_baby' => 'બાળક',
        'topic_mother' => 'માતા',
        'topic_partner' => 'સાથી',
        'card_header_size' => 'મારા બાળકની સાઇઝ',
        'card_text_size' => 'બોક્સ પર ક્લિક કરીને તમારા બાળકની સાઇઝ જાણો.',
        'card_link_open' => 'હવે ખોલો',
        'tools_button' => 'તમારા ટૂલ્સ શોધવા માટે અહીં ટેપ કરો',
        'card_header_checklist' => 'ચેકલિસ્ટ',
        'card_text_checklist' => 'તમારી ચેકલિસ્ટનું %s%% પૂર્ણ થઈ ગયું છે',
        'card_header_articles' => 'લેખો',
        'card_text_articles' => 'ગર્ભાવસ્થા, બાળક અને માતાના સ્વાસ્થ્ય વિશે સાપ્તાહિક લેખો શોધો.',
        'card_link_read' => 'હવે વાંચો',
        'days_to_go_small' => 'દિવસ બાકી',
        'week' => 'અઠવાડયું',
        'alert_title' => 'ચેતવણી ખોલો',
        'settings_title' => 'સેટિંગ્સ ખોલો',
        'stats_title' => 'ચાર્ટ્સ અને આંકડા જુઓ',
        'mail_title' => 'સંદેશાઓ/મેઇલબોક્સ ખોલો',
    ]
];

// Determine the current language
$allowed_langs = array_keys($translations);
$current_lang = 'en'; // Default language

// Check for language parameter in URL
if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $current_lang = $_GET['lang'];
}

// Translation function: looks up the key in the current language, falls back to English, or uses the key itself.
function t($key, ...$args) {
    global $translations, $current_lang;
    $text = $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
    if (!empty($args)) {
        return sprintf($text, ...$args);
    }
    return $text;
}

// --- 2. Application Data (Dynamic: compute from DB if user logged in) ---
$appData = [
    'week_number' => 0,
    'days_to_go' => 280,
    'percent_done' => 0,
    'checklist_percent' => 0,
    'progress_width' => 0,
];

// Try to load pregnancy data for logged-in user and compute metrics
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();

if ($user) {
    // fetch most recent active pregnancy for user
    $stmt = $pdo->prepare('SELECT id, edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $p = $stmt->fetch();
    if ($p && !empty($p['edd'])) {
        $edd = $p['edd'];
        $today = new DateTimeImmutable('today');
        $dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $edd);
        if ($dueDate) {
            $interval = $today->diff($dueDate);
            $days_to_go = (int)$interval->format('%r%a');
            $total_days = 280;
            $days_done = max(0, $total_days - max(0, $days_to_go));
            $percent_done = (int)round(($days_done / $total_days) * 100);
            $weeks_done = floor($days_done / 7);

            $appData['week_number'] = max(0, $weeks_done);
            $appData['days_to_go'] = $days_to_go;
            $appData['percent_done'] = $percent_done;
            // progress width for UI (0-100)
            $appData['progress_width'] = max(0, min(100, $percent_done));

            $current_trimester = 0;
            if ($weeks_done <= 12) {
                $current_trimester = 1;
            } elseif ($weeks_done <= 27) {
                $current_trimester = 2;
            } else {
                $current_trimester = 3;
            }

            // Fetch checklist completion for this user, up to the current trimester
            // Handle cases where checklist_items table might not exist yet
            try {
                $tableCheck = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
                $hasChecklistTable = $tableCheck && $tableCheck->rowCount() > 0;

                if ($hasChecklistTable) {
                    $stmt_checklist = $pdo->prepare('SELECT COUNT(*) as total, SUM(is_completed) as completed FROM checklist_items WHERE user_id = ? AND trimester <= ?');
                    $stmt_checklist->execute([$user['id'], $current_trimester]);
                    $checklist_stats = $stmt_checklist->fetch();
                    if ($checklist_stats && $checklist_stats['total'] > 0) {
                        $appData['checklist_percent'] = (int)round((($checklist_stats['completed'] ?? 0) / $checklist_stats['total']) * 100);
                    } else {
                        $appData['checklist_percent'] = 0;
                    }
                } else {
                    // Table not present; default to 0%
                    $appData['checklist_percent'] = 0;
                }
            } catch (Exception $e) {
                // Any DB error should not break the page; default safely
                $appData['checklist_percent'] = 0;
            }
        }
    }

    // Fetch baby size information based on the current week
    $week_number = $appData['week_number'];
    require_once 'baby_growth_util.php'; // This will provide the get_fruit_image_src function
    $baby_size_image = get_fruit_image_src_by_week($week_number);
}

// PHP to generate language options for the dropdown
$lang_options = '';
foreach ($translations as $code => $data) {
    $selected = ($code == $current_lang) ? 'selected' : '';
    $label = strtoupper($code);
    if ($code == 'hi') $label = 'Hindi (हिंदी)';
    if ($code == 'en') $label = 'English';
    if ($code == 'gu') $label = 'Gujarati (ગુજરાતી)';

    $lang_options .= "<option value=\"$code\" $selected>$label</option>";
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
        /* Define the primary colors */
        :root {
            --primary-pink: #f8e5e8; /* Light background pink (Week Tracker) */
            --secondary-pink: #ffc0cb; /* Medium pink for accents/dividers */
            --dark-pink: #e07f91; /* Darker pink for text/buttons */
            --text-color: #5d5d5d; /* Dark gray for general text */
            --white: #ffffff;
            --circle-border: #f0a5af; /* Border for the main circle */
            --app-bg: #f5f5f5; /* Light grey background for the page */
        }

        /* Reset + Body */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--app-bg);
            min-height: 100vh;
        }

        /* Full screen container (Desktop size) */
        .app-container {
            width: 100%;
            min-height: 100vh;
            background-color: var(--white);
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            padding-bottom: 70px;
        }

        /* Top bar */
         .top-bar {
         padding: 15px 40px;
         display: flex;
         justify-content: space-between;
         align-items: center;
         background-color: var(--primary-pink);
         flex-shrink: 0;
         position: relative; /* <--- ADDED: Needed for absolute positioning of the tab bar */
        }

        /* Group for Left Icons (Settings + Language) */
        .top-bar .left-icons-group {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between icons/dropdown */
        }
        
        /* Language Dropdown Styling */
        .language-dropdown-container {
            display: flex; 
            align-items: center;
        }
        
        .language-dropdown-container select {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid var(--secondary-pink);
            background-color: var(--primary-pink);
            color: var(--dark-pink);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            outline: none;
        }
        /* End Language Dropdown Styling */
        
        /* Icon Styling */
        .top-bar .icon-link {
            width: 28px;
            height: 28px;
            color: var(--dark-pink);
            cursor: pointer;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .top-bar .icon-link:hover {
            color: #b05f6f;
        }
        .top-bar .icon-link span {
            display: block; 
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
        }

        .tab.active {
            background-color: var(--white);
            color: var(--dark-pink);
        }

        /* --- WEEK TRACKER SECTION (PINK BACKGROUND) --- */
        .week-tracker {
            text-align: center;
            background-color: var(--primary-pink);
            padding: 30px 20px 40px; 
            position: relative;
            z-index: 10;
            border-bottom-left-radius: 50% 10%;
            border-bottom-right-radius: 50% 10%;
            box-shadow: 0 5px 10px -5px rgba(0, 0, 0, 0.1);
        }

        .week-circle-container {
            display: flex;
            align-items: center;
            margin: 5px auto;
            gap: 80px;
            flex-wrap: wrap;
        }

        .stat-left,
        .stat-right {
            flex-grow: 1;
            text-align: center;
            font-size: 18px;
            color: var(--dark-pink);
            font-weight: 500;
        }

        .stat-left strong,
        .stat-right strong {
            display: block;
            font-size: 40px;
            font-weight: bold;
            line-height: 1;
        }

        .week-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 10px solid var(--secondary-pink);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: var(--white);
            box-shadow: 0 0 0 10px var(--white), 0 0 0 13px var(--circle-border);
            color: var(--dark-pink);
            margin: 0 auto;
            position: relative;
        }

        .week-number {
            font-size: 80px;
            font-weight: bold;
            line-height: 1;
        }

        .week-days {
            font-size: 20px;
            margin-top: 5px;
        }

        .trimester {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark-pink);
            margin-top: 35px; 
            text-decoration: underline;
            display: block; 
            width: fit-content;
            margin-inline: auto;
        }

        /* --- Main Content --- */
        .main-content-wrapper {
            background-color: var(--white);
            position: relative;
            z-index: 20;
            margin-top: 0;
        }
        
        .main-content {
            padding: 40px 60px 100px;
            flex-grow: 1;
            position: relative;
            z-index: 30;
        }

        .content-header {
            font-size: 28px;
            font-weight: bold;
            color: var(--text-color);
            text-align: center;
            margin-bottom: 40px;
        }

        .topic-icons {
            display: flex;
            justify-content: center;
            gap: 100px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .topic-icon-item {
            text-align: center;
            color: var(--dark-pink);
            cursor: pointer; 
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            padding: 10px;
            border-radius: 20px;
            min-width: 140px; 
        }

        .topic-icon-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .topic-icon-item:active {
            transform: scale(0.95);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .topic-icon-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid var(--secondary-pink);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            margin-bottom: 10px;
            background-color: var(--white);
        }
        .topic-icon-circle svg {
            width: 50px;
            height: 50px;
        }


        .topic-icon-label {
            font-size: 18px;
            font-weight: bold;
            color: var(--text-color);
        }

        /* Enlarged Action Card */
        .action-card {
            background-color: var(--primary-pink);
            padding: 40px 50px;
            border-radius: 20px;
            color: var(--dark-pink);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            width: 90%;
            max-width: 1100px;
            margin-inline: auto;
            
            /* ADDED Doodles/Illustration Background */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 150 150'%3E%3Cpath d='M40 70 A30 30 0 0 1 110 70 L75 110 Z' fill='none' stroke='%23ffccd6' stroke-width='2' opacity='0.7'/%3E%3Ccircle cx='75' cy='75' r='5' fill='%23ffccd6' opacity='0.4'/%3E%3C/svg%3E");
            background-size: 150px 150px;
            background-repeat: repeat;
            background-position: center;
        }

        .card-header {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .card-text {
            font-size: 20px;
            margin-bottom: 20px;
            line-height: 1.5;
            max-width: 80%;
        }

        .continue-link {
            font-size: 22px;
            font-weight: bold;
            color: var(--dark-pink);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .continue-link::after {
            content: ">";
            margin-left: 10px;
            font-size: 24px;
        }

        .card-illustration {
            position: absolute;
            top: 15px;
            right: 40px;
            font-size: 100px;
            opacity: 0.3;
            color: var(--dark-pink);
        }
        .card-illustration svg {
            width: 100px;
            height: 100px;
        }
        
        /* --- Checklist/Article Card Styling (Common base) --- */
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

        .card-icon-container {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            margin-right: 15px;
            background-color: var(--primary-pink); 
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .card-content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding-right: 15px; 
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

        /* Specific Checklist Styling */
        .checklist-card .card-content-wrapper {
            padding-bottom: 0px; 
        }
        .progress-container {
            width: 100%;
            height: 4px;
            background-color: #f0f0f0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px; 
        }

        .progress-fill {
            height: 100%;
            width: 0%; 
            background-color: var(--dark-pink);
            border-radius: 2px;
            transition: width 0.5s ease;
        }
        
        .checklist-progress-text {
            font-size: 14px;
            font-weight: bold;
            color: var(--dark-pink);
            align-self: flex-end; 
            flex-shrink: 0;
            margin-left: auto;
        }
        /* End Specific Checklist Styling */
        
        /* Specific Article Styling */
        .article-link-text {
            font-size: 14px;
            font-weight: bold;
            color: var(--dark-pink);
            align-self: flex-end; 
            flex-shrink: 0;
            margin-left: auto;
        }


        /* Tools button */
        .tools-button {
            background-color: var(--secondary-pink);
            padding: 25px 30px;
            border-radius: 12px;
            font-size: 22px;
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 1100px;
            margin: 20px auto 40px; 
            transition: background-color 0.2s;
        }
        .tools-button:hover {
            background-color: #f7b3c0; 
        }
        
        .tools-button-text {
            line-height: 1; 
        }

        .tools-button-icon {
            display: flex;
            align-items: center;
            color: var(--dark-pink); 
        }

        .tools-button-icon svg {
            width: 28px;
            height: 28px;
        }

    </style>
</head>
<body>
    <div class="app-container">
        
        <script>
            // --- Icon Definitions (SVG Paths/Images) ---
            const ICON_SVGS = {
                settings: '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0,0,256,256"><g fill="#fc8eac" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M9.66602,2l-0.49023,2.52344c-0.82417,0.31082 -1.58099,0.74649 -2.24414,1.29102l-2.42383,-0.83594l-2.33594,4.04297l1.94141,1.6875c-0.07463,0.45823 -0.11328,0.88286 -0.11328,1.29102c0,0.40877 0.03981,0.83263 0.11328,1.29102v0.00195l-1.94141,1.6875l2.33594,4.04102l2.42188,-0.83398c0.66321,0.54482 1.42175,0.97807 2.24609,1.28906l0.49023,2.52344h4.66797l0.49024,-2.52344c0.82471,-0.31102 1.58068,-0.74599 2.24414,-1.29102l2.42383,0.83594l2.33398,-4.04102l-1.93945,-1.68945c0.07463,-0.45823 0.11328,-0.88286 0.11328,-1.29102c0,-0.40754 -0.03887,-0.83163 -0.11328,-1.28906v-0.00195l1.94141,-1.68945l-2.33594,-4.04102l-2.42188,0.83398c-0.66321,-0.54482 -1.42175,-0.97807 -2.24609,-1.28906l-0.49024,-2.52344zM11.31445,4h1.37109l0.38867,2l1.04297,0.39453c0.62866,0.23694 1.19348,0.56222 1.68359,0.96484l0.86328,0.70703l1.92188,-0.66016l0.68555,1.18555l-1.53516,1.33594l0.17578,1.09961v0.00195c0.06115,0.37494 0.08789,0.68947 0.08789,0.9707c0,0.28123 -0.02674,0.59572 -0.08789,0.9707l-0.17773,1.09961l1.53516,1.33594l-0.68555,1.1875l-1.91992,-0.66211l-0.86523,0.70898c-0.49011,0.40262 -1.05298,0.7279 -1.68164,0.96484h-0.00195l-1.04297,0.39453l-0.38867,2h-1.36914l-0.38867,-2l-1.04297,-0.39453c-0.62867,-0.23694 -1.19348,-0.56222 -1.68359,-0.96484l-0.86328,-0.70703l-1.92187,0.66016l-0.68555,-1.18555l1.53711,-1.33789l-0.17773,-1.0957v-0.00195c-0.06027,-0.37657 -0.08789,-0.69198 -0.08789,-0.97266c0,-0.28123 0.02674,-0.59572 0.08789,-0.9707l0.17773,-1.09961l-1.53711,-1.33594l0.68555,-1.1875l1.92188,0.66211l0.86328,-0.70898c0.49011,-0.40262 1.05493,-0.7279 1.68359,-0.96484l1.04297,-0.39453zM12,8c-2.19652,0 -4,1.80348 -4,4c0,2.19652 1.80348,4 4,4c2.19652,0 4,-1.80348 4,-4c0,-2.19652 -1.80348,-4 -4,-4zM12,10c1.11148,0 2,0.88852 2,2c0,1.11148 -0.88852,2 -2,2c-1.11148,0 -2,-0.88852 -2,-2c0,-1.11148 0.88852,-2 2,-2z"></path></g></g></svg>',
                alert:'<img width="34" height="34" src="https://img.icons8.com/pastel-glyph/64/e07f91/loudspeaker--v2.png" alt="loudspeaker--v2"/>',
                stats: '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>',
                user: '<img width="35" height="35" src="https://img.icons8.com/pulsar-line/48/e07f91/login-rounded-right.png" alt="login-rounded-right"/>',
                profile: '<img src="images/profile-icon.png" alt="Profile" width="35" height="35">',
                
                // Topic Icons (Main Content)
                baby: '<img src="https://img.icons8.com/color/48/fetus.png" width="60" height="60" style="filter: invert(67%) sepia(452%) saturate(452%) hue-rotate(310deg) brightness(90%) contrast(90%);" alt="Baby Fetus"/>',
                mother: '<img width="50" height="50" src="https://img.icons8.com/ios-filled/50/E75480/mother.png" alt="mother"/>',
                partner: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',

                // Action Card Illustration
                celebration: '<img width="70" height="70" src="https://img.icons8.com/office/40/gift--v1.png" alt="gift--v1"/>',

                // Wrench icon for Tools
                tools: '<img width="25" height="25" src="https://img.icons8.com/parakeet-line/50/FA5252/maintenance.png" alt="maintenance"/>',
                
                // Article Icon
                articles_card_icon: '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--dark-pink);"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/><path d="M10 8h6"/><path d="M10 12h6"/></svg>',

                // Checklist icon
                checklist_icon: '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--dark-pink);"><path d="m3 12 2 2 4-4"/><path d="M12 6h9"/><path d="M12 12h9"/><path d="M12 18h9"/></svg>'
            };

            // Utility function to get the correct icon SVG
            function getIcon(name) {
                return ICON_SVGS[name] || '<span style="font-size: 20px;">?</span>'; // Fallback
            }

            // Data used to populate the main tracker
            const appData = {
                week_number: <?php echo $appData['week_number']; ?>,
                days_to_go: <?php echo $appData['days_to_go']; ?>,
                percent_done: <?php echo $appData['percent_done']; ?>,
                checklist_percent: <?php echo $appData['checklist_percent']; ?>,
                progress_width: <?php echo $appData['progress_width']; ?>,
            };

            // Function to update the DOM based on appData
            document.addEventListener('DOMContentLoaded', () => {
                // --- Language Switch Logic (NEW) ---
                const langSelect = document.getElementById('language-select');
                if (langSelect) {
                    langSelect.addEventListener('change', function() {
                        // Get the current URL without parameters
                        const baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        // Reload the page with the selected language parameter
                        window.location.href = baseUrl + '?lang=' + this.value;
                    });
                }
                // --- End Language Switch Logic ---


                // Top Bar Icons
                document.getElementById('settings-icon').innerHTML = getIcon('settings');
                document.getElementById('alert-icon').innerHTML = getIcon('alert');           
                document.getElementById('stats-icon').innerHTML = getIcon('stats');
                
                // Show signin or profile icon based on login status
                <?php if ($user): ?>
                document.getElementById('profile-icon').innerHTML = getIcon('profile');
                <?php else: ?>
                document.getElementById('signin-icon').innerHTML = getIcon('user');
                <?php endif; ?>

                // Main Content Icons
                document.getElementById('icon-baby').innerHTML = getIcon('baby');
                document.getElementById('icon-mother').innerHTML = getIcon('mother');
                document.getElementById('icon-partner').innerHTML = getIcon('partner');
                
                // Action Card Illustration
                document.getElementById('card-illustration').innerHTML = getIcon('celebration');

                // Tools Button Icon
                document.getElementById('tools-button-icon').innerHTML = getIcon('tools');
                
                // Articles Card Icon
                document.getElementById('article-icon-container').innerHTML = getIcon('articles_card_icon');
            });
        </script>


<div class="top-bar">
    <div class="left-icons-group">
        <a href="7settings.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('settings_title'); ?>">
            <span id="settings-icon"></span>
        </a>
        <a href="10trimster.php?lang=<?php echo $current_lang; ?>" class="trimster-link">
        </a>    
        <a href="7settings.php?lang=<?php echo $current_lang; ?>" class="trimster-link">
        </a>              
          
        <div class="language-dropdown-container">
            <select id="language-select" name="language">
                <?php echo $lang_options; ?>
            </select>
        </div>
        </div>

    <div class="tab-bar">
        <a href="index.php?lang=<?php echo $current_lang; ?>" class="tab active"><?php echo t('tab_pregnancy'); ?></a>
        <a href="6child.php?lang=<?php echo $current_lang; ?>" class="tab"><?php echo t('tab_child'); ?></a> 
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="alert.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('alert_title'); ?>">
            <span id="alert-icon"></span>
        </a>

        <a href="8activity.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="<?php echo t('stats_title'); ?>">
            <span id="stats-icon"></span>
        </a>
        <?php if ($user): ?>
        <a href="profile.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="My Profile">
            <span id="profile-icon"></span>
        </a>
        <?php else: ?>
        <a href="1signinsignup.php?lang=<?php echo $current_lang; ?>" class="icon-link" title="Sign In / Sign Up">
            <span id="signin-icon"></span>
        </a>
        <?php endif; ?>
    </div>
</div>
        <div class="week-tracker">
            <div class="week-circle-container">
                <div class="stat-left">
                    <strong id="percent-done"><?php echo $appData['percent_done'] . '%'; ?></strong> <?php echo t('complete'); ?>
                </div>
                <div class="week-circle">
                    <div class="week-number" style="font-size: 80px; font-weight: bold; line-height: 0.9;"><?php echo $appData['week_number']; ?></div>
                    <div class="week-days" style="font-size: 24px; margin-top: 5px;"><?php echo t('week'); ?></div>
                </div>
                <div class="stat-right">
                    <strong id="days-to-go"><?php echo $appData['days_to_go']; ?></strong> <?php echo t('days_to_go_caps'); ?>
                </div>
            </div>
            <a href="10trimester.php?lang=<?php echo $current_lang; ?>" class="trimester">
                <?php echo t('trimester'); ?>
            </a>
        </div>
        
        <div class="main-content-wrapper">
            <div class="main-content">
                <div class="content-header"><?php echo t('header_whats_happening'); ?></div>

                <div class="topic-icons">
                    <div class="topic-icon-item" onclick="location.href='baby-info.php?lang=<?php echo $current_lang; ?>'">
                        <div class="topic-icon-circle"><span id="icon-baby"></span></div>
                        <div class="topic-icon-label"><?php echo t('topic_baby'); ?></div>
                    </div>
                    <div class="topic-icon-item" onclick="location.href='mother-info.php?lang=<?php echo $current_lang; ?>'">
                        <div class="topic-icon-circle"><span id="icon-mother"></span></div>
                        <div class="topic-icon-label"><?php echo t('topic_mother'); ?></div>
                    </div>
                    <div class="topic-icon-item" onclick="location.href='#partner-info?lang=<?php echo $current_lang; ?>'">
                        <div class="topic-icon-circle"><span id="icon-partner"></span></div>
                        <div class="topic-icon-label"><?php echo t('topic_partner'); ?></div>
                    </div>
                </div>

                <div class="action-card" onclick="location.href='fruit_details.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-header"><?php echo t('card_header_size'); ?></div>
                    <div class="card-text">
                        <?php echo t('card_text_size'); ?>
                    </div>
                    <a href="fruit_details.php?lang=<?php echo $current_lang; ?>" class="continue-link"><?php echo t('card_link_open'); ?></a>
                    <span class="card-illustration">
                        <?php if (isset($baby_size_image) && $baby_size_image): ?>
                            <img src="<?php echo htmlspecialchars($baby_size_image); ?>" alt="Baby size illustration" style="width: 100px; height: auto;">
                        <?php else: ?>
                            <span id="card-illustration"></span>
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="tools-button" onclick="location.href='tools-page.php?lang=<?php echo $current_lang; ?>'">
                    <span class="tools-button-text"><?php echo t('tools_button'); ?></span>
                    <span class="tools-button-icon" id="tools-button-icon"></span>
                </div>
                
                <div class="checklist-card info-card-base" onclick="location.href='checklist.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-icon-container">
                        <span id="checklist-icon-card">
                             <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--dark-pink);">
                                 <path d="m3 12 2 2 4-4"/>
                                 <path d="M12 6h9"/>
                                 <path d="M12 12h9"/>
                                 <path d="M12 18h9"/>
                             </svg>
                           </span>
                    </div>
                    <div class="card-content-wrapper checklist-content-wrapper">
                        <div class="card-header-small"><?php echo t('card_header_checklist'); ?></div>
                        <div class="card-text-small"><?php echo t('card_text_checklist', $appData['progress_width']); ?></div>
                        <div class="progress-container">
                            <div class="progress-fill" style="width: <?php echo $appData['progress_width']; ?>%;"></div>
                        </div>
                    </div>
                    <div class="checklist-progress-text"><?php echo $appData['checklist_percent']; ?>%</div>
                </div>
                
                <div class="article-card info-card-base" onclick="location.href='articles.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-icon-container" id="article-icon-container">
                        </div>
                    <div class="card-content-wrapper">
                        <div class="card-header-small"><?php echo t('card_header_articles'); ?></div>
                        <div class="card-text-small"><?php echo t('card_text_articles'); ?></div>
                    </div>
                    <div class="article-link-text"><?php echo t('card_link_read'); ?></div>
                </div>
                
                <div class="info-card-base" onclick="location.href='baby_delivery.php?lang=<?php echo $current_lang; ?>'">
                    <div class="card-content-wrapper">
                        <div class="card-header-small">Had Your Baby?</div>
                        <div class="card-text-small">Log your baby's birth details to switch to post-birth tracking.</div>
                    </div>
                    <div class="article-link-text">LOG DELIVERY</div>
                </div>
                </div>
        </div>
    </div>
    <!-- Emergency top-bar alert script: paste after your existing JS, before </body> -->
<script>
// --- Emergency alert trigger for top-bar alert icon ---
// Runs after DOM ready
document.addEventListener('DOMContentLoaded', () => {

  // Create hidden audio element for beep (if not present)
  if (!document.getElementById('jananiBeep')) {
    const audio = document.createElement('audio');
    audio.id = 'jananiBeep';
    audio.src = 'https://actions.google.com/sounds/v1/alarms/beep_short.ogg';
    audio.preload = 'auto';
    document.body.appendChild(audio);
  }
  const beep = document.getElementById('jananiBeep');

  // small toast helper
  function showToast(msg, timeout = 4000) {
    const t = document.createElement('div');
    t.innerHTML = msg;
    Object.assign(t.style, {
      position: 'fixed',
      right: '20px',
      bottom: '20px',
      background: '#fff',
      color: '#111',
      padding: '10px 14px',
      borderRadius: '8px',
      boxShadow: '0 6px 18px rgba(0,0,0,0.12)',
      zIndex: 999999,
      fontFamily: 'Inter, sans-serif'
    });
    document.body.appendChild(t);
    setTimeout(() => t.remove(), timeout);
  }

  // find the icon wrapper (set by your PHP script earlier)
  const alertIconSpan = document.querySelector('#alert-icon');
  if (!alertIconSpan) {
    console.warn('Alert icon (#alert-icon) not found. Add <span id="alert-icon"></span> in top bar.');
    return;
  }
  // find anchor parent if exists
  const anchor = alertIconSpan.closest('a');

  // attach click listener (prevent default link)
  const clickTarget = anchor || alertIconSpan;
  clickTarget.addEventListener('click', (ev) => {
    ev.preventDefault();
    triggerEmergencyAlert();
  });

  // Main function
  function triggerEmergencyAlert() {
    // confirmation to avoid accidental triggers
    if (!confirm('Send EMERGENCY alert now?')) return;

    // start local alert: beep + flashing
    try { beep.loop = true; beep.play().catch(()=>{}); } catch(e){}

    const flashInterval = setInterval(() => {
      document.body.style.backgroundColor =
        document.body.style.backgroundColor === 'red' ? '' : 'red';
    }, 350);

    function stopLocalEffects() {
      clearInterval(flashInterval);
      document.body.style.backgroundColor = '';
      try { beep.pause(); beep.currentTime = 0; } catch(e){}
    }

    // show message
    showToast('Fetching GPS — allow location permission');

    if (!navigator.geolocation) {
      stopLocalEffects();
      alert('Geolocation not supported on this device.');
      return;
    }

    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();

    // Acquire location
    navigator.geolocation.getCurrentPosition((position) => {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      const mapLink = `https://maps.google.com/?q=${lat},${lon}`;

      // Build payload. Include logged-in PHP user id if available
      const payload = {
        source: 'janani_web',
        date: date,
        time: time,
        latitude: lat,
        longitude: lon,
        user_id: <?php echo ($user ? (int)$user['id'] : 'null'); ?>,
        user_name: <?php echo ($user ? json_encode($user['name'] ?? '') : '""'); ?> 
      };

      // POST to backend
      fetch('send_alert.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(res => {
        stopLocalEffects();
        if (res && res.status === 'success') {
          showToast('Alert sent ✅');
          // Optionally show details
          alert('Alert sent to contacts.\n\nDate: ' + date + '\nTime: ' + time + '\nLocation: ' + mapLink);
        } else {
          console.error('send_alert error', res);
          showToast('Failed to send alert — check console', 6000);
          alert('Failed to send alert: ' + (res.message || JSON.stringify(res)));
        }
      })
      .catch(err => {
        stopLocalEffects();
        console.error(err);
        showToast('Network error while sending alert', 6000);
        alert('Network error: ' + (err.message || err));
      });

    }, (err) => {
      stopLocalEffects();
      alert('Unable to access location. Please enable GPS and try again.');
    }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 });
  }

});
</script>

    <?php include '15footer.php'; ?>
</body>
</html>