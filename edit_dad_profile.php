<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Partner"; // Title for the current page
$back_link = "family.php"; // Link back to the family list page

// Initial data (can be fetched from a database)
$member_initial = "P";
$initial_partner_name = "Rahul Verma"; // Example initial name
// You might also have a default profile image path from the database
$initial_profile_image = ""; // Leave empty if no image, or provide a default image URL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* General body reset and font */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed; 
        }

        /* --- Full Width Header Bar Wrapper --- */
        .header-bar-wrapper {
            width: 100%;
            background-color: #f7f3ed; 
            border-bottom: 1px solid #e0d9cd;
        }
        
        /* --- Top Header Bar Content (Constrained width) --- */
        .app-header {
            display: flex;
            align-items: center;
            padding: 25px 30px; 
            max-width: 1200px; 
            margin: 0 auto;
        }
        
        .app-header .header-content {
            display: flex;
            align-items: center;
            flex-grow: 1;
            position: relative;
        }

        .app-header h1 {
            font-size: 24px; 
            font-weight: normal;
            color: #4b4b4b;
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap; 
        }

        .back-arrow {
            font-size: 36px; 
            text-decoration: none;
            color: #4b4b4b;
            line-height: 1;
            position: relative;
            z-index: 10;
        }

        /* --- Main Content Area --- */
        .content-area {
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 30px; 
            text-align: center;
        }

        /* --- Avatar Styling --- */
        .large-avatar-container {
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .large-avatar {
            width: 150px; 
            height: 150px;
            border-radius: 50%;
            background-color: #e69999; /* Pink/Red background */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Important for image to stay within bounds */
            position: relative; /* For positioning img inside */
        }

        .large-avatar .initial {
            font-size: 72px; 
            font-weight: bold;
            color: #ffffff; 
            position: absolute; /* Center initial text */
        }
        
        .large-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Cover the avatar area */
            border-radius: 50%; /* Keep image circular */
            display: none; /* Hidden by default, shown when image is selected */
            position: absolute;
            top: 0;
            left: 0;
        }

        /* photo-link ab ek div hai jo click trigger karega */
        .photo-link {
            display: block;
            text-decoration: none;
            color: #4b4b4b;
            font-size: 14px;
            margin-top: 10px;
            cursor: pointer;
        }

        .camera-icon {
            font-size: 24px;
            color: #4b4b4b;
            display: block;
        }
        
        /* Hidden file input */
        #photoInput {
            display: none;
        }
        
        /* --- Settings/Data List Container --- */
        .data-list-container {
            background-color: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            margin-top: 20px; 
        }
        
        .data-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid #e0d9cd;
            cursor: pointer;
            text-decoration: none;
            color: #4b4b4b;
            transition: background-color 0.1s;
        }
        
        .data-list-item:hover {
            background-color: #fcfcfc;
        }

        .data-list-item:last-child {
            border-bottom: none; 
        }

        .item-label {
            font-size: 18px;
            font-weight: normal;
        }

        .item-value {
            font-size: 18px;
            color: #888; 
            margin-right: 15px; 
        }

        .arrow-right {
            font-size: 28px;
            color: #b0b0b0;
            line-height: 1;
            flex-shrink: 0;
        }
        
        /* ================================================= */
        /* --- Name Input Modal Styles --- */
        /* ================================================= */

        .modal-overlay {
            display: none; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); 
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .name-modal {
            background-color: white;
            width: 90%;
            max-width: 320px; 
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .modal-header-text {
            font-size: 18px;
            font-weight: bold;
            color: #4b4b4b;
            margin-bottom: 15px;
        }
        
        .modal-input-container {
            margin-bottom: 25px;
        }

        .name-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; 
            font-size: 16px;
            color: #4b4b4b;
            outline: none;
            height: 40px; 
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            padding-top: 10px;
        }

        .modal-button {
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.1s;
        }

        #cancelNameBtn {
            color: #4b4b4b; 
        }

        #okNameBtn {
            background-color: #333; 
            color: white;
            padding: 10px 20px; 
        }
        
        #okNameBtn:hover {
            background-color: #222;
        }

    </style>
</head>
<body>

