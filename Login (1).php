<?php
// Handle the redirect logic before any HTML is sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $email = $_POST['email'];
    $password = $_POST['password'];

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
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 40%, #000000 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 15px;
        }

        .login-container {
            position: relative;
            width: 100%;
            max-width: 340px; /* Slightly narrower for compactness */
            margin-top: 40px; 
        }

        .logo-img {
            width: 90px; /* Reduced size */
            height: 90px;
            object-fit: contain;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 20;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px; /* Slightly tighter corners */
            width: 100%;
            padding: 55px 25px 30px 25px; /* Reduced padding */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        /* Compact Header Styling */
        .emas-text {
            font-size: 1.5rem; /* Reduced from 3xl */
            font-weight: 800;
            margin-bottom: 0.25rem; /* Tight gap to logo */
            letter-spacing: 0.1em;
            line-height: 1;
        }

        .login-text {
            font-size: 1.25rem; /* Reduced from 2xl */
            font-weight: 700;
            margin-bottom: 1.25rem; /* Tight gap to form */
            text-align: left;
        }

        .input-field {
            background: white;
            border-radius: 10px;
            color: #333;
            width: 100%;
            padding: 12px 14px; /* Slightly slimmer inputs */
            outline: none;
            border: none;
            font-size: 15px;
            text-align: left;
        }

        .input-field::placeholder {
            color: #aaa;
        }

        .login-btn {
            background: #003366;
            color: white;
            font-weight: bold;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            margin-top: 5px;
            transition: all 0.2s ease;
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Logo on the border -->
        <img src="LOGO.png" alt="Logo" class="logo-img">

        <div class="login-card text-white">
            <!-- Compact E.M.A.S -->
            <h1 class="emas-text">E.M.A.S.</h1>
            
            <h2 class="login-text">Login</h2>

            <form action="Login (1).php" method="POST">
                
                <div class="mb-3 text-left">
                    <label class="block text-xs mb-1 opacity-80 ml-1">User ID</label>
                    <input 
                        type="text" 
                        name="user_id" 
                        placeholder="Enter your ID" 
                        class="input-field"
                        required
                    >
                </div>

                <div class="mb-3 text-left">
                    <label class="block text-xs mb-1 opacity-80 ml-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="username@gmail.com" 
                        class="input-field"
                        required
                    >
                </div>

                <div class="mb-5 text-left">
                    <label class="block text-xs mb-1 opacity-80 ml-1">Password</label>
                    <div class="relative">
                        <input 
                            id="password-input"
                            type="password" 
                            name="password" 
                            placeholder="Password" 
                            class="input-field"
                            required
                        >
                        <i id="toggle-password" class="fa-solid fa-eye-slash eye-icon"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#toggle-password');
        const password = document.querySelector('#password-input');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>