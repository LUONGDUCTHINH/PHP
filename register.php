
<?php
// Include database connection file
include 'database.php'; // Ensure 'database.php' initializes the $pdo object correctly.

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($fullname) && !empty($username) && !empty($email) && !empty($password)) {
        // Sanitize inputs
        $fullname = htmlspecialchars($fullname);
        $username = htmlspecialchars($username);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare SQL and bind parameters
            $stmt = $pdo->prepare("INSERT INTO users (fullname, username, email, password) VALUES (:fullname, :username, :email, :password)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Something went wrong. Please try again.";
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
  <title>Sign Up Form</title>
  <link rel="stylesheet" href="style/login.css">
  <style>


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
    <form class="form" action="" method="POST">
      <h2>Sign Up</h2>
      <?php if (!empty($message)): ?>
        <p style="color: #45f3ff; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <div class="inputbox">
        <input type="text" name="fullname" required>
        <span>Full Name</span>
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
      <div class="links">
        <a href="#">Already have an account?</a>
        <a href="login.php">Login</a>
      </div>
      <input type="submit" value="Sign Up">
      <!-- Admin Login Button -->
      <a href="adminlogin.php" class="admin-btn">Admin Login</a>
    </form>
  </div>
</body>
</html>