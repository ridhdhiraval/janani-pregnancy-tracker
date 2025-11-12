<?php
// tool-wellness.php: Detailed Wellness page, featuring step-by-step Safe Exercises 
// with a single static image for each exercise, based on the provided images.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preglife Wellness</title>
    <style>
        /* --- Root Variables & Base Styles --- */
        :root {
            --primary-pink: #ff708e; 
            --soft-bg: #fcf8f6; 
            --text-dark: #333333;
            --text-grey: #6a6a6a;
            --white-bg: #ffffff;
            --banner-bg: #f8c2cb; 
            --dark-text-on-banner: #4a1d25; 
            --card-bg-light: #f2f7f5; 
            --icon-color-light: #6a8c6a; 
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--soft-bg);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .app-container {
            width: 90%; /* डेस्कटॉप पर चौड़ाई बढ़ा दी */
            max-width: 1200px; /* **डेस्कटॉप साइज़ के लिए Max-width सेट किया** */
            background-color: var(--soft-bg);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden; 
            margin: 20px 0; /* डेस्कटॉप पर ऊपर और नीचे मार्जिन जोड़ा */
        }

        /* --- Top Banner Section --- */
        .top-banner {
            background-color: var(--banner-bg);
            position: relative;
            padding-bottom: 50px; 
            color: var(--dark-text-on-banner);
            /* डेस्कटॉप पर कंटेंट को बेहतर दिखाने के लिए flex का इस्तेमाल */
            display: flex; 
            align-items: center; 
            padding-right: 20px;
        }
        
        .top-banner::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background-color: var(--soft-bg);
            border-top-left-radius: 50px; 
            border-top-right-radius: 50px;
            z-index: 2;
        }
        
        .nav-bar {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            font-size: 24px;
            font-weight: 300;
            cursor: pointer;
            color: var(--dark-text-on-banner);
            position: relative;
            z-index: 3;
        }

        .header-content {
            padding: 20px 0 20px 20px; /* बाएँ और ऊपर की तरफ़ पैडिंग */
            position: relative;
            z-index: 3;
            flex-grow: 1; /* उपलब्ध जगह ले लेगा */
        }

        .header-content h1 {
            font-size: 40px; /* डेस्कटॉप के लिए बड़ा फ़ॉन्ट */
            font-weight: 700;
            margin: 10px 0 5px;
            color: var(--text-dark);
        }

        .header-content p {
            font-size: 18px; /* डेस्कटॉप के लिए बड़ा फ़ॉन्ट */
            line-height: 1.5;
            margin: 0;
            color: var(--text-dark);
            max-width: 700px; /* टेक्स्ट को ज़्यादा फैलने से रोकने के लिए */
        }

        /* --- Featured CTA Card & Progress Tracker (Side-by-Side on Desktop) --- */
        .desktop-flex-row {
            display: flex;
            gap: 20px;
            margin: 0 20px;
            transform: translateY(-30px); 
            position: relative;
            z-index: 5;
        }
        
        .featured-cta {
            background-color: var(--card-bg-light);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            cursor: pointer;
            flex-grow: 1; /* डेस्कटॉप पर उपलब्ध जगह को बाँट देगा */
        }
        
        .progress-tracker {
            background-color: var(--white-bg);
            padding: 15px;
            border-radius: 15px;
            border: 1px solid var(--primary-pink);
            flex-grow: 1; /* डेस्कटॉप पर उपलब्ध जगह को बाँट देगा */
        }

        /* Progress Bar Styles */
        .progress-bar-container {
            background-color: var(--soft-bg);
            border-radius: 10px;
            height: 10px;
            margin-top: 5px;
        }
        
        .progress-bar {
            width: 35%; /* Example progress */
            height: 100%;
            background-color: var(--primary-pink);
            border-radius: 10px;
        }


        /* --- Content Sections (Exercise and Explore) --- */
        .content-section {
            padding: 0 20px;
            margin-top: -15px;
        }
        
        .content-section h3 {
            font-size: 26px; /* डेस्कटॉप के लिए बड़ा फ़ॉन्ट */
            font-weight: 700;
            color: var(--primary-pink);
            margin: 40px 0 20px; /* अधिक मार्जिन */
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }

        /* Exercise Grid on Desktop */
        .exercise-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* डेस्कटॉप पर 2 कॉलम */
            gap: 25px;
            margin-bottom: 30px;
        }

        /* --- Exercise Item & Image Styles --- */
        .exercise-item {
            background-color: var(--white-bg);
            border-radius: 10px;
            padding: 20px; /* अधिक पैडिंग */
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
        }
        
        .exercise-item h4 {
            font-size: 20px; /* डेस्कटॉप के लिए बड़ा फ़ॉन्ट */
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 10px;
        }
        
        .exercise-item p.description {
            font-size: 15px;
            color: var(--text-grey);
            margin: 0 0 15px;
            font-style: italic;
        }

        /* Container for the single static image */
        .static-img-container {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 20px; /* अधिक मार्जिन */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--soft-bg);
            height: 250px; /* इमेज की ऊँचाई बढ़ाई */
        }
        
        .static-img-container img {
            width: 100%;
            height: 100%;
            object-fit: contain; 
            display: block;
        }

        /* --- Step List Styling --- */
        .step-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .step-list li {
            margin-bottom: 12px;
            font-size: 16px; /* डेस्कटॉप के लिए बड़ा फ़ॉन्ट */
            line-height: 1.5;
            color: var(--text-dark);
            display: flex;
            align-items: flex-start;
        }
        
        .step-list li strong {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            background-color: var(--primary-pink);
            color: var(--white-bg);
            border-radius: 50%;
            margin-right: 12px;
            font-weight: 500;
            flex-shrink: 0;
        }

        /* Explore List Grid on Desktop */
        .explore-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* डेस्कटॉप पर 3 कॉलम */
            gap: 20px;
            padding-bottom: 40px;
        }

        .explore-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: var(--white-bg);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            flex-direction: column; /* टेक्स्ट को आइकन के नीचे लाया */
            text-align: center;
        }
        .explore-icon-container {
            width: 50px; /* आइकन बड़ा किया */
            height: 50px;
            margin: 0 auto 10px; /* मार्जिन सेंटर किया */
            color: var(--primary-pink);
            flex-shrink: 0;
        }
        .explore-content h4 {
            margin: 0;
            font-size: 18px; /* बड़ा फ़ॉन्ट */
            font-weight: 600;
            color: var(--text-dark);
        }
        .explore-content p {
            margin: 5px 0 0;
            font-size: 14px;
            color: var(--text-grey);
        }

        /* Responsive adjustments for very large screens (optional) */
        @media (min-width: 1400px) {
            .app-container {
                max-width: 1400px;
            }
        }
        /* Mobile fallback - ensures it still looks good on smaller screens */
        @media (max-width: 768px) {
            .app-container {
                width: 100%;
                margin: 0;
            }
            .top-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-content {
                padding: 0 20px 20px;
            }
            .desktop-flex-row {
                flex-direction: column;
                margin: 0 20px;
                gap: 15px;
                transform: none;
            }
            .progress-tracker {
                margin-top: 30px; 
            }
            .exercise-grid {
                grid-template-columns: 1fr;
            }
            .explore-list {
                grid-template-columns: 1fr;
            }
            .explore-item {
                flex-direction: row;
                text-align: left;
            }
            .explore-icon-container {
                margin: 0 15px 0 0;
            }
        }
        
    </style>
