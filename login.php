<?php
session_start();  // Start the session

// Include database connection file
include 'database.php';  // Ensure $pdo is initialized

$message = '';  // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($username) && !empty($password)) {
        try {
            // Prepare SQL to select user based on username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    
                    // Redirect to dashboard or home page
                    header("Location: Homepage.php");
                    exit();
                } else {
                    $message = "Invalid password. Please try again.";
                }
            } else {
                $message = "No account found with that username.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Luong Duc Thinh</title>
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
                padding: 50px 40px;
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
            margin-top: 45px;
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
                padding: 14px 25px;  /* Increase padding to make the button taller */
                margin-top: 20px;  /* Increase the margin to add more space between the button and input fields */
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
        <form class="form" action="" method="POST">
            <h2>Sign In</h2>
            <?php if (!empty($message)): ?>
                <p style="color: #ff6b6b; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <div class="inputbox">
                <input type="text" name="username" required>
                <span>Username</span>
                <i></i>
            </div>
            <div class="inputbox">
                <input type="password" name="password" required>
                <span>Password</span>
                <i></i>
            </div>
            <div class="links">
                <a href="forgot_password.php">Forgot Password?</a>
                <a href="register.php">Signup</a>
            </div>
            <input type="submit" value="Login">
            <a href="adminlogin.php" class="admin-btn">Admin Login</a>
        </form>
    </div>
</body>
</html>
