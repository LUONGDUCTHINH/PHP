<?php
session_start();
include 'database.php'; // Include your database connection

$message = '';  // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the entered credentials
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check if the username is valid
    if (!empty($username) && !empty($password)) {
        // Prepare the SQL statement to fetch the admin record based on the username
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        // Fetch the admin record
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // If password matches, store the session data and redirect to the admin dashboard
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: admin_dashboard.php');  // Redirect to the admin dashboard
            exit();
        } else {
            $message = 'Invalid username or password.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Color Mixing Tool</title>
        <link rel="stylesheet" href="style/login.css">
       <style>
         @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");

            * {
            margin: 0;
            box-sizing: border-box;
            }

            body {
            margin: 0;
            padding: 0;
            display: grid;
            place-content: center;
            height: 100vh;
            width: 100vw;
            background: #23242a;
            overflow: hidden;
            color: white;
            font-family: "Poppins", sans-serif;
            }

            .box {
            position: relative;
            width: 380px;
            height: 500px;  /* Increase this value to make the form taller */
            background: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
            font-family: "Poppins";
            --color: #45f3ff;
            }

            .box::before,
            .box::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg, transparent, var(--color), var(--color));
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
            }

            .box::after {
            animation-delay: -3s;
            }

            @keyframes animate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
            }

            .form {
                position: absolute;
                background: #28292d;
                z-index: 10;
                inset: 2px;
                border-radius: 8px;
                padding: 10px 38px;
                display: flex;
                flex-direction: column;
                height: 100%;  /* Ensure it takes the full height of the parent .box */
                }

            .form h2 {
            color: var(--color);
            font-weight: 500;
            text-align: center;
            letter-spacing: 0.1em;
            }

            .inputbox {
            position: relative;
            width: 300px;
            margin-top: 35px;
            }

            .inputbox input {
            position: relative;
            width: 100%;
            padding: 10px 10px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            letter-spacing: 0.05em;
            z-index: 2;
            }

            .inputbox span {
            position: absolute;
            color: #8f8f8f;
            left: 0;
            padding: 20px 0 10px 0;
            font-size: 1em;
            pointer-events: none;
            letter-spacing: 0.05em;
            transform: translateY(-10px);
            transition: 0.5s;
            }

            .inputbox input:valid~span,
            .inputbox input:focus~span {
            color: var(--color);
            transform: translateY(-40px);
            font-size: 0.75em;
            }

            .inputbox i {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: var(--color);
            transition: 0.5s;
            border-radius: 4px;
            pointer-events: none;
            }

            .inputbox input:valid~i,
            .inputbox input:focus~i {
            height: 40px;
            }

            .links {
            display: flex;
            justify-content: space-between;
            }

            .links a {
            margin: 18px 0;
            font-size: 0.9em;
            text-decoration: none;
            color: #8f8f8f;
            }

            .links a:hover {
            color: var(--color);
            }

            input[type="submit"] {
            width: 300px;
            background: var(--color);
            border: none;
            outline: none;
            padding: 11px 25px;
            margin-top: 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            }

            .admin-btn {
            width: 300px;
            background: transparent;
            border: 2px solid var(--color);
            outline: none;
            padding: 11px 25px;
            margin-top: 15px; /* Ensures there is space between Login and Admin Login buttons */
            border-radius: 4px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            color: var(--color);
            text-decoration: none;
            display: block;
            cursor: pointer;
            }

            .admin-btn:hover {
            background: var(--color);
            color: #fff;
            }
       </style>
    </head>
    <body>
    <div class="bg"></div>
        <div class="star-field">
        <div class="layer"></div>
        <div class="layer"></div>
        <div class="layer"></div>   
        </div>
    <div class="box">
        <div class="form">
            <h2>Admin Login</h2>
            <form method="POST">
                <div class="inputbox">
                    <input type="text" name="username" required="required">
                    <span>Username</span>
                    <i></i>
                </div>
                <div class="inputbox">
                    <input type="password" name="password" required="required">
                    <span>Password</span>
                    <i></i>
                </div>
                <?php if ($message): ?>
                    <div class="error"><?php echo $message; ?></div>
                <?php endif; ?>
                <input type="submit" value="Login" class="login-btn">
                <a href="adminregistration.php" class="admin-btn">Admin Register</a>
                <h2>OR</h2>
                <a href="login.php" class="admin-btn">Back to User Login</a>
            </form>
        </div>
    </div>
</body>
</html>

