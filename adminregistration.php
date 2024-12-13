<?php
include 'database.php'; // Include your database connection

// Define the admin key (this is a simple example, you can use a more secure approach)
$admin_key = "12345"; // You can generate a random key for more security, or store it in environment variables

// Handle form submission
$message = '';  // For error or success messages
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $admin_key_input = trim($_POST['admin_key']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Debugging output
    echo "Admin Key Input: " . $admin_key_input; // Debugging line

    // Check if the admin key matches the defined key
    if ($admin_key_input !== $admin_key) {
        $message = "Invalid admin key.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to insert the new admin user
        try {
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();

            $message = "Admin account created successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Sign Up</title>
  <link rel="stylesheet" href="style/login.css">
  <style>
    /* Your CSS styles */
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
        height: 500px;
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
        height: 100%;
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
        margin-top: 15px;
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
    <form class="form" method="POST">
      <h2>Admin Register</h2>

      <!-- Display message if any -->
      <?php if ($message): ?>
        <p style="color: #fff; text-align: center;"><?php echo $message; ?></p>
      <?php endif; ?>

      <div class="inputbox">
        <input type="text" name="admin_key" required>
        <span>Admin Key</span>
        <i></i>
      </div>
      <div class="inputbox">
        <input type="text" name="username" required>
        <span>Username</span>
        <i></i>
      </div>
      <div class="inputbox">
        <input type="email" name="email" required>
        <span>Email</span>
        <i></i>
      </div>
      <div class="inputbox">
        <input type="password" name="password" required>
        <span>Password</span>
        <i></i>
      </div>
      <br>
      <input type="submit" value="Sign Up" class="login-btn">
      <a href="adminlogin.php" class="admin-btn">Back to Admin Login</a>
    </form>
  </div>
</body>
</html>
