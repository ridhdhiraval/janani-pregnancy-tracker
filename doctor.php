<?php
// PHP DATA: Doctors Data (All English)
$doctorsData = [
    [
        'id' => 1, 
        'name' => 'Dr. Amit Sharma', 
        'specialty' => 'Cardiologist', 
        'experience' => '15 Years', 
        'location' => 'New Delhi',
        'contact' => '+91-9876543210'
    ],
    [
        'id' => 2, 
        'name' => 'Dr. Priya Singh', 
        'specialty' => 'Pediatrician', 
        'experience' => '10 Years', 
        'location' => 'Mumbai',
        'contact' => '+91-9988776655'
    ],
    [
        'id' => 3, 
        'name' => 'Dr. Rajesh Verma', 
        'specialty' => 'Dermatologist', 
        'experience' => '8 Years', 
        'location' => 'Bangalore',
        'contact' => '+91-9000111222'
    ],
    [
        'id' => 4, 
        'name' => 'Dr. Neeta Kapoor', 
        'specialty' => 'Neurologist', 
        'experience' => '18 Years', 
        'location' => 'Pune',
        'contact' => '+91-8765432109'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
    
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            display: block;
            max-width: 900px;
            margin: 0 auto;
        }
        .back-button-container {
            max-width: 900px;
            margin: 0 auto 10px auto;
            text-align: left;
        }
        .back-button {
            background-color: #f8f9fa;
            color: #333; 
            border: 1px solid #ccc;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
        }
        .back-button:hover {
            background-color: #e9ecef;
        }
        .back-button span {
            margin-right: 5px;
            font-weight: bold;
        }
        .doctor-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .doctor-info {
            flex-grow: 1;
            padding-right: 20px;
        }
        .doctor-card h3 {
            color: #000;
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 1.5em;
        }
        .specialty {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .details-widget {
            background-color: #f8f9fa; 
            border-left: 3px solid #333;
            padding: 10px;
            margin-bottom: 5px;
        }
        .details-widget p {
            color: #333;
            font-size: 0.9em;
            margin: 5px 0;
        }
        .details-widget a {
            color: #333;
            text-decoration: underline;
        }
        .add-button-container {
            flex-shrink: 0;
        }
        .add-button {
            background-color: #ffffff;
            color: #000;
            border: 1px solid #333;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 50px;
            transition: background-color 0.3s, border-color 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        /* Specific style for 'My Doctor' button - optional color change for emphasis */
        .add-button.my-doctor-btn {
            background-color: #e6f7ff; /* Light blue background for emphasis */
            border-color: #007bff; /* Blue border */
            color: #007bff; /* Blue text */
        }

        .add-button:hover:not(:disabled) {
            background-color: #f0f0f0;
            border-color: #000;
        }
        
        .add-button.my-doctor-btn:hover:not(:disabled) {
            background-color: #cceeff;
        }

        .add-button:disabled {
            background-color: #f8f8f8; 
            border: 1px solid #ccc;
            color: #6c757d; 
            cursor: not-allowed;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid;
            border-radius: 5px;
            display: none; 
            text-align: center;
            max-width: 600px;
            margin: 20px auto 0 auto;
            font-weight: bold;
        }
    </style>
</head>
<body>
    
    <div class="back-button-container">
        <button class="back-button" onclick="window.history.back()">
            <span>&#8592;</span> Back to Previous Page
        </button>
    </div>
    <center><h1>🏥 Professional Doctor List 👨‍⚕️</h1></center>
    
    <div class="container" id="doctors-container">
        <?php 
        foreach ($doctorsData as $doctor): 
            $buttonText = 'Add Doctor to List';
            $buttonClass = 'add-button';
            $jsAction = 'handleAddDoctor(event)'; // Default AJAX action
            
            if ($doctor['name'] === 'Dr. Priya Singh') {
                $buttonText = 'My Doctor';
                $buttonClass .= ' my-doctor-btn';
                $jsAction = 'handleAddDoctor(event)';
            }
        ?>
            <div class="doctor-card">
                <div class="doctor-info">
                    <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                    <span class="specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></span>
                    
                    <div class="details-widget">
                        <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($doctor['location']); ?></p>
                        <p><strong>Contact:</strong> <a href="tel:<?php echo htmlspecialchars($doctor['contact']); ?>"><?php echo htmlspecialchars($doctor['contact']); ?></a></p>
                    </div>
                </div>
                
                <div class="add-button-container">
                    <button 
                        class="<?php echo $buttonClass; ?>" 
                        data-doctor-id="<?php echo htmlspecialchars($doctor['id']); ?>" 
                        data-doctor-name="<?php echo htmlspecialchars($doctor['name']); ?>"
                        
                        onclick="<?php echo $jsAction; ?>"
                    >
                        <?php echo $buttonText; ?>
                    </button>
                </div>
            </div>
        <?php 
            endforeach; 
        ?>
    </div>

    <div class="message" id="action-message"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const messageBox = document.getElementById('action-message');
            // Remove previous event listeners attachment as we are using inline onclick now.
            // But we keep the function for non-'My Doctor' buttons.
            
            // Function to display messages (success or error)
            function displayMessage(text, isSuccess) {
                messageBox.textContent = text;
                messageBox.style.display = 'block';
                // ... (message styling logic remains the same)
                if (isSuccess) {
                    messageBox.style.backgroundColor = '#d4edda';
                    messageBox.style.borderColor = '#c3e6cb';
                } else {
                    messageBox.style.backgroundColor = '#f8d7da';
                    messageBox.style.borderColor = '#f5c6cb';
                }
                messageBox.style.color = '#000';
                
                setTimeout(() => {
                    messageBox.style.display = 'none';
                }, 4000);
            }

            // Function to handle the button click and send data to PHP (add_doctor.php) 
            // This is ONLY for 'Add Doctor to List' buttons.
            window.handleAddDoctor = function(event) {
                const button = event.target;
                const doctorId = button.getAttribute('data-doctor-id');
                const doctorName = button.getAttribute('data-doctor-name');
                
                // Prevent redirection if it's the 'My Doctor' button which has its own inline onclick
                if (doctorName === 'Dr. Priya Singh') return; 

                button.disabled = true;
                button.textContent = 'Processing...';

                fetch('add_doctor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ doctor_id: doctorId, doctor_name: doctorName, action: 'add' }),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Server returned an error status.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    button.disabled = false;
                    button.textContent = 'Add Doctor to List';
                    displayMessage(`Success: ${data.message}`, true);
                })
                .catch((error) => {
                    console.error('AJAX Error:', error.message);
                    button.disabled = false;
                    button.textContent = 'Add Doctor to List';
                    displayMessage(`Error: Could not add doctor. (${error.message})`, false);
                });
            }
            
            // Re-attach listeners to ensure existing functionality works for non-'My Doctor' buttons
            document.querySelectorAll('.add-button').forEach(button => {
                if (button.getAttribute('data-doctor-name') !== 'Dr. Priya Singh') {
                    // Note: Since we are using an inline onclick for all buttons now, 
                    // this dedicated listener is technically redundant but good for robustness if the inline fails.
                    // For clean code, the logic moved to the inline onclick attribute above.
                    // The function `handleAddDoctor` is globally exposed using `window.`
                }
            });
        });
    </script>
</body>
</html>
