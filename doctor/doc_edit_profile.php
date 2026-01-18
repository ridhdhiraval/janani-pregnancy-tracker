<?php
// Replicate the doctor data structure for pre-filling the form
$doctor = [
    "name" => "Dr. Priya Sharma",
    "specialization" => "Gynecologist & Obstetrician",
    "email" => "priya.sharma@example.com",
    "phone" => "+91 98765 43210",
    "experience" => "12 Years",
    "hospital" => "Janani Women’s Care Hospital",
    "hospital_address" => "101, Orchid Tower, Near City Center, Anytown, 360005",
    "bio" => "Experienced gynecologist specializing in prenatal care, high-risk pregnancies, and women’s wellness. Dedicated to providing personalized and compassionate care.",
    // Default image if photo_url is not set or empty (for new doctors or if current photo is removed)
    "photo_url" => "https://randomuser.me/api/portraits/women/44.jpg", 
    "working_hours" => [
        "Mon - Fri" => "9:00 AM - 1:00 PM | 4:00 PM - 7:00 PM",
        "Sat" => "9:00 AM - 1:00 PM (by appointment)",
        "Sun" => "Closed"
    ]
];

// In a real application, you would handle the form submission here:
$message = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Validate incoming data ($_POST)

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Make sure this directory exists and is writable
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Basic validation (more robust validation needed for production)
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $doctor["photo_url"] = $target_file; // Update photo_url with new path
                $message = "File uploaded and profile updated successfully (Placeholder for database action).";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "File is not an image.";
        }
    } else {
        $message = "Profile updated successfully (Placeholder for database action).";
    }

    // 2. Sanitize data (e.g., filter_input for all $_POST fields)
    // 3. Update the database (e.g., UPDATE doctors SET ... WHERE id=...)
    
    // In a real scenario, you'd process inputs and then redirect:
    // header("Location: doc_profile.php");
    // exit();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Doctor Profile - <?= $doctor["name"] ?></title>

<!-- Google Fonts: Inter --><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300,400,500,600,700,800&display=swap" rel="stylesheet">
<!-- Font Awesome --><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* --- NEW UI STYLES FOR EDIT PROFILE --- */
:root {
    --pink-100: #fde6eb;
    --pink-200: #f8d7de;
    --pink-300: #f6c6cd;
    --accent: #ec8fa0;
    --muted: #9b9b9b;
    --text-color: #3f3f3f;
    --background-color: #ffffff;
    --card-shadow: 0 8px 25px rgba(236,80,120,0.08); /* Softer, slightly larger shadow */
    --transition: 0.3s ease;
    font-family: 'Inter', Arial, sans-serif;
}

body {
    margin: 0;
    background: var(--pink-100); 
    color: var(--text-color);
    line-height: 1.6;
}

