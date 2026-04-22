<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "emasmobile");

if (!$conn) {
    die("Database connection failed");
}

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Secure query (prevents SQL injection)
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["admin"] = $user["username"];

        header("Location: dashboard.php");
        exit();

    } else {
        $loginError = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-box {
        background: #fff;
        padding: 40px;
        width: 320px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .login-box input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .login-box button {
        width: 100%;
        padding: 12px;
        background: #2a5298;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
    }

    .login-box button:hover {
        background: #1e3c72;
    }

    .error {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

</head>
<body>

<div class="login-box">

    <h2>Admin Login</h2>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <?php if (!empty($loginError)) { ?>
        <div class="error"><?php echo $loginError; ?></div>
    <?php } ?>

</div>

</body>
</html>