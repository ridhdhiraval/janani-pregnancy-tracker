<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Ultrasound Gallery"; 
$back_link = "6child.php"; // Back link to the main profile page

// --- Real file-based gallery for 4 ultrasound images ---
$imagesDir = __DIR__ . '/images';
$webImagesDir = '/JANANI/images';
$allowedExts = ['png','jpg','jpeg'];

// Upload handling: save into next available slot (1..4). If all exist, overwrite slot 4.
$upload_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['scan_file'])) {
    $file = $_FILES['scan_file'] ?? null;
    if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $tmp = $file['tmp_name'];
        $name = $file['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $isImage = @getimagesize($tmp) !== false;
        if (!$isImage || !in_array($ext, $allowedExts, true)) {
            $upload_message = "Invalid image file. Please upload PNG/JPG.";
        } elseif (!is_dir($imagesDir)) {
            $upload_message = "Images folder not found.";
        } else {
            // Determine next available slot number 1..4
            $slot = null;
            for ($i = 1; $i <= 4; $i++) {
                $baseUltra = $imagesDir . "/ultra sound img $i";
                $baseUlta  = $imagesDir . "/ulta sound img $i"; // handle existing typo variant
                $exists = false;
                foreach ($allowedExts as $e) {
                    if (file_exists("{$baseUltra}.{$e}") || file_exists("{$baseUlta}.{$e}")) {
                        $exists = true; break;
                    }
                }
                if (!$exists && $slot === null) { $slot = $i; }
            }
            if ($slot === null) { $slot = 4; } // all present, overwrite last

            $targetBase = $imagesDir . "/ultra sound img {$slot}"; // normalize to 'ultra'
            $targetPath = $targetBase . "." . $ext;
            // Move uploaded file
            if (@move_uploaded_file($tmp, $targetPath)) {
                $upload_message = "File '" . htmlspecialchars($name) . "' uploaded (Ultra Sound {$slot}).";
            } else {
                $upload_message = "Upload failed. Please try again.";
            }
        }
    } else {
        $upload_message = "No file selected or upload error.";
    }
}

// Build gallery items from files present in images folder
$scan_photos = [];
for ($i = 1; $i <= 4; $i++) {
    $caption = "Ultra Sound {$i}";
    $path = null;
    // Prefer correctly spelled 'ultra', but fall back to 'ulta' if present
    foreach ($allowedExts as $e) {
        $p1 = $imagesDir . "/ultra sound img {$i}.{$e}";
        $p2 = $imagesDir . "/ulta sound img {$i}.{$e}";
        if (file_exists($p1)) { $path = $webImagesDir . "/ultra sound img {$i}.{$e}"; break; }
        if (file_exists($p2)) { $path = $webImagesDir . "/ulta sound img {$i}.{$e}"; break; }
    }
    if ($path) {
        $scan_photos[] = [
            'id' => $i,
            'filename' => $path,
            'caption' => $caption,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Base Theme Styles (Consistent) */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed; /* Light, warm background */
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
            max-width: 900px; /* Wider for gallery grid */
            margin: 0 auto;
        }
        
        /* --- Upload Section Styles --- */
        .upload-section {
            background-color: #a8dadc; /* Theme color */
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .upload-section h3 {
            margin-top: 0;
            color: #333;
            font-weight: 600;
            font-size: 18px;
        }

        .upload-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .file-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }
        
        .upload-btn {
            background-color: #e69999; /* Pinkish accent */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .upload-btn:hover {
            background-color: #d17a7a;
        }
        
        .upload-message {
            margin-top: 10px;
            color: #333;
            font-weight: bold;
        }

        /* --- Gallery Grid Styles --- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Responsive grid */
            gap: 20px;
        }
        
        .scan-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            cursor: pointer;
        }

        .scan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .scan-image {
            width: 100%;
            height: 150px; /* Fixed height for consistent look */
            object-fit: cover; /* Image ko fit karega */
            display: block;
        }
        
        .scan-caption {
            padding: 10px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            color: #4b4b4b;
        }
        
        .no-photos {
            text-align: center;
            padding: 50px;
            color: #999;
            background-color: white;
            border-radius: 8px;
            grid-column: 1 / -1; /* Pure white background with centered text */
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
        
        <div class="upload-section">
            <h3>Upload New Scan Photo</h3>
            <form action="gallery.php" method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="scan_file" accept="image/*" required class="file-input">
                <button type="submit" class="upload-btn">Upload</button>
            </form>
            <?php if ($upload_message): ?>
                <p class="upload-message"><?php echo htmlspecialchars($upload_message); ?></p>
            <?php endif; ?>
        </div>

        <div class="gallery-grid">
            <?php if (!empty($scan_photos)): ?>
                <?php foreach ($scan_photos as $photo): ?>
                    <div class="scan-card" onclick="viewPhoto(<?php echo htmlspecialchars($photo['id']); ?>)">
                        <img src="<?php echo htmlspecialchars($photo['filename']); ?>" 
                             alt="<?php echo htmlspecialchars($photo['caption']); ?>" 
                             class="scan-image">
                        <div class="scan-caption">
                            <?php echo htmlspecialchars($photo['caption']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-photos">
                    No ultrasound photos found. Please upload images for Ultra Sound 1–4.
                </div>
            <?php endif; ?>
        </div>
        
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton'); 
            
            // --- GUARANTEED BACK BUTTON LOGIC (Pichhle pages ki tarah) ---
            backButton.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    // Fallback to the defined PHP link (6child.php)
                    window.location.href = backButton.href; 
                }
            });

            // Photo viewing function (Aap ismein modal/lightbox functionality add kar sakte hain)
            window.viewPhoto = function(photoId) {
                alert("Viewing photo ID: " + photoId + "\n(In a real application, this would open a fullscreen view or modal.)");
            };
        });
    </script>

</body>
</html>