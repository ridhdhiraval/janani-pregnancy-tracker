<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Growth Tracking"; 
$back_link = "6child.php"; 

// Load baby data for logged-in user like 6child.php does
// require_once __DIR__ . '/lib/auth.php';
// require_once __DIR__ . '/config/db.php';
// start_secure_session();
// $user = current_user();
$user = ['id' => 1]; // Mock user
require_once __DIR__ . '/config/db.php';
$baby_data = null;
$child_name = "No Baby Data";
$child_age = "Unknown";
$child_gender = "Unknown";

if ($user) {
    // Check if baby data exists
    $stmt = $pdo->prepare('SELECT * FROM babies WHERE user_id = ? ORDER BY dob DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $baby_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($baby_data) {
        // Calculate baby age and tracking data based on birth date
        $birth_date = new DateTimeImmutable($baby_data['dob']);
        $today = new DateTimeImmutable('today');
        $age_interval = $birth_date->diff($today);
        
        // Calculate months and days since birth
        $months = $age_interval->y * 12 + $age_interval->m;
        $days = $age_interval->d;
        
        // Set child info
        $child_name = $baby_data['name'];
        $child_gender = ucfirst($baby_data['sex']);
        
        if ($months > 0) {
            $child_age = $months . " Month" . ($months > 1 ? "s" : "");
            if ($days > 0) {
                $child_age .= " + " . $days . " day" . ($days > 1 ? "s" : "");
            }
        } else {
            $child_age = $days . " day" . ($days > 1 ? "s" : "") . " old";
        }
    }
}

// Fetch growth data from baby_growth table and calculate age in months
$growth_data = [];
if ($baby_data) {
    $stmt = $pdo->prepare('SELECT * FROM baby_growth WHERE baby_id = ? ORDER BY recorded_at ASC');
    $stmt->execute([$baby_data['id']]);
    $growth_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $birth_date = new DateTimeImmutable($baby_data['dob']);
    
    foreach ($growth_records as $record) {
        // Calculate age in months at the time of measurement
        $measurement_date = new DateTimeImmutable($record['recorded_at']);
        $age_interval = $birth_date->diff($measurement_date);
        $age_in_months = $age_interval->y * 12 + $age_interval->m + ($age_interval->d / 30.44); // Convert days to fraction of month
        
        $growth_data[] = [
            "date" => $record['recorded_at'],
            "age_months" => round($age_in_months, 1), // Round to 1 decimal place
            "weight" => $record['weight_grams'] / 1000, // Convert grams to kg
            "height" => $record['height_mm'] / 10     // Convert mm to cm
        ];
    }
}

// If no growth data exists, show empty array
if (empty($growth_data)) {
    $growth_data = [];
}

// PHP mein growth data ko JSON format mein convert karein, jisse JavaScript use kar sake.
$js_growth_data = json_encode($growth_data);