/* Navbar (Keep as is) */
.navbar {
    background: var(--pink-300);
    padding: 6px 18px;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.navbar a {
    color: var(--text-color);
    text-decoration: none;
    font-size: 20px;
    transition: var(--transition);
    padding: 8px;
    border-radius: 8px;
}

.navbar i:hover {
    color: var(--accent);
}

.left-icons, .right-icons {
    display: flex;
    gap: 20px;
}

/* MAIN CONTAINER */
.container {
    max-width: 800px; /* Narrower container for better form flow */
    margin: auto;
    padding: 40px 20px;
}

/* Header */
.profile-header {
    text-align: center;
    margin-bottom: 40px;
}

.profile-header h1 {
    color: var(--accent);
    font-size: 42px; /* Slightly larger */
    margin: 10px 0;
    font-weight: 800;
}
.profile-header p {
    font-size: 18px;
}

/* NEW FORM LAYOUT */
.edit-form-container {
    background: var(--background-color);
    box-shadow: var(--card-shadow);
    padding: 40px;
    border-radius: 20px;
    border: 2px solid transparent; /* Subtle border */
    transition: var(--transition);
}

.edit-form-container:hover {
    border-color: var(--pink-200); /* Highlight on hover */
}

.form-section {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 1px dashed var(--pink-100); /* Visually separate sections */
}
.form-section:last-of-type {
    border-bottom: none; /* No border for the last section */
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section-title {
    font-weight: 700;
    color: var(--accent);
    font-size: 24px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-section-title i {
    font-size: 22px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 8px; /* More space for labels */
    font-size: 15px;
}

.form-control,
.form-textarea {
    width: 100%;
    padding: 14px 18px; /* Slightly more padding */
    border: 1px solid var(--pink-200);
    border-radius: 10px; /* More rounded */
    box-sizing: border-box;
    font-size: 16px; /* Slightly larger font */
    transition: border-color var(--transition), box-shadow var(--transition);
    background-color: var(--pink-100); /* Light background for inputs */
    color: var(--text-color);
}

.form-control:focus,
.form-textarea:focus {
    border-color: var(--accent);
    outline: none;
    box-shadow: 0 0 0 4px var(--pink-200); /* Accent shadow */
    background-color: var(--background-color); /* White on focus */
}

.form-textarea {
    resize: vertical;
    min-height: 120px; /* Slightly smaller min-height */
}

/* Profile Image Upload */
.profile-image-upload-area {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.profile-image-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--accent); /* More prominent border */
    box-shadow: 0 0 0 5px var(--pink-200); /* Outer ring */
}

.image-upload-box {
    text-align: center;
    padding: 20px;
    border: 2px dashed var(--pink-200);
    border-radius: 10px;
    cursor: pointer;
    transition: background-color var(--transition), border-color var(--transition);
    width: 100%;
    max-width: 300px;
}
.image-upload-box:hover {
    background-color: var(--pink-100);
    border-color: var(--accent);
}

.image-upload-box input[type="file"] {
    display: none; /* Hide default file input */
}

.image-upload-box p {
    margin: 10px 0 0 0;
    color: var(--muted);
    font-size: 14px;
}
.image-upload-box i {
    font-size: 40px;
    color: var(--accent);
}


/* Working Hours */
.form-hours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Auto-fit columns */
    gap: 15px;
}

.form-hours-item {
    display: flex;
    flex-direction: column; /* Stack label and input */
}

.form-hours-item label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 5px;
}

/* Action Buttons */
.form-actions {
    margin-top: 40px;
    text-align: center;
}

.submit-btn,
.cancel-btn {
    display: inline-block;
    padding: 15px 30px; /* Slightly larger buttons */
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 15px rgba(236, 143, 160, 0.3);
    margin: 0 10px; /* Space between buttons */
    font-size: 17px;
}

.submit-btn {
    background: var(--accent);
    color: white;
}

.submit-btn:hover {
    background: #d96b7f;
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(236, 143, 160, 0.4);
}

.cancel-btn {
    background: var(--muted);
    color: white;
}

.cancel-btn:hover {
    background: #777;
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(155, 155, 155, 0.3);
}

/* Footer (Keep as is) */
footer {
    margin-top: 60px;
    background: var(--pink-300);
    padding: 40px 0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .navbar .left-icons, 
    .navbar .right-icons {
        gap: 10px;
    }
    .profile-header h1 {
        font-size: 36px;
    }
    .edit-form-container {
        padding: 25px;
    }
    .form-section-title {
        font-size: 20px;
        margin-bottom: 20px;
    }
    .form-control, .form-textarea {
        padding: 12px 15px;
        font-size: 15px;
    }
    .submit-btn, .cancel-btn {
        width: calc(100% - 20px); /* Adjust for margin */
        display: block;
        margin: 10px auto;
    }
    .form-hours-grid {
        grid-template-columns: 1fr; /* Stack working hours vertically on small screens */
    }
}
</style>
</head>

<body>

<!-- NAVBAR --><div class="navbar">
    <div class="left-icons">
        <a href="doc_dashboard.php" title="Dashboard"><i class="fa-solid fa-house"></i></a>
        <a href="doc_patients.php" title="Patients"><i class="fa-solid fa-user-doctor"></i></a>
        <a href="doc_appointments.php" title="Appointments"><i class="fa-solid fa-calendar-check"></i></a>
    </div>

    <div class="right-icons">
        <a href="doc_alerts.php" title="Alerts"><i class="fa-solid fa-bell"></i></a>
        <a href="doc_settings.php" title="Settings"><i class="fa-solid fa-gear"></i></a>
        <a href="doc_logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</div>

