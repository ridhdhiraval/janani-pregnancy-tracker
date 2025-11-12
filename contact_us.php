<?php
// PHP LOGIC: TRANSLATION, CONFIGURATION, AND LANGUAGE SETUP
// --- START Configuration & Translation Setup ---

// 1. General Configuration
$your_email_id = "info@janani.com"; 
$back_link = "7settings.php"; 

// Email subject and body for mailto links
$default_subject = "Query Regarding JANANI Service";
$default_body = "Hello JANANI Team,\n\nI am writing to you regarding...";

// Mailto link for customer support email blocks
$mailto_link_support = "mailto:" . urlencode($your_email_id) . 
                            "?subject=" . urlencode($default_subject) . 
                            "&body=" . urlencode($default_body);


// 2. Translation Data
$translations = [
    'en' => [
        'brand_name' => 'JANANI', 
        'logo_text' => 'SRS', 
        'get_in_touch' => 'Get in touch with us',
        'contact_intro' => 'If you have a question, suggestion or complaint, please send us and email and we will get back to you in 2-5 working days. If your app is misbehaving, please send us an email with phone model, os-version, app-version, and if you have an account in-app or not.',
        'customer_support' => 'Customer support',
        'run_into_problems' => 'Have you run into problems?',
        'find_faq_answer' => 'Find answers to the most common questions in our FAQ.',
        'didnt_find' => 'Didn\'t find what you were looking for?',
        'go_to_settings' => 'Go to Settings and click Support in the app',
        'partnerships' => 'Partnerships',
        'interested_in_partnership' => 'Are you interested in a partnership with JANANI?', 
        'headquarters' => 'Headquarters',
        'invoicing' => 'Invoicing',
        'stockholm' => 'Stockholm',
        'info_email' => 'info@janani.com', 
        'invoicing_email' => 'invoice@janani.com', 
        'Customer ID' => 'Customer ID: JNN1568',
        'fe' => 'FE 301, 105 69 Stockholm',
        'vat' => 'VAT: SE556768427801',
        // --- New Form Translation Keys ---
        'form_heading' => 'Get in Touch',
        'form_sub_heading' => "We'd love to hear from you! Drop us a message below.",
        'your_name' => 'Your Name',
        'your_email' => 'Your Email',
        'your_message' => 'Your Message',
        'send_message' => 'Send Message',
        'JANANI Health Technologies Pvt. Ltd.' => 'JANANI Health Technologies Pvt. Ltd.',
        'GSTIN: 27AAICJ1234A1Z2' => 'GSTIN: 27AAICJ1234A1Z2',
    ],
    // Add other languages here if needed
];

// Determine the current language (Default is 'en')
$allowed_langs = array_keys($translations);
$current_lang = $_GET['lang'] ?? 'en'; 
if (!in_array($current_lang, $allowed_langs)) {
    $current_lang = 'en';
}

