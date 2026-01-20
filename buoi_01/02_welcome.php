<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INS3064 Welcome Page</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0; padding: 20px; min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 600px; text-align: center; }
        h1 { color: #667eea; margin-bottom: 20px; }
        .info { background: #f0f0f0; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .info p { margin: 10px 0; text-align: left; }
        .label { font-weight: bold; color: #667eea; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to INS3064</h1>
        
        <div class="info">
            <?php
            // --- STEP 2: VARIABLE ASSIGNMENTS ---
            $name = "Trần Phúc Khánh";      // Replace with your actual name
            $studentId = "23070121";        // Use your student ID here
            $class = "INS3064_01";         // Replace with your class code
            $email = "23070121@vnu.edu.vn"; // Replace with your email
            
            // --- STEP 2: DATE AND TIME FUNCTIONS ---
            // Setting timezone to ensure accurate local time
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentDate = date("l, F d, Y");
            $currentTime = date("H:i:s");

            // --- STEP 2: OUTPUT STATEMENTS ---
            echo "<p><span class='label'>Name:</span> " . $name . "</p>";
            echo "<p><span class='label'>Student ID:</span> " . $studentId . "</p>";
            echo "<p><span class='label'>Class:</span> " . $class . "</p>";
            echo "<p><span class='label'>Email:</span> " . $email . "</p>";
            echo "<p><span class='label'>Date:</span> " . $currentDate . "</p>";
            echo "<p><span class='label'>Time:</span> " . $currentTime . "</p>";
            ?>
        </div>
    </div>
</body>
</html>