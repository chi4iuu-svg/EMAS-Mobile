<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Infirmary - EMAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0093E9 0%, #002b4d 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: white;
            overflow: hidden;
        }
        .main-card {
            background: linear-gradient(180deg, #0056b3 0%, #000814 100%);
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            height: 85vh;
            box-shadow: 0 -10px 25px rgba(0,0,0,0.3);
        }
        .call-icon-main {
            background-color: #58b85c;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            position: relative;
        }
        /* Long shadow effect for the phone icon */
        .long-shadow {
            text-shadow: 2px 2px 0px #46944a, 5px 5px 0px #46944a, 10px 10px 20px rgba(0,0,0,0.2);
        }
        .hang-up-btn {
            background-color: #a34848;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: transform 0.2s ease;
        }
        .hang-up-btn:active {
            transform: scale(0.9);
        }
        /* Animation for "Connecting..." */
        .dot-pulse {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }
    </style>
</head>
<body class="flex flex-col">

    <div class="px-6 pt-12 pb-8">
        <div class="flex items-center mb-2">
            <a href="Main.php" class="text-2xl mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold flex-1 text-center mr-8">Call Infirmary</h1>
        </div>
        <p class="text-center text-lg opacity-90">Instant Medical Support, Anytime.</p>
    </div>

    <div class="main-card w-full flex-1 flex flex-col items-center pt-16 px-6">
        
        <h2 class="text-3xl font-bold underline underline-offset-8 mb-16">
            University Infirmary
        </h2>

        <div class="flex flex-col items-center gap-10">
            <div class="call-icon-main">
                <i class="fa-solid fa-comment-dots text-white text-8xl long-shadow"></i>
            </div>

            <div class="text-center">
                <h3 class="text-2xl font-bold">Infirmary Hotline</h3>
                <p class="text-2xl tracking-wider">0965-953-0227</p>
            </div>

            <p class="text-lg opacity-80 dot-pulse mt-4">Connecting...</p>
        </div>

        <div class="mt-auto mb-12">
            <a href="index.php" class="hang-up-btn">
                <i class="fa-solid fa-comment-dots text-white text-3xl rotate-[135deg]"></i>
            </a>
        </div>
    </div>

</body>
</html>