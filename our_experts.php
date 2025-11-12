<?php
// PHP code for processing or logic (Static content page)
// This file displays the list of expert team members for Janani.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Experts - JANANI</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Darker Pink (matching the last update)
                        'primary-pink': '#d81b60', 
                        'light-pink-bg': '#fcf6f6',
                        'card-bg': '#fff', // White card background
                        'text-dark': '#333',
                        'text-muted': '#666',
                        'view-more': '#ea580c', // Orange/Salmon color for link
                    },
                    fontFamily: {
                        // Using a standard, clean font
                        sans: ['Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom font family to closely match the uploaded image's look */
        .expert-title {
            font-family: 'Georgia', serif;
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-light-pink-bg flex flex-col items-center p-5 min-h-screen text-text-dark">

    <div class="w-full max-w-4xl">
        <!-- Back Link (Matching the about_us.php structure) -->
        <a href="5index.php" class="text-text-muted text-sm flex items-center mb-8 hover:text-primary-pink transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        
        <!-- Header Section -->
        <header class="text-left mb-12 px-2 md:px-0">
            <h1 class="text-4xl md:text-5xl expert-title text-text-dark mb-4">
                JANANI's Team
            </h1>
            <p class="text-base text-text-muted max-w-xl">
                JANANI's Team is dedicated to supporting mothers throughout their pregnancy journey. We strive to provide accurate information, timely alerts, and compassionate guidance to ensure the health and well-being of both mother and baby. Our goal is to make every step of this special journey safe, informed, and comforting.
            </p>
        </header>

        <!-- Experts Grid (Only 5 members) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php
            // Expert data array (5 members only)
            $experts = [
                [
                    'name' => 'Ridhdhi Raval',
                    'profile_link' => '#profile1',
                ],
                [
                    'name' => 'Komal Mishra',
                    'profile_link' => '#profile2',
                ],
                [
                    'name' => 'Hensy Patel',
                    'profile_link' => '#profile3',
                ],
                [
                    'name' => 'Diya Tandel',
                    'profile_link' => '#profile4',
                ],
                [
                    'name' => 'Aanandi Hudad',
                    'profile_link' => '#profile5',
                ],
            ];

            foreach ($experts as $expert) {
                echo '<div class="expert-card bg-card-bg p-6 rounded-2xl shadow-lg transition duration-300 hover:shadow-xl">';
                
                // Placeholder for Image (as requested, without actual image)
                // Using a pink circle placeholder
                echo '<div class="w-28 h-28 mx-auto rounded-full bg-light-pink-bg border-4 border-primary-pink flex items-center justify-center mb-6">';
                echo '<i class="fas fa-user-circle text-primary-pink text-6xl"></i>';
                echo '</div>'; // End Image Placeholder
                
                // Name
                echo '<h3 class="expert-title text-xl font-bold text-center text-text-dark mb-4">' . $expert['name'] . '</h3>';
                
                // Titles/Bullet Points
                
                // View More Link
                echo '<div class="mt-6 text-center">';
                echo '</div>';
                
                echo '</div>'; // End expert-card
            }
            ?>
        </div>
        
        <footer class="text-center text-xs text-text-muted mt-16 pb-5">
            &copy; <?php echo date("Y"); ?> JANANI App. All rights reserved.
        </footer>
    </div>
        <?php include '15footer.php'; ?>

</body>
</html>