// Translation function
if (!function_exists('t')) {
    function t($key) {
        global $translations, $current_lang;
        return $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
    }
}
// --- END Translation Setup ---
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($current_lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('get_in_touch'); ?> - <?php echo t('brand_name'); ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* --- General Reset & Layout --- */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            background-color: #fff; /* White background for desktop look */
            line-height: 1.6;
            padding-top: 0; 
        }

        /* --- Header Bar Styles (Adapted for Desktop/General Page Header) --- */
        .app-header {
            position: static; 
            width: 100%;
            background-color: #f8f8f8; /* Light background for a header bar */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); 
            padding: 15px 40px; 
            display: flex;
            align-items: center;
            z-index: 1000;
            box-sizing: border-box;
            border-bottom: 1px solid #ddd;
        }
        
        .app-header h1 {
            color: #2F4F4F; /* Dark Teal title */
            flex-grow: 1; 
            text-align: center;
            margin: 0;
            font-size: 1.5rem; 
            font-weight: 600;
            padding-right: 30px; 
        }

        /* Back Arrow Styling */
        .back-arrow {
            font-size: 24px; 
            text-decoration: none;
            color: #EE829E; /* Pink color for primary actions */
            line-height: 1;
        }


        /* --- Main Content Container --- */
        .contact-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* --- Header Section (Now main content title) --- */
        .contact-header h1 {
            font-size: 2.8rem; 
            font-weight: 700;
            color: #2F4F4F; 
            margin-bottom: 20px;
        }
        
        .contact-header p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 50px;
        }

        /* --- Support and Partnership Sections --- */
        .section-heading {
            font-size: 1.7rem; 
            font-weight: 600;
            color: #EE829E; 
            margin-top: 40px;
            margin-bottom: 25px;
        }
        
        .contact-info-block {
            margin-bottom: 30px;
        }

        .contact-info-block h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .contact-info-block p {
            font-size: 1.05rem;
            color: #666;
            margin-top: 5px;
        }

        /* --- Email Blocks (Background highlighted in beige) --- */
        .email-block {
            background-color: #FFF9F6; 
            padding: 18px 25px;
            margin-top: 15px;
            margin-bottom: 40px;
        }

        .email-block a {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2F4F4F; 
            text-decoration: none;
        }
        
        .email-block a:hover {
            text-decoration: underline;
        }
        
        /* --- Address/Invoicing Section --- */
        .address-invoicing-grid {
            display: flex;
            justify-content: space-between;
            gap: 60px; 
            border-top: 1px solid #ddd;
            padding-top: 40px;
            margin-top: 50px;
            margin-bottom: 60px; 
        }

        .grid-item {
            flex: 1;
            display: flex;
            gap: 20px;
        }

        .grid-item i {
            font-size: 28px; 
            color: #EE829E; 
            align-self: flex-start;
        }

        .item-details h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-top: 0;
            margin-bottom: 12px;
        }

        .item-details p {
            margin: 0;
            font-size: 1.05rem;
            color: #666;
        }
        
        /* ---------------------------------------------------- */
        /* --- Contact Form Styles (Glassmorphism Effect) --- */
        /* ---------------------------------------------------- */
        .form-section {
            padding: 80px 20px;
            width: 100%;
            box-sizing: border-box;
            
            /* **UPDATED BACKGROUND:** Soft Radial Pink Gradient */
            background: radial-gradient(circle at top right, #fff0f5 0%, #ffffff 60%);
        }
        
        .form-box {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 20px;
            
            /* UPDATED GLASS EFFECT: */
            background-color: rgba(255, 255, 255, 0.5); /* Higher opacity for a cleaner look on light background */
            border: 1px solid rgba(255, 255, 255, 0.7); /* Thicker, brighter border */
            box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.08); /* Softer shadow */
            backdrop-filter: blur(8px); 
            -webkit-backdrop-filter: blur(8px);
            
            color: #2F4F4F; /* Dark Teal fallback */
            text-align: center;
        }
        
        .form-box h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2F4F4F; /* Dark Teal Title */
        }
        
        .form-box p {
            font-size: 1rem;
            margin-bottom: 30px;
            color: #666; /* Darker sub-heading text for contrast */
        }
        
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #2F4F4F; /* Dark Teal Label */
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            /* Input fields: Very subtle white background for the glass-on-glass effect */
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8); 
            color: #333; /* Dark input text */
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #999; /* Grey placeholder text */
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #EE829E;
            box-shadow: 0 0 0 1px #EE829E;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            /* **UPDATED BUTTON GRADIENT:** Soft Pink/Purple gradient (from image_46b4c6.png) */
            background-image: linear-gradient(90deg, #d0abf5 0%, #eaa0b4 100%);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s, transform 0.3s;
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* --- Mobile Responsive Fallback --- */
        @media (max-width: 768px) {
            body {
                padding-top: 50px; 
            }
            .app-header {
                position: fixed;
                padding: 10px 15px;
            }
            .app-header h1 {
                font-size: 20px;
                padding-right: 25px;
            }
            .contact-container {
                padding: 20px 15px 40px;
            }
            .contact-header h1 {
                font-size: 2.5rem;
                display: block; 
            }
            .address-invoicing-grid {
                flex-direction: column;
                gap: 30px;
                padding-top: 30px;
            }
            .form-box {
                padding: 20px;
            }
            .form-box h2 {
                font-size: 1.8rem;
            }
            .form-section {
                padding: 40px 15px;
            }
        }
    </style>
</head>
<body>

    <header class="app-header">
        <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
        <h1><?php echo t('get_in_touch'); ?></h1>
    </header>

    <div class="contact-container">
        
        <div class="contact-header">
            <h1><?php echo t('get_in_touch'); ?></h1> 
            <p><?php echo t('contact_intro'); ?></p>
        </div>

        <h2 class="section-heading"><?php echo t('customer_support'); ?></h2>

        <div class="contact-info-block">
            <h3><?php echo t('run_into_problems'); ?></h3>
            <p><?php echo t('find_faq_answer'); ?></p>
        </div>

        <div class="contact-info-block">
            <h3><?php echo t('didnt_find'); ?></h3>
            <p><?php echo t('go_to_settings'); ?></p>
        </div>
        
        <div class="email-block">
            <a href="<?php echo htmlspecialchars($mailto_link_support); ?>"><?php echo t('info_email'); ?></a>
        </div>
        
        <h2 class="section-heading"><?php echo t('partnerships'); ?></h2>
        
        <div class="contact-info-block">
            <p><?php echo t('interested_in_partnership'); ?></p>
        </div>
        
        <div class="email-block">
            <a href="<?php echo htmlspecialchars($mailto_link_support); ?>"><?php echo t('info_email'); ?></a>
        </div>

        <div class="address-invoicing-grid">
            
            <div class="grid-item">
                <i class='bx bxs-map'></i>
                <div class="item-details">
                    <h4><?php echo t('headquarters'); ?></h4>
                    <p><?php echo t('stockholm'); ?></p>
                    <p><?php echo t('fe'); ?></p>
                </div>
            </div>
            
            <div class="grid-item">
                <i class='bx bxs-envelope'></i>
                <div class="item-details">
                    <h4><?php echo t('invoicing'); ?></h4>
                    <p><?php echo t('Customer ID'); ?></p>
                    <p><?php echo t('JANANI Health Technologies Pvt. Ltd.'); ?></p>
                    <p><a href="mailto:<?php echo t('invoicing_email'); ?>" style="color: #666; text-decoration: underline;"><?php echo t('invoicing_email'); ?></a></p>
                    <p><?php echo t('GSTIN: 27AAICJ1234A1Z2'); ?></p>
                </div>
            </div>
        </div>

    </div>
    
    <section class="form-section">
        <div class="form-box">
            <h2><?php echo t('form_heading'); ?></h2>
            <p><?php echo t('form_sub_heading'); ?></p>
            
            <form action="#" method="post">
                <div class="form-group">
                    <label for="name"><?php echo t('your_name'); ?></label>
                    <input type="text" id="name" name="name" value="Ridhhi Raval" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><?php echo t('your_email'); ?></label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="message"><?php echo t('your_message'); ?></label>
                    <textarea id="message" name="message" placeholder="Write your message" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn"><?php echo t('send_message'); ?></button>
            </form>
        </div>
    </section>
    <?php 
    // This is the location of your footer include, placing it immediately after the form section.
    include '15footer.php'; 
    ?>

</body>
</html>