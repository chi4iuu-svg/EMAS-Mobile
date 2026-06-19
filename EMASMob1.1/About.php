<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About EMAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0093E9 0%, #002b4d 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: white;
            margin: 0;
        }
        .main-card {
            background: linear-gradient(180deg, #0056b3 0%, #000814 100%);
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            min-height: 85vh;
            box-shadow: 0 -10px 25px rgba(0,0,0,0.3);
        }
        .text-shadow-custom {
            text-shadow: 0px 4px 10px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="flex flex-col">

    <div class="px-6 pt-12 pb-8">
        <div class="flex items-center">
            <a href="Main.php" class="text-2xl mr-4 hover:opacity-70 transition-opacity">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-4xl font-black flex-1 text-center mr-8 text-shadow-custom leading-tight">
                About<br>the App
            </h1>
        </div>
    </div>

    <div class="main-card w-full flex-1 flex flex-col items-center pt-10 px-8 pb-12">
    
        <div class="mb-4">
            <img src="logo.png" alt="EMAS Logo" class="w-48 h-48 object-contain drop-shadow-2xl">
        </div>

        <div class="text-center mb-8">
            <h2 class="text-5xl font-black tracking-tighter mb-1">EMAS</h2>
            <p class="text-xl font-bold leading-tight">
                Emergency Medical<br>Alert System
            </p>
        </div>

        <div class="max-w-md text-center leading-relaxed text-sm md:text-base opacity-95">
            <p class="mb-4">
                The Emergency Medical Alert System or EMAS is an application system that allows students 
                of the Ateneo de Zamboanga University to directly contact the University Infirmary in 
                times of any emergencies.
            </p>
            <p class="mb-4">
                EMAS has a contact system that allows users and responders to report to the emergency 
                details via sending images and videos of their locations or the emergency itself to the 
                infirmary.
            </p>
            <p>
                Additionally, in times of big emergencies that result in power outages or connectivity issues, 
                the app will display a direct hotline to the infirmary to be able to contact them directly 
                through the phone.
            </p>
        </div>

    </div> </body>
</html>