<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMAS - Emergency Medical Alert System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0059c0 0%, #80b0d0 20%, #000000 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: white;
        }
        .glass-button {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .glass-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .icon-circle {
            background: white;
            color: #004e92;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-between p-6 pb-12">

    <div class="w-full flex justify-between items-center mt-4">
        <h2 class="text-2xl font-bold tracking-tight">EMAS</h2>
        <div class="flex gap-4 text-2xl">
            <i class="fa-solid fa-user-circle"></i>
            <i class="fa-solid fa-bars"></i>
        </div>
    </div>

    <div class="text-center px-4 mt-12">
        <h1 class="text-5xl font-extrabold leading-tight">
            Emergency Medical <br> Alert System
        </h1>
    </div>

    <div class="w-full max-w-md space-y-6 mt-8">
        
        <a href="report.php" class="glass-button flex items-center p-4 rounded-full w-full">
            <div class="icon-circle shadow-lg">
                <i class="fa-solid fa-bell text-2xl"></i>
            </div>
            <div class="ml-4 text-left">
                <div class="text-xl font-bold">Report Emergency</div>
                <div class="text-xs opacity-80">Connect with University Infirmary</div>
            </div>
        </a>

        <a href="call.php" class="glass-button flex items-center p-4 rounded-full w-full">
            <div class="icon-circle shadow-lg">
                <i class="fa-solid fa-phone text-2xl"></i>
            </div>
            <div class="ml-4 text-left">
                <div class="text-xl font-bold">Call Infirmary</div>
                <div class="text-xs opacity-80">Infirmary Hotline: 0965-953-0227</div>
            </div>
        </a>

        <a href="about.php" class="glass-button flex items-center p-4 rounded-full w-full">
            <div class="icon-circle shadow-lg">
                <i class="fa-solid fa-th-large text-2xl"></i>
            </div>
            <div class="ml-4 text-left">
                <div class="text-xl font-bold">About App</div>
                <div class="text-xs opacity-80">Learn More About the App</div>
            </div>
        </a>

    </div>

    <div class="text-right w-full mt-12 pr-4">
        <p class="text-xl font-semibold italic">Safety Comes First!</p>
        <p class="text-sm opacity-90">— University Infirmary</p>
    </div>

    <?php
        // Basic PHP Logic for handling clicks
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'report') {
                echo "<script>alert('Alerting University Infirmary...');</script>";
            }
        }
    ?>

</body>
</html>