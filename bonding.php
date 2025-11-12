<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Bonding Activities"; 
$back_link = "6child.php"; // Back link to the main profile/dashboard page

// Data for different sections with detailed modal content
$activities = [
    [
        "id" => "music",
        "title" => "Music for Baby",
        "icon" => "🎵",
        "description" => "Playing soft music and classical melodies can soothe your baby and aid brain development. Choose a few tracks to play regularly.",
        "link_text" => "View Song List",
        "modal_content_html" => "
            <h4>🎶 Recommended Baby-Friendly Songs & Music</h4>
            <p>These selections are gentle and known to be soothing for infants and babies in the womb:</p>
            <ul>
                <li>**Classical:** Mozart - Lullaby (*Wiegenlied*)</li>
                <li>**Classical:** Bach - Cello Suite No. 1</li>
                <li>**Lullaby:** Danny Boy (Instrumental version is often calming)</li>
                <li>**Popular:** What a Wonderful World (Louis Armstrong)</li>
                <li>**Nature/Ambient:** Gentle Rain or Ocean Waves</li>
            </ul>
            <p style='color:#e69999; font-weight:bold;'>Consistency is key; playing the same songs regularly creates familiarity.</p>
            <button class='modal-action-btn' onclick=\"alert('Simulating opening a music service or in-app playlist creator...')\">Start Playlist Creation</button>
        "
    ],
    [
        "id" => "checklist", 
        "title" => "Baby Prep Checklist", 
        "icon" => "🛒", 
        "description" => "Ensure you have all the essential items ready before your baby arrives. Use this checklist to track your purchases.",
        "link_text" => "View Checklist", 
        "modal_content_html" => "
            <h4>🛒 First-Week Essentials List</h4>
            <p>Yeh woh zaroori cheezein hain jo aapko bachche ke aane se pehle taiyar rakhni chahiye:</p>
            <ul>
                <li>**Nursery:** Crib, Mattress, Sheets (3-4 sets)</li>
                <li>**Clothing:** Sleepsuits/onesies (7-10), Hats, Mittens, Socks (5-7 pairs)</li>
                <li>**Diapering:** Diapers (newborn size), Wipes, Diaper Rash Cream</li>
                <li>**Feeding:** Bottles (if bottle-feeding), Sterilizer/Cleaning Brush, Burp cloths</li>
                <li>**Travel:** Car Seat (installed correctly), Stroller or Carrier</li>
            </ul>
            <p style='color:#2a9d8f; font-weight:bold;'>**Important:** Car seat ko pehle hi install kar lein!</p>
            <button class='modal-action-btn' onclick=\"alert('Simulating linking to a detailed shopping list tool...')\">Manage Full List</button>
        "
    ],
    [
        "id" => "names",
        "title" => "Name Ideas",
        "icon" => "✨",
        "description" => "Searching for the perfect name? Explore different names, meanings, and origins. Try saying them out loud to feel the connection.",
        "link_text" => "Name Explorer",
        "modal_content_html" => "
            <h4>✨ How to Choose the Perfect Name</h4>
            <p><strong>1. Meaning:</strong> Research names based on desired traits (strength, beauty, wisdom).</p>
            <p><strong>2. Flow Test:</strong> Say the full name, including middle and last names. Check if the initials spell anything unusual.</p>
            <p><strong>3. Nicknames:</strong> Consider potential nicknames—will they be ones you love or dread?</p>
            <p style='margin-top:15px;'>Start your name list today!</p>
            <button class='modal-action-btn' onclick=\"alert('Simulating launching a Name Generator tool...')\">Launch Name Explorer</button>
        "
    ],
    [
        "id" => "hospital_bag", // ID UPDATED
        "title" => "Hospital Bag Planner", // TITLE UPDATED
        "icon" => "🏥", // ICON UPDATED
        "description" => "Pack your bags well in advance! This list covers essentials for mom, baby, and partner for the hospital stay.",
        "link_text" => "Start Packing", // LINK TEXT UPDATED
        "modal_content_html" => "
            <h4>🏥 Must-Haves for the Hospital Bag (3 Bags)</h4>
            <p>Delivery ki date se 3-4 hafte pehle taiyar rakhen:</p>
            <ol>
                <li>**Mom's Bag:** Documents (ID, insurance), Comfortable clothes, Toiletries, Slippers.</li>
                <li>**Baby's Bag:** Going-home outfit, Diapers, Wipes, Blankets/Swaddles.</li>
                <li>**Partner's Bag:** Snacks, Phone charger (extra long cord), Entertainment, Change of clothes.</li>
            </ol>
            <p style='color:#f4a261; font-weight:bold;'>**Tip:** Car seat ko install karna na bhulein!</p>
            <button class='modal-action-btn' onclick=\"alert('Simulating linking to a printable checklist...')\">Get Printable Checklist</button>
        " // CONTENT UPDATED
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Base Theme Styles */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed;
            color: #4b4b4b;
        }
        
        /* Header Bar */
        .app-header {
            position: sticky;
            top: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
        }

        .back-arrow-btn {
            font-size: 28px; 
            text-decoration: none;
            color: #4b4b4b;
            cursor: pointer;
            padding: 0 5px;
            line-height: 1;
        }
        
        .header-title {
            font-size: 20px;
            font-weight: 600;
            flex-grow: 1;
            text-align: center;
            margin-left: -28px; 
        }

        /* Main Content Area */
        .content-area {
            padding: 20px;
            max-width: 700px; 
            margin: 0 auto;
        }
        
        /* --- Bonding Card Styles --- */
        .activities-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
        }
        
        .activity-card {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #2a9d8f; 
            transition: box-shadow 0.2s, transform 0.2s;
            cursor: pointer; 
        }
        
        .activity-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .activity-icon {
            font-size: 40px;
            margin-bottom: 10px;
            line-height: 1;
        }

        .activity-card h3 {
            font-size: 20px;
            font-weight: bold;
            color: #e69999; 
            margin: 0 0 10px;
        }

        .activity-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .activity-link {
            display: inline-block;
            background-color: #a8dadc; 
            color: #333;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer; 
        }

        .activity-link:hover {
            background-color: #92c6c8;
        }
        
        /* --- Modal Styles --- */
        .modal {
            display: none; 
            position: fixed;
            z-index: 2000; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0,0,0,0.6);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; 
            padding: 30px;
            border-radius: 15px;
            width: 90%; 
            max-width: 500px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .modal-header h2 {
            margin: 0;
            color: #2a9d8f; 
            font-size: 24px;
        }

        .close-btn {
            color: #4b4b4b;
            font-size: 36px;
            font-weight: lighter;
            cursor: pointer;
        }
        
        .modal-content h4 {
            color: #e69999; 
            margin-top: 20px;
        }

        .modal-action-btn {
            background-color: #f4a261;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
            width: 100%;
        }
        .modal-action-btn:hover {
            background-color: #e09255;
        }
        
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-50px);}
            to {opacity: 1; transform: translateY(0);}
        }

    </style>