// Link to add new data 
$add_data_link = "#openGrowthModal"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        /* Base Theme Styles (Consistent with previous files) */
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

        .back-arrow {
            font-size: 28px; 
            text-decoration: none;
            color: #4b4b4b;
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
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Child Info Card */
        .child-info-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid #a8dadc; 
        }
        
        .info-detail h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #2a9d8f;
        }
        
        .info-detail p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }

        /* Section Header and Button */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 0 5px;
        }
        
        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #4b4b4b;
            margin: 0;
        }

        .add-data-btn {
            background-color: #e69999; 
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-data-btn:hover {
            background-color: #d17a7a;
        }

        /* Chart Visualization Area - Ab yeh canvas ko hold karega */
        .chart-container {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            height: 300px; 
            /* Flex properties ko hata diya hai taki canvas sahi se render ho */
        }
        
        /* Data History Table */
        .data-history-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .data-history-table th, .data-history-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .data-history-table th {
            background-color: #a8dadc;
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }

        .data-history-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-history-table .action-cell {
            text-align: right;
        }
        
        .data-history-table .action-cell a {
            color: #e69999;
            text-decoration: none;
            font-size: 14px;
        }
        
    </style>
</head>
<body>

    <header class="app-header">
        <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
        <div class="header-title"><?php echo htmlspecialchars($page_title); ?></div>
        <div></div> 
    </header>

    <div class="content-area">
        
        <?php if ($baby_data): ?>
        <div class="child-info-card">
            <div class="info-detail">
                <h2><?php echo htmlspecialchars($child_name); ?></h2>
                <p>Age: <?php echo htmlspecialchars($child_age); ?> | Gender: <?php echo htmlspecialchars($child_gender); ?></p>
            </div>
            <div style="font-size: 30px;">👶</div>
        </div>
        <?php else: ?>
        <div class="child-info-card" style="border-left-color: #ff6b6b;">
            <div class="info-detail">
                <h2>No Baby Data Found</h2>
                <p>Please record your baby's delivery details first to track growth.</p>
                <p><a href="baby_delivery.php" style="color: #e69999; text-decoration: none; font-weight: bold;">Add Baby Details →</a></p>
            </div>
            <div style="font-size: 30px;">⚠️</div>
        </div>
        <?php endif; ?>

        <div class="section-header">
            <h3>Age-Based Growth Chart (Weight/Height vs WHO Standards)</h3>
            <?php if ($baby_data): ?>
            <a href="<?php echo htmlspecialchars($add_data_link); ?>" class="add-data-btn">
                + Add Data
            </a>
            <?php endif; ?>
        </div>
        
        <div class="chart-container">
            <canvas id="growthChart"></canvas>
        </div>
        
        <div class="section-header">
            <h3>Measurement History</h3>
        </div>
        
        <table class="data-history-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Weight (kg)</th>
                    <th>Height (cm)</th>
                    <th class="action-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($growth_data as $data): ?>
                    <tr>
                        <td><?php echo date("d M Y", strtotime(htmlspecialchars($data['date']))); ?></td>
                        <td><?php echo htmlspecialchars($data['weight']); ?></td>
                        <td><?php echo htmlspecialchars($data['height']); ?></td>
                        <td class="action-cell">
                            <a href="edit_growth.php?date=<?php echo htmlspecialchars($data['date']); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($growth_data)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999;">No growth data recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // PHP se JSON data ko JavaScript object mein convert kiya
            const rawData = <?php echo $js_growth_data; ?>;
            
            // Labels (X-Axis) aur Data Points (Y-Axis) prepare karein
            const labels = rawData.map(item => {
                // Age in months ko X-axis label banane ke liye
                return item.age_months + ' months';
            });
            const weights = rawData.map(item => item.weight);
            const heights = rawData.map(item => item.height);

            // Chart.js Context
            const ctx = document.getElementById('growthChart').getContext('2d');

            // WHO Growth Reference Data (approximate values for healthy babies)
            const whoWeightReference = {
                '0': 3.2, '0.5': 3.4, '1': 4.2, '1.5': 4.6, '2': 5.1, '2.5': 5.4, '3': 5.8, '3.5': 6.1, '4': 6.4, '4.5': 6.6, '5': 6.9,
                '5.5': 7.1, '6': 7.3, '6.5': 7.5, '7': 7.6, '7.5': 7.8, '8': 7.9, '8.5': 8.0, '9': 8.2, '9.5': 8.3, '10': 8.5, '10.5': 8.6,
                '11': 8.7, '11.5': 8.8, '12': 8.9, '13': 9.1, '14': 9.3, '15': 9.5, '16': 9.7, '17': 9.9, '18': 10.1, '19': 10.3, '20': 10.5,
                '21': 10.7, '22': 10.9, '23': 11.1, '24': 11.3
            };
            
            const whoHeightReference = {
                '0': 50, '0.5': 52, '1': 54, '1.5': 56, '2': 58, '2.5': 59, '3': 61, '3.5': 62, '4': 63, '4.5': 64, '5': 65,
                '5.5': 66, '6': 67, '6.5': 68, '7': 69, '7.5': 70, '8': 71, '8.5': 71.5, '9': 72, '9.5': 72.5, '10': 74, '10.5': 74.5,
                '11': 75, '11.5': 75.5, '12': 76, '13': 77, '14': 78, '15': 79, '16': 80, '17': 81, '18': 82, '19': 83, '20': 84,
                '21': 85, '22': 86, '23': 86.5, '24': 87
            };
            
            // Function to interpolate WHO reference values for decimal ages
            function getWHOReferenceValue(ageMonths, referenceData) {
                const age = parseFloat(ageMonths);
                
                // If exact match exists, return it
                if (referenceData[age] !== undefined) {
                    return referenceData[age];
                }
                
                // Find nearest lower and upper ages
                const ages = Object.keys(referenceData).map(Number).sort((a, b) => a - b);
                let lowerAge = ages[0];
                let upperAge = ages[ages.length - 1];
                
                for (let i = 0; i < ages.length; i++) {
                    if (ages[i] <= age) lowerAge = ages[i];
                    if (ages[i] >= age) {
                        upperAge = ages[i];
                        break;
                    }
                }
                
                // Linear interpolation
                if (lowerAge === upperAge) {
                    return referenceData[lowerAge];
                }
                
                const lowerValue = referenceData[lowerAge];
                const upperValue = referenceData[upperAge];
                const ratio = (age - lowerAge) / (upperAge - lowerAge);
                
                return lowerValue + (upperValue - lowerValue) * ratio;
            }
            
            // Generate WHO reference data for the same age points as actual data
            const whoWeightData = labels.map((label, index) => {
                const ageMonths = parseFloat(label.replace(' months', ''));
                return getWHOReferenceValue(ageMonths, whoWeightReference);
            });
            
            const whoHeightData = labels.map((label, index) => {
                const ageMonths = parseFloat(label.replace(' months', ''));
                return getWHOReferenceValue(ageMonths, whoHeightReference);
            });

            // Chart Create karna
            new Chart(ctx, {
                type: 'line', // Line chart for growth tracking
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Baby Weight (kg)',
                            data: weights,
                            borderColor: '#e69999', // Pinkish color for weight
                            backgroundColor: 'rgba(230, 153, 153, 0.5)',
                            tension: 0.4, // Smooth line
                            fill: false,
                            yAxisID: 'yWeight',
                            borderWidth: 3,
                        },
                        {
                            label: 'WHO Weight Reference (kg)',
                            data: whoWeightData,
                            borderColor: '#ff9999',
                            backgroundColor: 'rgba(255, 153, 153, 0.2)',
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'yWeight',
                            borderDash: [5, 5], // Dashed line
                            borderWidth: 2,
                        },
                        {
                            label: 'Baby Height (cm)',
                            data: heights,
                            borderColor: '#2a9d8f', // Greenish color for height
                            backgroundColor: 'rgba(42, 157, 143, 0.5)',
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'yHeight',
                            borderWidth: 3,
                        },
                        {
                            label: 'WHO Height Reference (cm)',
                            data: whoHeightData,
                            borderColor: '#66b3b3',
                            backgroundColor: 'rgba(102, 179, 179, 0.2)',
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'yHeight',
                            borderDash: [5, 5], // Dashed line
                            borderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Age (Months)'
                            }
                        },
                        yWeight: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Weight (kg)'
                            },
                            // Suggested min/max value set kar sakte hain
                            // min: 5, 
                        },
                        yHeight: {
                            type: 'linear',
                            display: true,
                            position: 'right', // Right side par height scale
                            title: {
                                display: true,
                                text: 'Height (cm)'
                            },
                            grid: {
                                drawOnChartArea: false, // Right scale ke liye gridlines off
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Add Data Button Handler
            const addDataBtn = document.querySelector('.add-data-btn');
            if (addDataBtn) {
                addDataBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert("Opening modal to add new growth data...");
                });
            }
        });
    </script>

</body>
</html>