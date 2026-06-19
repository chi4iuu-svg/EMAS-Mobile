<?php
// Handle the redirect logic before any HTML is sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real app, you would validate credentials here
    // For now, we redirect directly to your main dashboard
    header("Location: Main.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMAS - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0093E9 0%, #004e92 40%, #000000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 40px;
            width: 100%;
            max-width: 350px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .input-field {
            background: white;
            border-radius: 8px;
            color: #333;
            width: 100%;
            padding: 12px 16px;
            outline: none;
            border: none;
        }
        .input-field::placeholder {
            color: #ccc;
        }
        .login-btn {
            background: #003366;
            color: white;
            font-weight: bold;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.2s ease;
        }
        .login-btn:active {
            transform: scale(0.98);
            background: #002244;
        }
        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="login-card text-white">
        <h1 class="text-3xl font-bold text-center mb-10 tracking-wide">EMAS</h1>
        <h2 class="text-2xl font-bold mb-6">Login</h2>

        <form action="login.php" method="POST">
            <div class="mb-5">
                <label class="block text-sm mb-2 opacity-90">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    placeholder="username@gmail.com" 
                    class="input-field"
                    required
                >
            </div>

            <div class="mb-8">
                <label class="block text-sm mb-2 opacity-90">Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Password" 
                        class="input-field"
                        required
                    >
                    <i class="fa-solid fa-eye-slash eye-icon"></i>
                </div>
            </div>

            <button type="submit" class="login-btn text-lg">
                Login
            </button>
        </form>
    </div>

</body>
</html>