</head>
<body>

    <header class="app-header">
        <a href="<?php echo htmlspecialchars($back_link); ?>" id="backButton" class="back-arrow-btn">&#x2329;</a> 
        <div class="header-title"><?php echo htmlspecialchars($page_title); ?></div>
        <div></div> 
    </header>

    <div class="content-area">
        
        <div class="activities-grid">
            <?php foreach ($activities as $activity): ?>
                <div class="activity-card open-modal-trigger" 
                     data-title="<?php echo htmlspecialchars($activity['title']); ?>"
                     data-content='<?php echo htmlspecialchars($activity['modal_content_html']); ?>'>

                    <div class="activity-icon"><?php echo htmlspecialchars($activity['icon']); ?></div>
                    <h3><?php echo htmlspecialchars($activity['title']); ?></h3>
                    <p><?php echo htmlspecialchars($activity['description']); ?></p>
                    
                    <button class="activity-link open-modal-trigger" 
                            data-title="<?php echo htmlspecialchars($activity['title']); ?>"
                            data-content='<?php echo htmlspecialchars($activity['modal_content_html']); ?>'>
                        <?php echo htmlspecialchars($activity['link_text']); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    
    <div id="activityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle"></h2>
                <span class="close-btn">&times;</span>
            </div>
            <div id="modalBody">
                </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton'); 
            const modal = document.getElementById('activityModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const closeBtn = document.querySelector('.close-btn');
            const modalTriggers = document.querySelectorAll('.open-modal-trigger');
            
            // --- GUARANTEED BACK BUTTON LOGIC ---
            backButton.addEventListener('click', function(e) {
                e.preventDefault(); 
                window.location.href = backButton.href; 
            });
            // ------------------------------------

            // --- Modal Open/Close Logic ---
            
            // 1. Open Modal Handler (Triggered by card or button)
            modalTriggers.forEach(element => {
                element.addEventListener('click', function(e) {
                    e.stopPropagation(); 
                    
                    const triggerElement = e.currentTarget;

                    const title = triggerElement.getAttribute('data-title');
                    const content = triggerElement.getAttribute('data-content'); 

                    modalTitle.textContent = title;
                    modalBody.innerHTML = content;
                    
                    modal.style.display = 'block';
                });
            });

            // 2. Close Modal Handler (X button)
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // 3. Close Modal Handler (Clicking outside the modal)
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
            
        });
    </script>

</body>
</html>