<div class="profile-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <div class="header-content">
                <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
        </header>
    </div>

    <div class="content-area">
        
        <div class="large-avatar-container">
            <div class="large-avatar" id="avatarContainer">
                <span class="initial" id="avatarInitial"><?php echo htmlspecialchars($member_initial); ?></span>
                <img id="profileImage" src="<?php echo htmlspecialchars($initial_profile_image); ?>" alt="Profile Image" 
                     style="<?php echo !empty($initial_profile_image) ? 'display: block;' : 'display: none;'; ?>">
            </div>
            <div class="photo-link" id="photoLink">
                <span class="camera-icon">&#128247;</span> 
                TAKE A PHOTO
            </div>
            <input type="file" id="photoInput" accept="image/*">
        </div>
        
        <div class="data-list-container">
            
            <div class="data-list-item" id="row-name">
                <span class="item-label">Partner's name</span>
                <span class="item-value" id="partnerNameValue"><?php echo htmlspecialchars($initial_partner_name); ?></span>
                <span class="arrow-right">&#x232a;</span>
            </div>
            
        </div>
    </div>
</div>

<div class="modal-overlay" id="nameModalOverlay">
    <div class="name-modal">
        <div class="modal-header-text">Partner's name</div>
        
        <div class="modal-input-container">
            <input type="text" class="name-input" id="nameInput" placeholder="Enter partner's name">
        </div>

        <div class="modal-footer">
            <button class="modal-button" id="cancelNameBtn">CANCEL</button>
            <button class="modal-button" id="okNameBtn">OK</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backArrow = document.querySelector('.back-arrow');
        const photoLink = document.getElementById('photoLink');
        const photoInput = document.getElementById('photoInput');
        const avatarInitial = document.getElementById('avatarInitial');
        const profileImage = document.getElementById('profileImage'); // New element to display image
        const nameRow = document.getElementById('row-name');
        const weightRow = document.getElementById('row-weight');
        
        // Modal elements
        const nameModalOverlay = document.getElementById('nameModalOverlay');
        const nameInput = document.getElementById('nameInput');
        const partnerNameValue = document.getElementById('partnerNameValue');
        const cancelNameBtn = document.getElementById('cancelNameBtn');
        const okNameBtn = document.getElementById('okNameBtn');
        
        // --- Modal Control Functions ---
        
        function openNameModal() {
            nameInput.value = partnerNameValue.textContent.trim();
            nameModalOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => nameInput.focus(), 100); 
        }

        function closeNameModal() {
            nameModalOverlay.style.display = 'none';
            document.body.style.overflow = '';
        }
        
        // --- Event Listeners ---

        // 1. TAKE A PHOTO Click -> Trigger File Input (Gallery/Camera)
        photoLink.addEventListener('click', function() {
            console.log("Triggering photo selection...");
            photoInput.click(); 
        });
        
        // 2. Handle file selection change (Display selected image)
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                    profileImage.style.display = 'block'; // Show the image
                    avatarInitial.style.display = 'none'; // Hide the initial
                    console.log("Profile image updated.");
                    // In a real app, you would upload this 'e.target.result' (base64) or the file itself to the server
                };

                reader.readAsDataURL(this.files[0]); // Read the selected file as a Data URL
            } else {
                // If no file is selected (e.g., user cancels), revert to initial or default image
                profileImage.style.display = 'none';
                avatarInitial.style.display = 'block';
                profileImage.src = "<?php echo htmlspecialchars($initial_profile_image); ?>"; // Reset to initial image if available
                if (profileImage.src) profileImage.style.display = 'block'; // If initial image exists, show it
                console.log("No photo selected, reverting to initial.");
            }
        });

        // 3. Partner's Name Row Click -> Open Modal
        nameRow.addEventListener('click', openNameModal);

        // 4. OK Button Click -> Save and Close
        okNameBtn.addEventListener('click', function() {
            const newName = nameInput.value.trim();
            if (newName) {
                partnerNameValue.textContent = newName;
                // In a real app, send newName to the server here
            }
            closeNameModal();
        });

        // 5. CANCEL Button Click -> Discard and Close
        cancelNameBtn.addEventListener('click', closeNameModal);

        // 6. Close Modal on Overlay Click
        nameModalOverlay.addEventListener('click', function(event) {
            if (event.target === nameModalOverlay) {
                closeNameModal();
            }
        });


        // Initial check: if there's an initial_profile_image, display it instead of initial
        if (profileImage.src && profileImage.src !== window.location.href) { // Check if src is not empty/default
            profileImage.style.display = 'block';
            avatarInitial.style.display = 'none';
        } else {
            profileImage.style.display = 'none';
            avatarInitial.style.display = 'block';
        }
    });
</script>

</body>
</html>