</head>
<body>

<div class="app-container">
    
    <div class="top-banner">
        <div class="nav-bar" onclick="history.back()">&lt;</div>
        <div class="header-content">
            <h1>Preglife Wellness</h1>
            <p>Exercises & classes designed to improve mobility, strength & circulation</p>
        </div>
        
    </div>
    
    <div class="desktop-flex-row">
        <div class="featured-cta" onclick="alert('Starting a session.')">
            <div class="cta-text">
                <h2>Start your 15-minute routine</h2>
                <p style="color: var(--primary-pink); font-weight: 600;">DAILY ROUTINES TRACKER</p>
            </div>
        </div>

        <div class="progress-tracker">
            <h4>Weekly Wellness Progress</h4>
            <p style="font-size: 14px; color: var(--text-grey);">Track which exercises were done. Energy/mood improvements.</p>
            <div class="progress-bar-container">
                <div class="progress-bar"></div>
            </div>
        </div>
    </div>


    <div class="content-section">
        
        <h3>Safe Exercises: Step-by-Step</h3>
        
        <div class="exercise-grid">
            
            <div class="exercise-item" id="exercise-1">
                <h4>1. Seated Side Bend</h4>
                <p class="description">A gentle stretch to improve flexibility in your sides and relieve tension in the lower back.</p>
                
                <div class="static-img-container">
                    <img src="images/image_88f842.png" alt="Seated Side Bend" />         
                </div>

                <p style="font-weight: 600; margin-bottom: 5px;">How to do it:</p>
                <ol class="step-list">
                    <li><strong>1</strong> Sit with one leg extended straight and the other bent, foot pressing into your inner thigh.</li>
                    <li><strong>2</strong> Inhale, raise the arm on the extended leg side overhead.</li>
                    <li><strong>3</strong> Exhale, gently bend towards your extended leg, reaching for your foot or shin. Keep your chest open.</li>
                    <li><strong>4</strong> Hold for 20-30 seconds, then slowly come back up and repeat on the other side.</li>
                </ol>
            </div>
            
            <div class="exercise-item" id="exercise-2">
                <h4>2. Butterfly Stretch (Baddha Konasana)</h4>
                <p class="description">Opens hips and groin, great for increasing flexibility and preparing for labor.</p>
                
                <div class="static-img-container">
                    <img src="images/image_88f7a6.jpg" alt="Butterfly Stretch" />         
                </div>

                <p style="font-weight: 600; margin-bottom: 5px;">How to do it:</p>
                <ol class="step-list">
                    <li><strong>1</strong> Sit on the floor with the soles of your feet together, knees bent out to the sides.</li>
                    <li><strong>2</strong> Hold onto your feet or ankles. Sit tall, lengthening your spine.</li>
                    <li><strong>3</strong> Gently press your knees down towards the floor, feeling the stretch in your inner thighs. Do not force the stretch.</li>
                    <li><strong>4</strong> Hold for 30 seconds to 1 minute, breathing deeply and relaxing your hips.</li>
                </ol>
            </div>
            
            <div class="exercise-item" id="exercise-3">
                <h4>3. Gentle Seated Pose & Focus</h4>
                <p class="description">This pose aids in relaxation, improves posture, and strengthens core awareness.</p>
                
                 <div class="static-img-container">
                    <img src="images/image_88f3ff.png" alt="Seated Gentle Pose" />         
                </div>

                <p style="font-weight: 600; margin-bottom: 5px;">How to do it:</p>
                <ul class="step-list">
                    <li><strong>1</strong> Sit cross-legged (or with legs extended if more comfortable), maintaining a tall, straight spine.</li>
                    <li><strong>2</strong> Place your hands on your knees or in your lap.</li>
                    <li><strong>3</strong> Perform a gentle seated side stretch: Inhale, raise one arm, and exhale, gently bend to the opposite side.</li>
                    <li><strong>4</strong> Return to center and hold the seated position for 1-5 minutes, focusing on slow, deep belly breaths.</li>
                </ul>
            </div>

            <div class="exercise-item" id="exercise-4">
                <h4>4. Supported Bridge Pose</h4>
                <p class="description">Strengthens the glutes and pelvic floor, improves posture, and helps relieve sciatica and back pain.</p>
                
                 <div class="static-img-container">
                    <img src="images/image_88f442.png" alt="Supported Bridge Pose" />         
                </div>

                <p style="font-weight: 600; margin-bottom: 5px;">How to do it:</p>
                <ul class="step-list">
                    <li><strong>1</strong> Lie on your back with knees bent and feet hip-width apart, pressing into the floor.</li>
                    <li><strong>2</strong> Inhale and press through your feet to lift your hips toward the ceiling.</li>
                    <li><strong>3</strong> If using a prop (recommended), slide a yoga block or firm pillow under your lower back (sacrum) for support.</li>
                    <li><strong>4</strong> Hold this position for 30 seconds, maintaining relaxed breathing, then slowly lower your hips to the floor.</li>
                </ul>
            </div>
            
        </div>
        
        
    </div>
    
</div>
    <?php include '15footer.php'; ?>

</body>
</html>