<?php
include 'database.php'; // Ensure the connection to the database

// Fetch the user details to display in the form
// Here, we assume you're fetching user details based on the user ID or any other mechanism.
// For demonstration, I will assume you want to fetch the first user for simplicity.
$stmt = $pdo->prepare("SELECT * FROM users LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
$message = '';  // For error or success messages
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($fullname) || empty($email)) {
        $message = "Please fill in all fields.";
    } else {
        // Handle password change
        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            // Check if current password matches
            if (password_verify($current_password, $user['password'])) {
                if ($new_password === $confirm_password) {
                    // Update password
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email, password = :new_password WHERE id = :user_id");
                    $stmt->bindParam(':new_password', $new_hashed_password, PDO::PARAM_STR);
                } else {
                    $message = "New passwords do not match.";
                }
            } else {
                $message = "Current password is incorrect.";
            }
        } else {
            // Update profile without changing password
            $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email WHERE id = :user_id");
        }

        // Update profile details
        if (empty($message)) {
            try {
                $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();

                $message = "Profile updated successfully!";
            } catch (PDOException $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #23242a;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 500px;
            text-align: center;
        }

        h1 {
            color: #45f3ff;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-size: 14px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            border: 1px solid #444;
        }

        button {
            background-color: #45f3ff;
            color: #1c1c1c;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #45f3ff;
            text-decoration: none;
            font-size: 16px;
        }

        .message {
            margin-top: 10px;
            color: #45f3ff;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Profile</h1>
        
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="password">Current Password:</label>
            <input type="password" name="password">

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password">

            <button type="submit" name="update">Update Profile</button>
        </form>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>

</body>
</html>

