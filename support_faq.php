<?php
// PHP Section: Define page title and FAQ data
$page_title = "Support and FAQ";

// Array to hold Questions and Answers
$faq_items = [
    [
        "q" => "How do I change my Estimated Due Date (EDD) in the app?",
        "a" => "You can change your Estimated Due Date (EDD) in the 'My profile' section. Please ensure you enter the date confirmed by your healthcare provider for accurate tracking."
    ],
    [
        "q" => "Can I track multiple pregnancies in the same app?",
        "a" => "Yes, you can track multiple pregnancies by tapping the 'ADD PREGNANCY' option on the settings page or by managing them within the 'My family' settings."
    ],
    [
        "q" => "What should I do if I want to delete my account?",
        "a" => "Please navigate to 'App settings', then select 'My profile'. You will find the option to delete your account at the bottom of that page. Note that this action is permanent."
    ],
    [
        "q" => "How accurate is the baby size information provided in the app?",
        "a" => "The baby size and growth information provided in the app is an average estimate. Every baby is different, and the actual size of your baby may vary. Always consult your doctor for precise information."
    ],
    [
        "q" => "When will I receive the new weekly information?",
        "a" => "New weekly information (such as fetal development updates) will be automatically updated at the start of your current gestational week. The app is updated daily to reflect your progress."
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Pregnancy App</title>
    
    <style>
        /* General body and styling consistent with previous screens */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #fcf6f7;
            min-height: 100vh;
        }

        /* Header Styling */
        .header-bar-wrapper {
            width: 100%;
            background-color: #ffffff;
            border-bottom: 1px solid #e0d9cd;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .app-header {
            display: flex;
            align-items: center;
            padding: 20px 40px; 
            max-width: 90vw; 
            margin: 0 auto; 
        }
        
        .app-header h1 {
            font-size: 24px; 
            font-weight: 500;
            color: #4b4b4b;
            margin: 0 auto;
            white-space: nowrap; 
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .back-arrow {
            font-size: 30px; 
            text-decoration: none;
            color: #4b4b4b; 
            line-height: 1;
            z-index: 10;
        }

        /* --- Main Content Wrapper --- */
        .content-wrapper {
            max-width: 90vw; 
            margin: 0 auto; 
            padding: 20px 40px; 
        }

        /* --- FAQ Section Styling --- */
        .faq-section {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 30px;
        }

        .faq-section h2 {
            font-size: 28px;
            color: #eb7c8c; /* Pink heading */
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0e9ea;
        }

        .faq-item {
            border-bottom: 1px solid #f0e9ea;
            padding: 15px 0;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .question {
            font-size: 18px;
            font-weight: 600;
            color: #4b4b4b;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }
        
        .toggle-icon {
            font-size: 24px;
            color: #eb7c8c;
            margin-left: 10px;
            transition: transform 0.3s;
        }

        .answer {
            font-size: 16px;
            color: #666;
            padding: 0 0 0 0; /* Reduced padding when closed */
            line-height: 1.6;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease-out;
        }

        .answer.active {
            max-height: 500px; /* Large enough value to show content */
            padding: 10px 0 15px 0; /* Padding when open */
        }
        
        .question.open .toggle-icon {
            transform: rotate(180deg); /* Rotate the chevron when open */
        }
    </style>
</head>
<body>

<div class="faq-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <a href="7settings.php" class="back-arrow">&leftarrow;</a> 
            
            <div class="header-content">
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
            
            </header>
    </div>

    <div class="content-wrapper">
        
        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>

            <?php foreach ($faq_items as $index => $item): ?>
                <div class="faq-item">
                    <div class="question" data-index="<?php echo $index; ?>">
                        <span><?php echo htmlspecialchars($item['q']); ?></span>
                        <span class="toggle-icon">&#x2304;</span> </div>
                    
                    <div class="answer" id="answer-<?php echo $index; ?>">
                        <?php echo htmlspecialchars($item['a']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px dashed #f0e9ea; text-align: center;">
                <p style="font-size: 16px; color: #888;">Need more help? You can <a href="contact_us.php" style="color: #eb7c8c; text-decoration: none; font-weight: bold;">Contact us here.</a></p>
            </div>

        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('.question');

        questions.forEach(question => {
            question.addEventListener('click', function() {
                const answerId = 'answer-' + this.getAttribute('data-index');
                const answer = document.getElementById(answerId);
                
                // Toggle the 'open' class on the question to rotate the icon
                this.classList.toggle('open');
                
                // Toggle the 'active' class on the answer to show/hide it smoothly
                if (answer.classList.contains('active')) {
                    answer.classList.remove('active');
                    answer.style.maxHeight = 0;
                } else {
                    answer.classList.add('active');
                    // Set maxHeight dynamically to scroll height for smooth open/close effect
                    answer.style.maxHeight = answer.scrollHeight + "px";
                }
            });
        });

        // Ensure the back button navigates to the App Settings file
        document.querySelector('.back-arrow').addEventListener('click', function(e) {
            // No e.preventDefault() needed if we want the default navigation to 'app_settings.php'
            console.log("Navigating back to App Settings...");
        });
    });
</script>
    <?php include '15footer.php'; ?>

</body>
</html>