<!-- MAIN --><div class="container">

    <div class="profile-header">
        <h1><i class="fa-solid fa-user-pen"></i> Edit Profile</h1>
        <p style="color:var(--muted);">Update your personal and professional details</p>
    </div>

    <!-- Feedback Message (Only visible after a POST request) --><?php if (isset($message)): ?>
        <div style="background: var(--pink-300); padding: 15px; border-radius: 10px; margin-bottom: 25px; color: var(--text-color); font-weight: 600; text-align: center; border: 1px solid var(--accent);">
            <i class="fa-solid fa-circle-check"></i> <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="edit-form-container">
        <!-- EDIT FORM - ACTION POINTS BACK TO THIS FILE, ADD enctype FOR FILE UPLOAD --><form method="POST" action="doc_edit_profile.php" enctype="multipart/form-data">

            <!-- PROFILE PHOTO SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-image"></i> Profile Photo</h2>
                <div class="profile-image-upload-area">
                    <img id="profileImagePreview" src="<?= htmlspecialchars($doctor["photo_url"]) ?>" class="profile-image-preview" alt="Current Profile Photo">
                    <label for="profile_image" class="image-upload-box">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Click to choose an image or drag & drop here</p>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                    </label>
                </div>
            </div>

            <!-- BASIC INFORMATION SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-circle-info"></i> Basic Information</h2>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($doctor["name"]) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" id="specialization" name="specialization" class="form-control" value="<?= htmlspecialchars($doctor["specialization"]) ?>" required>
                </div>

                <div class="form-group">
                    <label for="experience">Years of Experience</label>
                    <input type="number" id="experience" name="experience" class="form-control" value="<?= (int)filter_var($doctor["experience"], FILTER_SANITIZE_NUMBER_INT) ?>" min="0" required>
                </div>
            </div>

            <!-- CONTACT DETAILS SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-address-book"></i> Contact Details</h2>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor["email"]) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($doctor["phone"]) ?>" required>
                </div>
            </div>
            
            <!-- HOSPITAL & LOCATION SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-hospital"></i> Clinic Details</h2>
                
                <div class="form-group">
                    <label for="hospital">Hospital/Clinic Name</label>
                    <input type="text" id="hospital" name="hospital" class="form-control" value="<?= htmlspecialchars($doctor["hospital"]) ?>" required>
                </div>

                <div class="form-group">
                    <label for="hospital_address">Full Clinic Address</label>
                    <input type="text" id="hospital_address" name="hospital_address" class="form-control" value="<?= htmlspecialchars($doctor["hospital_address"]) ?>" required>
                </div>
            </div>

            <!-- PROFESSIONAL BIO SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-user-md"></i> Professional Bio</h2>
                <div class="form-group">
                    <label for="bio">Summary of Experience and Philosophy</label>
                    <textarea id="bio" name="bio" class="form-textarea" required><?= htmlspecialchars($doctor["bio"]) ?></textarea>
                </div>
            </div>

            <!-- WORKING HOURS SECTION --><div class="form-section">
                <h2 class="form-section-title"><i class="fa-solid fa-clock"></i> Working Hours</h2>
                <p style="color:var(--muted); font-size: 14px; margin-bottom: 20px;">Enter your daily time slots (e.g., 9:00 AM - 5:00 PM or "Closed")</p>

                <div class="form-hours-grid">
                    <?php 
                    // Loop through the working hours to create fields
                    foreach ($doctor["working_hours"] as $day => $time): 
                    ?>
                    <div class="form-hours-item">
                        <label for="<?= strtolower(str_replace([' ', '-'], '_', $day)) ?>_hours"><?= htmlspecialchars($day) ?></label>
                        <input type="text" id="<?= strtolower(str_replace([' ', '-'], '_', $day)) ?>_hours" name="working_hours[<?= htmlspecialchars($day) ?>]" class="form-control" value="<?= htmlspecialchars($time) ?>" placeholder="e.g., 9:00 AM - 1:00 PM">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- FORM ACTIONS --><div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
                <a href="doc_profile.php" class="cancel-btn">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
            </div>

        </form>
    </div> <!-- /edit-form-container --></div>

<!-- FOOTER --><footer>
    <div class="container" style="text-align: center;">
        <h2 style="color:var(--accent); font-weight: 700;">Janani Doctor Panel</h2>
        <p style="color: var(--text-color); font-size: 14px; margin-top: 10px;">&copy; <?= date("Y"); ?> All rights reserved.</p>
    </div>
</footer>

<script>
    // JavaScript for real-time image preview
    document.addEventListener('DOMContentLoaded', function() {
        const profileImageInput = document.getElementById('profile_image');
        const profileImagePreview = document.getElementById('profileImagePreview');

        profileImageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // If no file selected, revert to default or previous image
                profileImagePreview.src = "<?= htmlspecialchars($doctor["photo_url"]) ?>";
            }
        });
    });
</script>

</body>
</html>