<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Handle new user addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$full_name, $email, $hashed_password]);

        $success_message = "User added successfully!";
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style/manage.css">
    <style>
        body{
            background-color: #23242a;;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px; /* Add space below the heading */
            color: white;
        }

        .BD {
            text-align: center;
            width: 30vh;
            background-color: #45f3ff;
            text-decoration: none;
            border-radius: 20px;
            padding: 10px;
            display: grid;
        }

        .BD:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        }

        /* Form container for adding new user */
        .form-container {
            width: 40%;
            margin: 0 auto;
            padding: 20px;
            background-color: #28292d;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px; /* Space below the form */
        }

        /* Form heading */
        .form-container h2 {
            text-align: center;
            color: #45f3ff;
            margin-bottom: 20px; /* Space below the heading */
        }

        /* Form field styling */
        .form-group {
            margin-bottom: 20px; /* More space between form fields */
            color: #ffffff;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            outline: none;
            background-color: #3a3b3d;
            color: white;
            border-radius: 5px;
        }

        .form-control:focus {
            background-color: black;
            box-shadow: 0 0 5px rgba(70, 243, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 12px 20px;
            background-color: #45f3ff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #33d1d9;
        }

        /* Success/Error messages */
        .error {
            background-color: #ffebee;
            color: #f44336;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background-color: #e8f5e9;
            color: #4caf50;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Table styling */
        table {
            width: 80%;
            margin: 0 auto;
            margin-top: 30px; /* Space above the table */
            border-collapse: collapse;
            text-align: left;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #333436;
            color: white;
        }

        table td {
            background-color: #2f3033;
            color: white;
        }

        /* Add space between the rows in the table */
        table tr {
            margin-bottom: 10px;
        }

        /* Add some padding around the "Back to Dashboard" button */
        .BD {
            margin-top: 40px;
            margin-bottom: 40px; /* Space around the button */
            text-align: center;
            display: grid;
            margin-left: 78vh;
        }
    </style>
</head>
<body>

<h1>Manage Users</h1>

<!-- Display success or error message -->
<?php if (isset($success_message)): ?>
    <div class="success"><?php echo $success_message; ?></div>
<?php elseif (isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>

<!-- New User Form -->
<div class="form-container">
    <h2>Add New User</h2>
    <form method="POST">
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit">Add User</button>
    </form>
</div>

<!-- Display Users Table -->
<table>
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="admin_dashboard.php" class="BD">Back to Dashboard</a>

</body>
</html>
